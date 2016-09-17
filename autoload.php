<?php

spl_autoload_register(function ($className){
    $className = explode('\\', $className);
    $className = $className[sizeof($className)-1];
    $file = __DIR__. '/' . $className . '.php';
    if(is_file($file)){
        require_once $file;
    }
});