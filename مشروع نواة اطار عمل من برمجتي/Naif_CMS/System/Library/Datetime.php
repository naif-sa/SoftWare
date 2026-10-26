<?php

namespace NAIF_CMS\System\Library;

class Datetime
{
	
	private static $instance;
    private $time;
    private $Language;

    private function __construct($Language)
    {
        $this->setLanguage($Language);
        $this->setTime(time());
        $this->setTimeZone();
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

    public function formatTime($toDayYesterDay = false, $format = DATE_FORMAT)
    {
        $isPmAm = $this->isPmAm();
        if ($toDayYesterDay) {
            if (date('Y-m-d') === date('Y-m-d', $this->getTime())) {
                $this->setTime($this->Language->get('text_Datetime_toDay') . ' ' . date(TIME_FORMAT, $this->getTime()) . ' ' . $isPmAm);
            } else if (date('Y-m-d', $this->getTime()) === date('Y-m-d', $this->dateToUnix('yesterday'))) {
                $this->setTime($this->Language->get('text_Datetime_yesterDay') . ' ' . date(TIME_FORMAT, $this->getTime()) . ' ' . $isPmAm);
            } else {
                $this->setTime(date($format, $this->getTime()) . ' ' . $isPmAm);
            }
        } else {
            $this->setTime(date($format, $this->getTime()) . ' ' . $isPmAm);
        }
        return $this->getTime();
    }

    public function setTimeZone()
    {
        return date_default_timezone_set(TIME_ZONE);
    }

    public function isPmAm()
    {
        return date('A', $this->getTime()) === 'PM' ? $this->Language->get('text_Datetime_pm') : $this->Language->get('text_Datetime_am');
    }

    public function dateToUnix($date)
    {
        return strtotime($date);
    }

}