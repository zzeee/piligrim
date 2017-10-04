<?php

require '../vendor/autoload.php';
require_once "classes/db.php";
require_once "classes/main.php";
require_once "classes/saints.php";
require_once "classes/monastery.php";
require_once "classes/pointsList.php";
require_once "classes/redirector.php";
require_once "classes/auth.php";
require_once "classes/tours.php";
require_once "classes/users.php";
require_once "classes/orders.php";
require_once "classes/turspace.php";
require_once "classes/products.php";
require_once "classes/configurator.php";
require_once "classes/oneOrder.php";
require_once "classes/price.php";


use Suprematik;
use \Interop\Container\ContainerInterface as ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
//echo(__NAMESPACE__);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//die();


$app = new \Slim\App();
session_start();

$c = $app->getContainer();
$c['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something !!!went wrong!, error:'.$error->getMessage());
    };
};


$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../templates', ['cache' => false]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.html', '', $container['request']->getUri()->getBasePath()), '/');
    //  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    // print_r(get_declared_classes());
    return $view;
};

$cmain=$app->getContainer();
$cmain['main']=function ($cmain) {
    $main = new main();
    return $main;
};

$app->add(new redirector($app->getContainer()));
$app->add(new \RKA\SessionMiddleware(['name' => 'MySessionName']));
$class_methods = get_class_methods("redirector");
foreach ($class_methods as $method_name) {
    if (strpos($method_name, "___")) {
        $cname = substr($method_name, 5);
        $rname = '\redirector:do___' . $cname;
        $pname = "/" . $cname;
        $app->get($pname, $rname);
        $app->get($pname."/", $rname);
        //echo($pname."->".$rname."<br />");

    }
    if (strpos($method_name, "__one__")) {
        $cname = substr($method_name, 9);
        $rname = '\redirector:do__one__' . $cname;
        $pname = "/" . $cname . "/{name}";
        $app->get($pname, $rname);
        //echo($pname."->".$rname."<br />");
    }
    if (strpos($method_name, "__postsrv__")) {
        $cname = substr($method_name, 13);
        $rname = '\redirector:do__postsrv__' . $cname;
        $pname = "/" . $cname;
        $app->post($pname, $rname);
    }
}
$app->get("/", '\redirector:do_main');

$app->get("/dev/Login", '\redirector:do_vklogin');
$app->get("/okvklogin", '\redirector:do_vklogin_ok');

//$app->get('/', function ($request, $response, $args) {});




$app->run();
