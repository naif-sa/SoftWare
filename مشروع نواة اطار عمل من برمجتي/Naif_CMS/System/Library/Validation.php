<?php

namespace NAIF_CMS\System\Library;

trait Validation
{

    private $regex  = [
        'float'           => '/^[0-9]+(?:\.[0-9]+)?$/',
        'int'             => '/^[0-9]+$/',
        'alphaEnglish'    => '/^[A-Za-z\s]+$/',
        'alphaArabic'     => '/^[\p{Arabic}\s]+$/u',
        'alphaNumEnglish' => '/^[A-Za-z0-9\s]+$/',
        'alphaNumArabic'  => '/^[\p{Arabic}0-9\s]+$/u',
        'min'             => '/(min)\((.+?)\)/',
        'max'             => '/(max)\((.+?)\)/',
        'eq'              => '/(eq)\((.+?)\)/',
        'between'         => '/(between)\((.+?),(.+?)\)/',
        'floatLike'       => '/(floatLike)\((.+?),(.+?)\)/',
        'alpha'           => '/^[A-Za-z\p{Arabic}\s]+$/u',
        'alphaNum'        => '/^[A-Za-z\p{Arabic}0-9\s]+$/u',
        'date'            => '/^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/'
    ];
    private $errors = [];

    private function req($field)
    {
        return !empty($field) ? true : false;
    }

    private function min($field, $min)
    {
        if (is_numeric($field)) {
            return $field >= $min;
        } else if (is_string($field)) {
            return mb_strlen($field) >= $min;
        }
    }

    private function max($field, $max)
    {
        if (is_numeric($field)) {
            return $field <= $max;
        } else if (is_string($field)) {
            return mb_strlen($field) <= $max;
        }
    }

    private function url($field)
    {
        return filter_var($field, FILTER_VALIDATE_URL);
    }

    private function int($field)
    {
        return (bool) preg_match($this->regex['int'], $field);
    }

    private function float($field)
    {
        return (bool) preg_match($this->regex['float'], $field);
    }

    private function alphaArabic($field)
    {
        return (bool) preg_match($this->regex['alphaArabic'], $field);
    }

    private function alphaEnglish($field)
    {
        return (bool) preg_match($this->regex['alphaEnglish'], $field);
    }

    private function alphaNumArabic($field)
    {
        return (bool) preg_match($this->regex['alphaNumArabic'], $field);
    }

    private function alphaNumEnglish($field)
    {
        return (bool) preg_match($this->regex['alphaNumEnglish'], $field);
    }

    private function alpha($field)
    {
        return (bool) preg_match($this->regex['alpha'], $field);
    }

    private function alphaNum($field)
    {
        return (bool) preg_match($this->regex['alphaNum'], $field);
    }

    private function date($field)
    {
        return (bool) preg_match($this->regex['date'], $field);
    }

    private function between($field, $min, $max)
    {
        if (is_numeric($field)) {
            return $field >= $min && $field <= $max;
        } else if (is_string($field)) {
            return mb_strlen($field) >= $min && mb_strlen($field) <= $max;
        }
    }

    private function floatLike($field, $min, $max)
    {
        if (!$this->float($field)) {
            return false;
        }
        $pattern = '/^[0-9]{' . $min . '}\.[0-9]{' . $max . '}$/';
        return (bool) preg_match($pattern, $field);
    }

    private function email($field)
    {
        return filter_var($field, FILTER_VALIDATE_EMAIL);
    }

    private function eq($field, $eqField)
    {
        return $field === $eqField;
    }

    public function isValid($rules, $inputType = null)
    {
        if (!empty($rules)) {
            foreach ($rules as $field => $fieldRules) {
                if (is_null($inputType)) {
                    $inputType = $_POST;
                }
                @$value         = $inputType[$field];
                $ValidateRules = explode('|', $fieldRules);
                $title         = $this->Language->get('text_label_' . $field);
                foreach ($ValidateRules as $fieldRule) {
                    if (!empty($this->errors) && array_key_exists($field, $this->errors)) {
                        continue;
                    }
                    if (preg_match_all($this->regex['min'], $fieldRule, $m)) {
                        if (!$this->min($value, $m[2][0])) {
                            $this->errors[$field] = $this->Messenger->add($this->Language->feedKey('text_error_Validation_min', [$title, $m[2][0]]), Messenger::TYPE_ERROR);
                        }
                    } else if (preg_match_all($this->regex['max'], $fieldRule, $m)) {
                        if (!$this->max($value, $m[2][0])) {
                            $this->errors[$field] = $this->Messenger->add($this->Language->feedKey('text_error_Validation_max', [$title, $m[2][0]]), Messenger::TYPE_ERROR);
                        }
                    } else if (preg_match_all($this->regex['eq'], $fieldRule, $m)) {
                        if (!$this->eq($value, $inputType[$m[2][0]])) {
                            $this->errors[$field] = $this->Messenger->add($this->Language->feedKey('text_error_Validation_eq', [$title, $this->Language->get('text_label_' . $m[2][0])]), Messenger::TYPE_ERROR);
                        }
                    } else if (preg_match_all($this->regex['between'], $fieldRule, $m)) {
                        if (!$this->between($value, $m[2][0], $m[3][0])) {
                            $this->errors[$field] = $this->Messenger->add($this->Language->feedKey('text_error_Validation_between', [$title, $m[2][0], $m[3][0]]), Messenger::TYPE_ERROR);
                        }
                    } else if (preg_match_all($this->regex['floatLike'], $fieldRule, $m)) {
                        if (!$this->floatLike($value, $m[2][0], $m[3][0])) {
                            $this->errors[$field] = $this->Messenger->add($this->Language->feedKey('text_error_Validation_floatLike', [$title, $m[2][0], $m[3][0]]), Messenger::TYPE_ERROR);
                        }
                    } else {
                        if (!$this->$fieldRule($value)) {
                            $this->errors[$field] = $this->Messenger->add($this->Language->feedKey('text_error_Validation_' . $fieldRule, [$title]), Messenger::TYPE_ERROR);
                        }
                    }
                }
            }
        }
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

}