<?php

namespace NAIF_CMS\System\Library;

use NAIF_CMS\System\Helper\TemplateHelper;

class Template
{

    use TemplateHelper;

    private static $instance;
    private $templateConfig;
    private $actionPath;
    private $actionName;
    private $controllerName;
    private $data;
    private $Registry;

    private function __construct($templateConfig)
    {
        $this->setTemplateConfig($templateConfig);
    }

    public function __get($object)
    {
        return $this->Registry->classes[$object];
    }

    public static function getInstance($templateConfig)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($templateConfig);
        }
        return self::$instance;
    }

    public function setTemplateConfig($templateConfig)
    {
        $this->templateConfig = $templateConfig;
    }

    public function setRegistry($Registry)
    {
        $this->Registry = $Registry;
    }

    public function swapTemplate($templateFiles)
    {
        $this->templateConfig['template'] = $templateFiles;
    }

    public function setActionPath($action)
    {
        $this->actionPath = $action;
    }

    public function setUrl($controller, $action)
    {
        $this->controllerName = $controller;
        $this->actionName     = $action;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function renderView()
    {
        extract($this->data);
        if (isset($this->templateConfig['template'])) {
            if (!empty($this->templateConfig)) {
                foreach ($this->templateConfig['template'] as $fileKey => $file) {
                    if ($fileKey === ':action') {
                        if (file_exists($this->actionPath)) {
                            require_once $this->actionPath;
                        } else {
                            die('Template Not Found');
                        }
                    } else {
                        if (file_exists(TEMPLATE_PATH . APP_LANGUAGE . DS . $file)) {
                            require_once TEMPLATE_PATH . APP_LANGUAGE . DS . $file;
                        } else {
                            die('Template Not Found');
                        }
                    }
                }
            }
        }
    }

}