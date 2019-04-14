<?php

class Db
{

    public static function getConnection(){
        $config = new Config();
        $dns = 'mysql:host=' . $config->data['db']['host'] . '; dbname=' . $config->data['db']['dbname'];
        $login = $config->data['db']['user'];
        $password = $config->data['db']['password'];
        $dbh = new \PDO($dns, $login, $password);
        $dbh->exec("set names utf8");

        return $dbh;
    }

}