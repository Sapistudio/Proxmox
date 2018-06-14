<?php
namespace SapiStudio\Proxmox;

class Handler
{
    const AUTH_COOKIE       = 'PVEAuthCookie';
    const AUTH_TOKEN        = 'CSRFPreventionToken';
    const AUTH_VALIDITY     = 3600;
    const GLOBALIZE_NAME    = 'CSRFPreventionToken';
    
    private static $requestCookies;
    private $credentials;
    private $responseType;
    private $fakeType;
    private $authToken;
    private static $instance;
    private static $cacheHash;
    
    /**
     * Handler::__callStatic()
     * 
     * @param mixed $name
     * @param mixed $arguments
     * @return
     */
    public static function __callStatic($name, $arguments)
    {
        self::configure($arguments[0]);
        $provider = __NAMESPACE__.'\Data\\'.$name;
        if (!class_exists($provider)){
            throw new \Exception('Could not find data provider '.$provider);
        }
        return new $provider;
    }
    
    /**
     * Handler::configure()
     * 
     * @param mixed $options
     * @return
     */
    public static function configure($options = [])
    {
        if (null === static::$instance)
            static::$instance = new static($options);
        return static::$instance;
    }
    
    /**
     * Handler::__construct()
     * 
     * @return
     */
    public function __construct($credentials,$responseType = 'object',$httpClient = null){
        $this->globalise();
        $this->setHttpClient($httpClient);
        $this->setCredentials($credentials);
        $this->setResponseType($responseType);
        self::$cacheHash = md5(realpath(dirname(__FILE__)));
    }
    
    /**
     * Handler::globalise()
     * 
     * @param mixed $alias
     * @return void
     */
    public function globalise($alias = self::GLOBALIZE_NAME)
    {
        if (substr($alias, 0, 1) != '\\')
            $alias = '\\' . $alias;
        if (!class_exists($alias))
            class_alias(get_class($this), $alias);
        if (null === static::$instance)
            self::$instance = $this;
    }
    
    /**
     * Handler::requestResource()
     * 
     * @return
     */
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
    
    /**
     * Handler::buildHttpOptions()
     * 
     * @return
     */
    private function buildHttpOptions($options = []){
        if(!self::$requestCookies)
            self::$requestCookies = \GuzzleHttp\Cookie\CookieJar::fromArray([self::AUTH_COOKIE=>$this->authToken->getTicket()],$this->credentials->getHostname());
        return array_merge([
            'verify'        => false,
            'exceptions'    => false,
            'cookies'       => self::$requestCookies,
            'headers'       => [self::AUTH_TOKEN => $this->authToken->getCsrf()]
        ],$options);
        return \GuzzleHttp\Cookie\CookieJar::fromArray([self::AUTH_COOKIE=>$this->authToken->getTicket()],$this->credentials->getHostname());
    }
    
    /**
     * Handler::processHttpResponse()
     * 
     * @return
     */
    private function processHttpResponse($response)
    {
        switch ($this->fakeType) {
            case 'pngb64':
                $base64 = base64_encode($response->getBody());
                return 'data:image/png;base64,' . $base64;
                break;
            case 'object':
                return json_decode($response->getBody()->getContents());
                break;
            case 'array':
                return json_decode($response->getBody()->getContents(),true);
                break;
            default:
                return $response->getBody()->getContents();
        }
    }

    /**
     * Handler::setHttpClient()
     * 
     * @return
     */
    public function setHttpClient($httpClient = null)
    {
        $this->httpClient = $httpClient ?: new \GuzzleHttp\Client();
    }

    /**
     * Handler::login()
     * 
     * @return
     */
    public function login()
    {
        $cache          = new \Symfony\Component\Cache\Simple\FilesystemCache();
        $response       = $cache->get(self::$cacheHash);
        if (!$response->data){
            $loginUrl = $this->credentials->getApiUrl() . '/json/access/ticket';
            $response = $this->httpClient->post($loginUrl, [
                'verify'        => false,
                'form_params'   => [
                    'username'      => $this->credentials->getUsername(),
                    'password'      => $this->credentials->getPassword(),
                    'realm'         => $this->credentials->getRealm(),
                ],
            ]);
            $response = json_decode($response->getBody()->getContents());
            $cache->set(self::$cacheHash,$response,SELF::AUTH_VALIDITY);
        }
        if (!$response->data)
            throw new \Exception('Can not login using credentials: ' . $this->credentials);
        return new Auth\Token($response->data->CSRFPreventionToken,$response->data->ticket,$response->data->username);
    }

    /**
     * Handler::getCredentials()
     * 
     * @return
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Handler::setCredentials()
     * 
     * @return
     */
    public function setCredentials($credentials)
    {
        if (!$credentials instanceof Auth\Credentials) {
            $credentials = new Auth\Credentials($credentials);
        }
        $this->credentials = $credentials;
        $this->authToken = $this->login();
    }

    /**
     * Handler::setResponseType()
     * 
     * @return
     */
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

    /**
     * Handler::getResponseType()
     * 
     * @return
     */
    public function getResponseType()
    {
        return $this->fakeType ?: $this->responseType;
    }

    /**
     * Handler::getApiUrl()
     * 
     * @return
     */
    public function getApiUrl()
    {
        return $this->credentials->getApiUrl() . '/' . $this->responseType;
    }

    /**
     * Handler::getVersion()
     * 
     * @return
     */
    public function getVersion()
    {
        return $this->get('/version');
    }
    
    /**
     * Handler::listNodes()
     * 
     * @return
     */
    public function listNodes()
    {
        return $this->get("/nodes");
    }


    /**
     * Handler::get()
     * 
     * @return
     */
    public function get($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params);
    }

    /**
     * Handler::set()
     * 
     * @return
     */
    public function set($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params, 'PUT');
    }

    /**
     * Handler::create()
     * 
     * @return
     */
    public function create($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params, 'POST');
    }

    /**
     * Handler::delete()
     * 
     * @return
     */
    public function delete($actionPath, $params = [])
    {
        return $this->requestResource($actionPath, $params, 'DELETE');
    }
}
