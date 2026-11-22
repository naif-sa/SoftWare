<?php

namespace NAIF_CMS\System\Library;

class Loader
{

    public static function run($className)
    {
        $className = str_replace(['NAIF_CMS\\', '\\'], ['', DS], $className);
        $className = $className . '.php';
        $className = APP_PATH . $className;
        if (file_exists($className)) {
            require_once $className;
        } else {
            return;
        }
    }

}

spl_autoload_register(__NAMESPACE__ . '\Loader::run');