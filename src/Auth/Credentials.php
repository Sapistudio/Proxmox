<?php

namespace SapiStudio\Proxmox\Auth;

class Credentials
{
    protected static $hostname;
    protected static $username;
    protected static $password;
    protected static $realm;
    protected static $port;
    
    /** Credentials::__construct() */
    public function __construct($credentials = [])
    {
        $check          = false;
        self::$hostname = !empty($credentials['hostname'])  ? $credentials['hostname']  : $check = true;
        self::$username = !empty($credentials['username'])  ? $credentials['username']  : $check = true;
        self::$password = !empty($credentials['password'])  ? $credentials['password']  : $check = true;
        self::$realm    = !empty($credentials['realm'])     ? $credentials['realm']     : 'pam';
        self::$port     = !empty($credentials['port'])      ? $credentials['port']      : 8006;
        if ($check){
            throw new \Exception('Require in array [hostname], [username], [password], [realm], [port]');
        }
    }

    /** Credentials::__toString() */
    public function __toString()
    {
        return sprintf('[Host: %s:%s], [Username: %s@%s].',self::$hostname,self::$port,self::$username,self::$realm);
    }

    /** Credentials::getApiUrl()*/
    public function getApiUrl()
    {
        return 'https://'.self::$hostname.':'.self::$port.'/api2';
    }

    /** Credentials::getHostname()*/
    public function getHostname()
    {
        return self::$hostname;
    }

    /** Credentials::getUsername()*/
    public function getUsername()
    {
        return self::$username;
    }

    /** Credentials::getPassword()*/
    public function getPassword()
    {
        return self::$password;
    }

    /** Credentials::getRealm()*/
    public function getRealm()
    {
        return self::$realm;
    }

    /** Credentials::getPort() */
    public function getPort()
    {
        return self::$port;
    }
}
