<?php

namespace NAIF_CMS\System\Helper;

trait TemplateHelper
{

    public function showValue($fieldName, $object = null)
    {
        if (isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) {
            return $_POST[$fieldName];
        } else {
            if (!is_null($object)) {
                return $object->$fieldName;
            }
        }
        return '';
    }

    public function selected($fieldName, $value, $object = null)
    {
        if (isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) {
            return $_POST[$fieldName] === $value ? 'selected' : '';
        } else {
            if (!is_null($object)) {
                return $object->$fieldName === $value ? 'selected' : '';
            }
        }
        return '';
    }

    public function url($path = null)
    {
        return APP_URL . $path;
    }

    public function assetUrl($path = null)
    {
        return ASSET_PATH . $path;
    }

    public function matchUrl($url)
    {
        return $this->controllerName . '/' . $this->actionName === $url;
    }

    public function CSRFToken()
    {
        if (!isset($this->session->CSRFToken)) {
            $this->session->CSRFToken = hash('SHA256', random_bytes(32));
            $CSRFToken                = $this->session->CSRFToken;
        } else {
            $CSRFToken = $this->session->CSRFToken;
        }
        return "<input type=\"hidden\" name=\"CSRFToken\" value=\"" . $CSRFToken . "\" />\n";
    }

    public function condition($ifstatement, $true, $false)
    {
        return $ifstatement ? $true : $false;
    }

}