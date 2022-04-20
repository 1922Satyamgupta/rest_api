<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Application;
use Phalcon\Config;
use Phalcon\Loader;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$config = new Config([]);


require_once "../vendor/autoload.php";
$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Models' => './models/',
    ]
);
$loader->register();
// $application = new Application($container);
$container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));

        return $mongo;
    },
    true
);
$prod = new Api\Models\Robots();
$app = new Micro($container);

$app->get(
    '/api/search/{name}',
    [
        $prod,
        'search'
    ]
);
$app->post(
    '/api/products/add',
    [
        $prod,
        'add'
    ]
);

$app->get(
    '/api/gettoken/{role}',
    [
        $prod,
        'gettoken'
    ]
);
$app->handle(
    $_SERVER["REQUEST_URI"]
);

