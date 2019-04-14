<?php

class Config
{
    public $data;

    public function __construct()
    {
        return $this->data = include ROOT . '/config/config.php';
    }

    public function getSessionName(){
        return $this->data['ya_config']['session_name'];
    }

    public function getClientId(){
        return $this->data['ya_config']['client_id'];
    }

    public function getClientSecret(){
        return $this->data['ya_config']['client_secret'];
    }

    public function getAuthorization(){
        return $this->data['ya_config']['authorization'];
    }

    public function getType(){
        return $this->data['ya_config']['type'];
    }

    public function getSort(){
        return $this->data['ya_config']['sort'];
    }

    public function getLimit(){
        return $this->data['ya_config']['limit'];
    }



}