<?php

namespace AppBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiInterface extends Controller
{

    private $apiToken;
    private $api_key;
    private $check_api_key;

    public function __construct()
    {
        global $kernel;
        $this->api_key = $kernel->getContainer()->getParameter('api_key');
        $this->check_api_key = $kernel->getContainer()->getParameter('check_api_key');
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function checkApiKey($key)
    {
        if (array_key_exists($key, $this->api_key)) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $get_ip = explode(";", $this->api_key[$key][0]);
        }

        if ($this->check_api_key == true) {
            if (array_key_exists($key, $this->api_key) && in_array($ip, $get_ip)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    public function apiKeyError()
    {

        return array("status"=>"error", "API_KEY"=>"invalid or null", "IP"=>$_SERVER['REMOTE_ADDR']);

    }

    public static function encryptUrlValue ($url, $key = "ksdkjhIUy&^%&^TDhjewhge2y3guy%^%$")
    {

        $method = 'AES-256-CBC';
        $ivSize = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encrypted = openssl_encrypt($url, $method, $key, OPENSSL_RAW_DATA, $iv);
        $encrypted = trim(strtr(base64_encode($iv.$encrypted), '+/', '-_'), '=');

        return $encrypted;
    }


    public static function decryptUrlValue ($url, $key = "ksdkjhIUy&^%&^TDhjewhge2y3guy%^%$")
    {

        $method = 'AES-256-CBC';
        $url = base64_decode(str_pad(strtr($url, '-_', '+/'), strlen($url) % 4, '=', STR_PAD_RIGHT));
        $ivSize = openssl_cipher_iv_length($method);
        $iv = substr($url, 0, $ivSize);
        $url = openssl_decrypt(substr($url, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

        return $url;
    }

}
