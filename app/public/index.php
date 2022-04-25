<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Escaper;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Session\Manager;
use App\translate\Locale;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Db\Adapter\MongoDB\Client;
include("../vendor/autoload.php");
$config = new Config([]);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH );
// Register an autoloader
$loader = new Loader();
$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
// $loader->registerNamespaces(
//     [
//         'App\Components' => APP_PATH . '/components',
//         'App\Listners' => APP_PATH . '/Listners',
//         'App\translate' => APP_PATH . '/translate'
//     ]
// );
$loader->register();

$container = new FactoryDefault();
$container->setShared(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();

        return $session;
    }
);
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
// $container->set('locale', (new Locale())->getTranslator());

$application = new Application($container);
//EVENTMANAGER ------------------------------Start---------------------------------------------------------
// $eventsManager = new EventsManager();
// $eventsManager->attach(
//     'NotificationListners',
//     new \App\Listners\NotificationListners()
// );
// $eventsManager->attach(
//     'application:beforeHandleRequest',
//     new \App\Listners\NotificationListners()
// );
// $application->setEventsManager($eventsManager);
// $container->set(
//     'EventsManager',
//     $eventsManager
// );
$container->set(
    'logger',
    function () {
        $adapters1 = new Stream("../storage/log/register.log");
        $adapters2 = new Stream("../storage/log/login.log");
        $logger  = new Logger(
       'messages',
       [
        'register' => $adapters1,
        'login' => $adapters2
    ]
);
 return $logger;
    }
);
$container->set(
    'escaper',
    function () {
        return new Escaper();
    }
);
$container->setShared(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();

        return $session;
    }
);
$container->set(
    'config',
    function () {
        $fileName = '../app/etc/config.php';
        $config = new Config([]);
        $array = new \Phalcon\config\Adapter\Php($fileName);
        $config->merge($array);
        return $config;
    }, 
    true
);
// $container->set(
//     'db',
//     function () {
//         $config = $this->getConfig();
//         return new Mysql(
//             [
//                 'host'     => $config->db->host,
//                 'username' =>  $config->db->username,
//                 'password' =>  $config->db->password,
//                 'dbname'   => $config->db->dbname,
//                 ]
//         );
//         }
// );
// $container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));

        return $mongo;
    },
    true
);
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}