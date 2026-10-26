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

    public function check($requestType = 'POST', $requestName = 'submit')
    {
        if ($requestType === 'GET') {
            if (isset($_GET[$requestName])) {
                return true;
            }
            return false;
        } else if ($requestType === 'POST') {
            if (isset($_POST[$requestName])) {
                if (isset($_POST['CSRFToken']) && hash_equals($_POST['CSRFToken'], $this->session->CSRFToken)) {
                    unset($this->session->CSRFToken);
                    return true;
                } else {
                    die('Page is Expired');
                }
            }
            return false;
        }
    }

    public function clean($requestType = null)
    {
        if ($requestType === 'GET') {
            return $this->filterArray($_GET);
        } else {
            return $this->filterArray($_POST);
        }
    }

}