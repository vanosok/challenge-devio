<?php
require_once 'config.php';

if (DEBUG_PHP) {
    ini_set('display_errors', true);
    ini_set('display_startup_erros', true);
    error_reporting(E_ALL);
}

spl_autoload_register('loadClass');

function loadClass($nameClass)
{
    if (file_exists('./class/' . $nameClass . '.php')) {
        require_once './class/' . $nameClass . '.php';
    }
}

require_once 'functions.php';