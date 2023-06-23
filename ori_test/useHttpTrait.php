<?php
#PHP trait的使用

class Api {
    use HttpTrait;
    public function getUser($id) {
        $url = 'https://api.example.com/users/' . $id;
        $result = $this->get($url);
        return json_decode($result);
    }
}

$api = new Api();
$user = $api->getUser('52');