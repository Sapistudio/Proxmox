<?php
namespace SapiStudio\Proxmox;

class Handler
{
    const AUTH_COOKIE       = 'PVEAuthCookie';
    const AUTH_TOKEN        = 'CSRFPreventionToken';
    const AUTH_VALIDITY     = 3600;
    const GLOBALIZE_NAME    = 'HandlerApiRequest';
    
    private static $requestCookies;
    private $credentials;
    private $responseType;
    private $fakeType;
    private $authToken;
    private static $instance;
    private static $cacheHash;
    private $nodeHandler;
    
    /** Handler::__callStatic() */
    public static function __callStatic($name, $arguments)
    {
        self::configure($arguments[0]);
        
        if(method_exists($this->nodeHandler,$name) ){
            throw new \Exception('Could not find data provider '.$provider);
        }
        
        $provider = __NAMESPACE__.'\Data\\'.$name;
        if (!class_exists($provider)){
            throw new \Exception('Could not find data provider '.$provider);
        }
        return new $provider;
    }
    
    /** Handler::__call() */
    public function __call($name, $arguments)
    {
        if(!method_exists($this->nodeHandler,$name) ){
            throw new \Exception('Could not find data method '.$name);
        }
        return $this->nodeHandler->$name(...$arguments);
    }
    
    /** Handler::configure()*/
    public static function configure($options = [])
    {
        if (null === static::$instance)
            static::$instance = new static($options);
        return static::$instance;
    }
    
    /** Handler::setNodeId()*/
    public function setNodeId($nodeId){
        $this->nodeHandler = new Data\Nodes($nodeId);
        return $this;
    }
    
    /** Handler::__construct()*/
    public function __construct($credentials,$responseType = 'object'){
        self::$cacheHash = md5(realpath(dirname(__FILE__)));                
        $this->globalise();
        $this->setHttpClient();
        $this->setCredentials($credentials);
        $this->setResponseType($responseType);
    }
    
    /** Handler::globalise()*/
    public function globalise($alias = self::GLOBALIZE_NAME)
    {
        if (substr($alias, 0, 1) != '\\')
            $alias = '\\' . $alias;
        if (!class_exists($alias))
            class_alias(get_class($this), $alias);
        if (null === static::$instance)
            self::$instance = $this;
    }
    
    /** Handler::requestResource()*/
    public function requestResource($actionPath, $params = [], $method = 'GET')
    {
        if (!is_array($params))
            throw new \InvalidArgumentException('Params should be an associative array.');
        if (substr($actionPath, 0, 1) != '/')
            $actionPath = '/' . $actionPath;
        $url        = $this->getApiUrl().$actionPath;
        $response   = false;
        switch ($method){
            case 'GET':
                $response = $this->httpClient->get($url,$this->buildHttpOptions(['query' => $params]));
                break;
            case 'POST':
                $response = $this->httpClient->post($url,$this->buildHttpOptions(['form_params' => $params]));
                break;
            case 'PUT':
                $response = $this->httpClient->put($url,$this->buildHttpOptions(['form_params' => $params]));
                break;
            case 'DELETE':
                $response = $this->httpClient->delete($url,$this->buildHttpOptions(['form_params' => $params]));
                break;
            default:
                throw new \Exception("HTTP Request method {$method} not allowed.");
        }
        if(!$response)
            throw new \Exception("Invalid resources response");
        return $this->processHttpResponse($response);
    }
    
    /** Handler::buildHttpOptions()*/
    private function buildHttpOptions($options = []){
        $this->httpClient->setRequestCookiesFromArray([self::AUTH_COOKIE=>$this->authToken->getTicket()],$this->credentials->getHostname());
        return array_merge([
            'verify'        => false,
            'exceptions'    => false,
            'headers'       => [self::AUTH_TOKEN => $this->authToken->getCsrf()]
        ],$options);
    }
    
    /** Handler::processHttpResponse()*/
    private function processHttpResponse($response)
    {
        switch ($this->fakeType) {
            case 'pngb64':
                $base64 = base64_encode($response->getBody());
                return 'data:image/png;base64,' . $base64;
                break;
            case 'object':
                return json_decode($response->getBody());
                break;
            case 'array':
                return json_decode($response->getBody(),true);
                break;
            default:
                return $response->getBody();
        }
    }

    /** Handler::setHttpClient()*/
    public function setHttpClient($httpClient = null)
    {
        $this->httpClient = \SapiStudio\Http\Browser\StreamClient::make();
    }

    /** Handler::login()*/
    public function login()
    {
        $response = $this->httpClient->cacheRequest(self::$cacheHash)->post($this->credentials->getApiUrl() . '/json/access/ticket', [
                'verify'        => false,
                'form_params'   => [
                    'username'      => $this->credentials->getUsername(),
                    'password'      => $this->credentials->getPassword(),
                    'realm'         => $this->credentials->getRealm(),
                ],
            ]);
        $response = json_decode($response);                
        if (!$response->data)
            throw new \Exception('Can not login using credentials: ' . $this->credentials);
        return new Auth\Token($response->data->CSRFPreventionToken,$response->data->ticket,$response->data->username);
    }

    /** Handler::getCredentials()*/
    public function getCredentials()
    {
        return $this->credentials;
    }

    /** Handler::setCredentials()*/
    public function setCredentials($credentials)
    {
        if (!$credentials instanceof Auth\Credentials) {
            $credentials = new Auth\Credentials($credentials);
        }
        $this->credentials = $credentials;
        $this->authToken = $this->login();
    }

    /** Handler::setResponseType()*/
    public function setResponseType($responseType = 'object')
    {
        $supportedFormats = ['json', 'html', 'extjs', 'text', 'png'];
        if (in_array($responseType, $supportedFormats)) {
            $this->fakeType     = false;
            $this->responseType = $responseType;
        } else {
            switch ($responseType) {
                case 'pngb64':
                    $this->fakeType     = 'pngb64';
                    $this->responseType = 'png';
                    break;
                case 'object':
                    $this->responseType = 'json';
                    $this->fakeType     = $responseType;
                    break;
                default:
                    $this->responseType = 'json';
                    $this->fakeType     = 'array';
            }
        }
    }

    /** Handler::getResponseType()*/
    public function getResponseType()
    {
        return $this->fakeType ?: $this->responseType;
    }

    /** Handler::getApiUrl()*/
    public function getApiUrl()
    {
        return $this->credentials->getApiUrl() . '/' . $this->responseType;
    }

    /** Handler::getVersion()*/
    public function getVersion()
    {
        return $this->getRequest('/version');
    }
    
    /** Handler::listNodes()*/
    public function listNodes()
    {
        return $this->getRequest("/nodes");
    }
    
    /** Handler::listResources()*/
    public function listResources()
    {
        return $this->getRequest("/cluster/resources");
    }
    
    /** Handler::getRequest() */
    public function getRequest($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params);
    }

    /** Handler::setRequest()*/
    public function setRequest($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params, 'PUT');
    }

    /**
     * Handler::postRequest()*/
    public function postRequest($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params, 'POST');
    }

    /** Handler::deleteRequest()*/
    public function deleteRequest($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params, 'DELETE');
    }
}
