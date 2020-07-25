<?php
spl_autoload_register(function ($className) {
    if (file_exists($className . '.php')) {
        require_once "{$className}.php";
        return true;
    }
    return false;
});