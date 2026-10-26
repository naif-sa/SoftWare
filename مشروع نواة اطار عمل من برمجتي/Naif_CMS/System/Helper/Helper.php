<?php

namespace NAIF_CMS\System\Helper;

use NAIF_CMS\System\Library\Database;

trait Helper
{

    public function redirect($url = null, $customURL = false)
    {
        $baseURL = APP_URL;
        if (!is_null($url) && $customURL) {
            $baseURL = $url;
        } else {
            if (!is_null($url)) {
                $baseURL .= $url;
            }
        }
        header('Location: ' . $baseURL);
        exit();
    }

    public function filterInt($int)
    {
        return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    }

    public function filterUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    public function filterString($string)
    {
        $db     = Database::getInstance();
        $string = $db->escapeString($string);
        $string = strip_tags($string);
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function filterArray($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->filterArray($value);
            } else {
                $array[$key] = $this->filterString($value);
            }
        }
        return $array;
    }

    public function flush()
    {
        if (ob_get_length()) {
            ob_flush();
        }
        flush();
    }

}