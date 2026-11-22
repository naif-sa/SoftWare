<?php

namespace NAIF_CMS\System\Library;

class Messenger
{

    const TYPE_SUCCESS = 1;
    const TYPE_ERROR   = 2;
    const TYPE_INFO    = 3;
    const TYPE_WARNING = 4;

    private static $instance;
    private $session;
    private $message = [];

    private function __construct($session)
    {
        $this->setSession($session);
    }

    public static function getInstance($session)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($session);
        }
        return self::$instance;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function add($message, $type = self::TYPE_SUCCESS)
    {
        if (!$this->checkMessage()) {
            $this->session->message = [];
        }
        $messages               = $this->session->message;
        $messages[]             = [$message, $type];
        $this->session->message = $messages;
    }

    private function checkMessage()
    {
        return isset($this->session->message);
    }

    public function getMessages()
    {
        if ($this->checkMessage()) {
            $this->message = $this->session->message;
            unset($this->session->message);
            return $this->message;
        }
        return [];
    }

}