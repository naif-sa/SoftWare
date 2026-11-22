<?php

namespace NAIF_CMS\System\Library;

use NAIF_CMS\System\Helper\Helper;

class Request
{

    use Helper;

    private static $instance;
    private $session;

    private function __construct($session)
    {
        $this->setSeesion($session);
    }

    public static function getInstance($session)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($session);
        }
        return self::$instance;
    }

    public function setSeesion($session)
    {
        $this->session = $session;
    }

    public function check($type = 'POST', $name = 'submit')
    {
        $requestType = $type === 'POST' ? $_POST : $_GET;
        if (isset($requestType[$name]) && isset($this->session->CSRFToken)) {
            if (isset($requestType['CSRFToken']) && hash_equals($requestType['CSRFToken'], $this->session->CSRFToken)) {
                $this->session->CSRFToken = $this->generateToken();
                return true;
            } else {
                die('Page is Expired');
            }
        }
        return false;
    }

    public function clean($type = 'POST')
    {
        $requestType = $type === 'POST' ? $_POST : $_GET;
        return $this->filterArray($requestType);
    }

}