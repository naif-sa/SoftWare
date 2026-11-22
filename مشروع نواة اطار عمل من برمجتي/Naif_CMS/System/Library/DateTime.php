<?php

namespace NAIF_CMS\System\Library;

class DateTime
{

    private static $instance;
    private $time;
    private $Language;

    private function __construct($Language)
    {
        $this->setLanguage($Language);
        $this->setTimeZone();
        $this->setTime(time());
    }

    public static function getInstance($Language)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($Language);
        }
        return self::$instance;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setLanguage($Language)
    {
        $this->Language = $Language;
    }

    public function format($dateToString = false)
    {
        if ($dateToString) {
            $pmOrAm    = $this->getPmOrAm();
            $date      = date('Y-m-d', $this->getTime());
            $date      = explode('-', $date);
            $dayName   = $this->Language->get('text_DateTime_dayName')[date('w', $this->getTime())];
            $monthName = $this->Language->get('text_DateTime_monthName')[$date[1]];
            $text      = $dayName . '، ' . $date[2] . ' ' . $monthName . ' ' . $date[0] . ' ' . date(TIME_FORMAT, $this->getTime()) . ' ' . $pmOrAm;
        } else {
            $text = date(DATE_FORMAT, $this->getTime());
        }
        return $text;
    }

    public function setTimeZone()
    {
        return date_default_timezone_set(TIME_ZONE);
    }

    public function getPmOrAm()
    {
        $a           = date('a', $this->getTime());
        $languageKey = 'text_DateTime_' . $a;
        return $this->Language->get($languageKey);
    }

    public function timeToUnix($date)
    {
        return strtotime($date);
    }

}