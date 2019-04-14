<?php

abstract class Controller
{
    public $config;

    public function __construct()
    {
        $this->config = new Config();
    }
}