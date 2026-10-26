<?php

namespace NAIF_CMS\System\Library;

class Registry
{

    private static $instance;
    public $classes = [];

    private function __construct()
    {
        // ... //
    }

    public function __set($key, $object)
    {
        $this->classes[$key] = $object;
    }

    public function __get($key)
    {
        return $this->classes[$key];
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}