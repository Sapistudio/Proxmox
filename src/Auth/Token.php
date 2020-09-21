<?php
namespace SapiStudio\Proxmox\Auth;

class Token
{
    private $timestamp;
    private $CSRFPreventionToken;
    private $ticket;
    private $username;

    /** Token::__construct()*/
    public function __construct($csrf, $ticket, $username)
    {
        $this->timestamp            = time();
        $this->CSRFPreventionToken  = $csrf;
        $this->ticket               = $ticket;
        $this->username             = $username;
    }

    /** Token::getCsrf()*/
    public function getCsrf()
    {
        return $this->CSRFPreventionToken;
    }

    /** Token::getTicket()*/
    public function getTicket()
    {
        return $this->ticket;
    }

    /**  Token::getUsername()  */
    public function getUsername()
    {
        return $this->username;
    }

    /** Token::getTimestamp()*/
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
