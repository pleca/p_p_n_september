<?php

namespace AppBundle\Utils;

class RestApiInterface
{
    private $http_response;
    private $http_code;
    private $http_header_resp;
    private $url;
    private $post_fields;
    private $api_key;
    private $post_flag;
    private $file;
    private $temp_url;
    private $json_flag;
    private $headers;

    public function rawApiConnector()
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);

        if ($this->getFile()) {
            set_time_limit(0);
            $location = '/var/www/html/panel_klienta/temp' . '/localfile.tmp';
            $filep = fopen($location, 'w+');
            $ch = curl_init(str_replace(" ","%20",$this->getUrl()));
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_FILE, $filep);
            $this->setTempFileurl($location);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->getPostFlag() == 1) {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        else {
            curl_setopt($ch, CURLOPT_POST, false);
          }

        if ($this->getPostFields()) {
            $postData = $this->getPostFields();
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        if ($this->json_flag == 1) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            $addHeaders = array(
                'Content-Type:application/json',
                'Accept:application/json',
                'x-api-key: '.$this->getApiKey()
            );
        }
        else {
            $addHeaders = array(
                'x-api-key: '.$this->getApiKey()
            );
        }

        if ($this->headers) {
            $headers = array_merge($this->headers, $addHeaders);
        }
        else {
            $headers = $addHeaders;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $this->http_response = curl_exec ($ch);
        $this->http_header_resp = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close ($ch);

        if ($this->getFile()) {
            fclose($filep);
        }
    }

    public function setPostFields($setPostFields)
    {
        $this->post_fields = $setPostFields;
        return $this;
    }

    public function getPostFields()
    {
        return $this->post_fields;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getHttpResponse()
    {
        return $this->http_response;
    }

    public function getHttpCode()
    {
        return $this->http_code;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function getHeaderResp()
    {
        return $this->http_header_resp;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getPostFlag()
    {
        return $this->post_flag;
    }

    public function setPostFlag($post_flag)
    {
        $this->post_flag = $post_flag;

        return $this;
    }

    public static function getApiToken($data)
    {
        return sha1($data."");
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getTempFileurl()
    {
        return $this->temp_url;
    }

    public function setTempFileurl($temp_url)
    {
        $this->temp_url = $temp_url;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setJsonFlag($json_flag)
    {
        $this->json_flag = $json_flag;
        return $this;
    }

    public function getJsonFlag()
    {
        return $this->json_flag;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }
}
