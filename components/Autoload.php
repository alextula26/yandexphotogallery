<?php

function __autoload($class)
{
    $array_paths = ['/models/', '/components/'];

    foreach ($array_paths as $path) {
        $path = ROOT . $path . $class . '.php';
        if (is_file($path)) {
            include_once $path;
        }

    }
}