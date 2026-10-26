<?php

namespace NAIF_CMS\System\Library;

use SessionHandler;

class SessionManager extends SessionHandler
{

    private static $instance;
    private $Encryption;

    private function __construct($Encryption)
    {
        $this->setEncryption($Encryption);
        $this->init();
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = serialize($value);
    }

    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return @unserialize($_SESSION[$name]);
        }
        return false;
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]) ? true : false;
    }

    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    public static function getInstance($Encryption)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($Encryption);
        }
        return self::$instance;
    }

    public function setEncryption($Encryption)
    {
        $this->Encryption = $Encryption;
    }

    public function init()
    {
        if (!file_exists(SESSION_SAVE_PATH)) {
            mkdir(SESSION_SAVE_PATH, 0777);
        }
        if (!file_exists(SESSION_SAVE_PATH . '.htaccess')) {
            $file = fopen(SESSION_SAVE_PATH . '.htaccess', 'w');
            fwrite($file, 'Require all denied');
            fclose($file);
        }
        @ini_set('session.use_cookies', 1);
        @ini_set('session.use_only_cookies', 1);
        @ini_set('session.use_trans_sid', 0);
        @ini_set('session.cookie_samesite', 'Lax');
        @ini_set('session.save_handler', 'files');
        session_name(SESSION_NAME);
        session_save_path(SESSION_SAVE_PATH);
        session_set_cookie_params(0, '/', '.' . $_SERVER['SERVER_NAME'], SESSION_USE_SSL, true);
        session_set_save_handler($this, true);
    }

    public function start()
    {
        if (session_id() === '') {
            if (@session_start()) {
                $this->fingerPrint();
                $this->activity();
                $this->lifeTime();
            }
        }
    }

    public function read($id): string|false
    {
        $data = parent::read($id);
        if (empty($data)) {
            return '';
        }
        if (SESSION_ENCRYPTION) {
            $data = $this->Encryption->decrypt($data);
        }
        return $data;
    }

    public function write($id, $data): bool
    {
        if (SESSION_ENCRYPTION) {
            $data = $this->Encryption->encrypt($data);
        }
        return parent::write($id, $data);
    }

    public function delete()
    {
        session_unset();
        setcookie(SESSION_NAME, '', time() - 3600, '/');
        session_destroy();
    }

    private function lifeTime()
    {
        if (!isset($this->lifeTime)) {
            $this->lifeTime = time();
        } else {
            if ((time() - $this->lifeTime) > SESSION_LIFE_TIME) {
                $this->delete();
            }
        }
    }

    private function activity()
    {
        if (!isset($this->activity)) {
            $this->activity = time();
        } else {
            if ((time() - $this->activity) > SESSION_ACTIVITY_TIME) {
                $this->activity = time();
                session_regenerate_id(true);
                $this->generateFingerPrint();
            }
        }
    }

    private function generateFingerPrint()
    {
        $userAgent         = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $_SERVER['REMOTE_ADDR'];
        $sessionId         = session_id();
        $cipherKey         = md5(random_bytes(32));
        $fingerPrintHash   = hash('SHA256', $userAgent . $sessionId . $cipherKey);
        $this->fingerPrint = $fingerPrintHash;
        $this->cipherKey   = $cipherKey;
    }

    private function isValidFingerPrint()
    {
        $userAgent       = $_SERVER['HTTP_USER_AGENT'];
        $sessionId       = session_id();
        $cipherKey       = $this->cipherKey;
        $fingerPrintHash = hash('SHA256', $userAgent . $sessionId . $cipherKey);
        if (hash_equals($fingerPrintHash, $this->fingerPrint)) {
            return true;
        }
        return false;
    }

    private function fingerPrint()
    {
        if (!isset($this->fingerPrint)) {
            $this->generateFingerPrint();
        } else {
            if (!$this->isValidFingerPrint()) {
                $this->delete();
            }
        }
    }

}