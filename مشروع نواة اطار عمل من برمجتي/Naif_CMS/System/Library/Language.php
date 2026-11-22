<?php

namespace NAIF_CMS\System\Library;

class Language
{

    private static $instance;
    private $dictionary = [];

    private function __construct()
    {
        // ... // 
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function load($path)
    {
        $defaultLanguage = APP_LANGUAGE;
        $fileToLoad      = LANGUAGE_PATH . $defaultLanguage . DS . str_replace('.', DS, $path) . '.php';
        if (file_exists($fileToLoad)) {
            require_once $fileToLoad;
            if (is_array($_) && !empty($_)) {
                foreach ($_ as $key => $value) {
                    $this->dictionary[$key] = $value;
                }
            }
        } else {
            die('Language Not Found');
        }
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->dictionary)) {
            return $this->dictionary[$key];
        }
    }

    public function feedKey($key, $data)
    {
        if (array_key_exists($key, $this->dictionary)) {
            array_unshift($data, $this->dictionary[$key]);
            return call_user_func_array('sprintf', $data);
        }
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

}