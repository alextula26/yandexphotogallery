<?php

class AuthorizationController
{
    public function actionIndex($param)
    {

        $config = new Config();

        if (isset($param) && !isset($_SESSION[$config->getSessionName()])) {
            $yaTokenInfo = Gallery::getYaToken($param, $config->getClientId(), $config->getClientSecret());

            if(isset($yaTokenInfo['access_token'])){
                $yaAuthorization = true;
                $yaUserInfo = json_decode(file_get_contents('https://login.yandex.ru/info?oauth_token=' . $yaTokenInfo['access_token']), true);
                Gallery::addSession($config->getSessionName(), $yaTokenInfo, $yaUserInfo, $yaAuthorization);
            }
        }

        header('Location: /');
        exit;
    }
}