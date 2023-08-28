<?php
namespace App\Libraries;
/**
 * PHP 的 Trait 是一种代码复用机制，可以在不使用继承关系的情况下，将方法组合在一起，提高代码的复用性。
 */

trait HttpTrait {
    protected $httpMethod = 'GET'; // 默认的请求方法为 GET

    public function setHttpMethod($method) {
        $this->httpMethod = $method;
    }

    public function get($url) {
        $this->setHttpMethod('GET');
        return $this->sendRequest($url);
    }

    public function post($url, $data, $headers = []) {
        $this->setHttpMethod('POST');
        return $this->sendRequest($url, $data, $headers);
    }

    public function put($url, $data) {
        $this->setHttpMethod('PUT');
        return $this->sendRequest($url, $data);
    }

    public function delete($url) {
        $this->setHttpMethod('DELETE');
        return $this->sendRequest($url);
    }

    private function sendRequest($url, $data = null, $headers = []) {
        // 使用 curl 发送 HTTP 请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->httpMethod == 'POST' || $this->httpMethod == 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($this->httpMethod == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
