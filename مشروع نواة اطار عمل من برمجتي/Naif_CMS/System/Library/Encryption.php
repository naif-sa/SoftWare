<?php

namespace NAIF_CMS\System\Library;

class Encryption
{

    private static $instance;
    private $iv;
    private $algorithm;
    private $key;

    private function __construct()
    {
        $this->setAlgorithm(ENCRYPTION_ALGORITHM);
        $this->setKey(ENCRYPTION_KEY);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getIv()
    {
        return $this->iv;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setIv($iv)
    {
        $this->iv = $iv;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getIvLength()
    {
        return openssl_cipher_iv_length($this->getAlgorithm());
    }

    public function generateIv()
    {
        $this->setIv(openssl_random_pseudo_bytes($this->getIvLength()));
    }

    public function getIvByData($data)
    {
        $iv = substr($data, 0, $this->getIvLength());
        $this->setIv($iv);
    }

    public function encrypt($data)
    {
		$this->generateIv();
        $data = serialize($data);
        $data = openssl_encrypt($data, $this->getAlgorithm(), $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
        $iv   = $this->getIv();
        $data = base64_encode($iv . $data);
        return $data;
    }

    public function decrypt($data)
    {
        $data = base64_decode($data);
        $this->getIvByData($data);
        $data = substr($data, $this->getIvLength());
        $data = openssl_decrypt($data, $this->getAlgorithm(), $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
        $data = @unserialize($data);
        return $data;
    }

    public function crypt($data)
    {
        return password_hash($data, PASSWORD_ARGON2ID);
    }

    public function verify($data, $hash)
    {
        return password_verify($data, $hash);
    }

}