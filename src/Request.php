<?php
namespace SapiStudio\Proxmox;

class Request
{
    /**
     * Provider::Request()
     * 
     * @return
     */
    public static function Request($actionPath, $params = [], $method = 'GET')
    {
        $handler = Handler::GLOBALIZE_NAME;
        return $handler::configure()->requestResource($actionPath,$params,$method);
    }
}