<?php
namespace SapiStudio\Proxmox\Auth;

class Token
{
    private $timestamp;
    private $CSRFPreventionToken;
    private $ticket;
    private $username;

    /**
     * Token::__construct()
     * 
     * @return
     */
    public function __construct($csrf, $ticket, $username)
    {
        $this->timestamp            = time();
        $this->CSRFPreventionToken  = $csrf;
        $this->ticket               = $ticket;
        $this->username             = $username;
    }

    /**
     * Token::getCsrf()
     * 
     * @return
     */
    public function getCsrf()
    {
        return $this->CSRFPreventionToken;
    }

    /**
     * Token::getTicket()
     * 
     * @return
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Token::getUsername()
     * 
     * @return
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Token::getTimestamp()
     * 
     * @return
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}