<?php

namespace NAIF_CMS\System\Library;

class RandomData
{

    private static $instance;

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

    public function text($max = 20)
    {
        $char       = time() . '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $charLength = mb_strlen($char);
        $randomChar = '';
        for ($i = 0; $i < $max; $i++) {
            $randomChar .= $char[random_int(0, $charLength - 1)];
        }
        return $randomChar;
    }

    public function email($max = 8)
    {
        $char       = time() . '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $charLength = mb_strlen($char);
        $randomChar = '';
        for ($i = 0; $i < $max; $i++) {
            $randomChar .= $char[random_int(0, $charLength - 1)];
        }
        return $randomChar . '@example.com';
    }

    public function password($max = 15)
    {
        $char       = time() . '0123456789qwertyuiopasdfghjklzxcvb*&^%$#@!nm!@#$%^&*QWERTYUIOPASDFGHJKLZXCVBNM';
        $charLength = mb_strlen($char);
        $randomChar = '';
        for ($i = 0; $i < $max; $i++) {
            $randomChar .= $char[random_int(0, $charLength - 1)];
        }
        return $randomChar;
    }

    public function number($max = 10)
    {
        $char       = time() . '0123456789';
        $charLength = mb_strlen($char);
        $randomChar = '';
        for ($i = 0; $i < $max; $i++) {
            $randomChar .= $char[random_int(0, $charLength - 1)];
        }
        return $randomChar;
    }

    public function date($min = null, $max = null)
    {
        if (!is_null($min)) {
            if (mb_strlen($min) === 4) {
                $min = $min . '-01-01';
            }
        }
        if (!is_null($max)) {
            if (mb_strlen($max) === 4) {
                $max = $max . '-12-30';
            }
        }
        $max  = $max ? $max : (date('Y') - 1) . '-12-30';
        $min  = $min ? $min : 1970 . '-01-01';
        $date = mt_rand(strtotime($min), strtotime($max));
        return $date;
    }

    public function code($length = 20, $dlimiterLength = 4, $dlimiter = '-')
    {
        $char       = time() . '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $charLength = mb_strlen($char);
        $randomChar = '';
        for ($i = 0; $i < $length; $i++) {
            $randomChar .= $char[random_int(0, $charLength - 1)];
        }
        $char = strtoupper($randomChar);
        $char = substr($char, 0, $length);
        $char = str_split($char, $dlimiterLength);
        $char = implode($dlimiter, $char);
        return $char;
    }

}