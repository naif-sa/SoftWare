<?php

namespace NAIF_CMS\System\Library;

use NAIF_CMS\System\Helper\Helper;

class FrontController
{

    use Helper;

    private static $instance;
    private $controller = 'Home';
    private $action     = 'default';
    private $param      = [];
    private $Registry;
    private $Template;

    private function __construct()
    {
        $this->getUrl();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setRegistry($Registry)
    {
        $this->Registry = $Registry;
    }

    public function setTemplate($Template)
    {
        $this->Template = $Template;
    }

    public function dispatch()
    {
        $controller   = 'NAIF_CMS\\FrontEND\\Controller\\' . $this->controller . 'Controller';
        $action       = $this->action;
        $this->action = $action . 'Action';
        if (!class_exists($controller)) {
            $this->redirect('not_found');
        }
        if (!method_exists($controller, $this->action)) {
            $this->redirect('not_found');
        }
        $controllerObject = new $controller();
        $controllerObject->setRegistry($this->Registry);
        $controllerObject->setController($this->controller);
        $controllerObject->setAction($action);
        $controllerObject->setTemplate($this->Template);
        call_user_func_array([$controllerObject, $this->action], $this->param);
    }

    private function getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = $this->filterUrl($url);
        $url = trim($url, '/');
        $url = explode('/', $url);
        if (isset($url[0]) && $url[0] !== '') {
            $controller = explode('_', $url[0]);

            if (!empty($controller)) {
                $tmp = '';
                foreach ($controller as $name) {
                    $tmp .= ucwords($name);
                }
                $controller = $tmp;
            } else {
                $controller = ucwords($url[0]);
            }

            $this->controller = $controller;
            unset($url[0]);
        }
        if (isset($url[1]) && $url[1] !== '') {
            $action = explode('_', $url[1]);

            if (!empty($action)) {
                $tmp = '';
                foreach ($action as $index => $name) {
                    if ($index === 0) {
                        $tmp .= mb_strtolower($name);
                    } else {
                        $tmp .= ucwords($name);
                    }
                }
                $action = $tmp;
            } else {
                $action = mb_strtolower($url[1]);
            }

            $this->action = $action;
            unset($url[1]);
        }
        if (isset($url[2]) && $url[2] !== '') {
            $this->param = array_values($url);
        }
    }

}