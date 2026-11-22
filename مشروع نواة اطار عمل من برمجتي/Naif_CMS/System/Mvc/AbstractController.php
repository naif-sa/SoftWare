<?php

namespace NAIF_CMS\System\Mvc;

abstract class AbstractController
{

    protected $controller;
    protected $action;
    protected $Registry;
    protected $Template;
    protected $data = [];

    public function __get($object)
    {
        return $this->Registry->classes[$object];
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setRegistry($Registry)
    {
        $this->Registry = $Registry;
    }

    public function setTemplate($template)
    {
        $this->Template = $template;
    }

    protected function view()
    {
        $this->data = array_merge($this->data, $this->Language->getDictionary());
        $view       = VIEW_PATH . $this->controller . DS . $this->action . '.php';
        if (file_exists($view)) {
            $this->Template->setRegistry($this->Registry);
            $this->Template->setData($this->data);
            $this->Template->setUrl($this->controller, $this->action);
            $this->Template->setActionPath($view);
            $this->Template->renderView();
        } else {
            die('View Not Found');
        }
    }

}