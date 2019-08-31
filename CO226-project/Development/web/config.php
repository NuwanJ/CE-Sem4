<?php
/**
 * Created by PhpStorm.
 * User: Mohamed Irfan
 * Date: 1/01/01
 * Time: 10:35 PM
 */


$env = 'irfan';

if (dirname(__FILE__) == '/home/irfan/Desktop/co226/web') {
    $env = 'irfan';
} else if (dirname(__FILE__) == 'add your web path') {
    $env = 'nuwan';
} else if (dirname(__FILE__) == 'add your web path') {
    $env = 'wishma';
} else {
    $env = 'production';
}

switch ($env) {

    case 'irfan':

        define('DB_USER', 'root');
        define('DB_PASSWORD', 'password');
        define('DB_NAME', 'co226');
        define('DB_HOST', 'localhost');
        define('SITE_URL', 'http://localhost/co226/web/');
        break;

    case 'nuwan':

        define('DB_USER', '');
        define('DB_PASSWORD', '');
        define('DB_NAME', '');
        define('DB_HOST', '');
        define('SITE_URL', '');
        break;

    case 'wishma':

        define('DB_USER', '');
        define('DB_PASSWORD', '');
        define('DB_NAME', '');
        define('DB_HOST', '');
        define('SITE_URL', '');
        break;


    case 'production':


	define('DB_USER', '');
        define('DB_PASSWORD', '');
        define('DB_NAME', '');
        define('DB_HOST', '');
        define('SITE_URL', '');
                break;
}

