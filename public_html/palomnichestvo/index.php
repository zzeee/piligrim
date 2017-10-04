<?php
//echo ($_GET["successpay"]);
if (isset($_GET["successpay"]) || isset($_GET["failpay"]) )
{
    if (strpos($_SERVER["SERVER_NAME"], "dev4.elitsy") !== false || strpos($_SERVER["SERVER_NAME"], "elitsy.pozamerkam") !== false)
        $urlline = "http://elitsy.pozamerkam.ru/palomnichestvo/users/".$_GET["customerNumber"];
    else $urlline="https://elitsy.ru/palomnichestvo/".$_GET["customerNumber"];

    header("HTTP/1.1 301 Moved Permanently");
    if (isset($_GET["succespay"]))      header("Location: $urlline");
    else      header("Location: $urlline");
    exit();
}



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
require_once "classes/order.php";
require_once "classes/turspace.php";
require_once "classes/products.php";
require_once "classes/configurator.php";
require_once "classes/oneOrder.php";
require_once "classes/price.php";
require_once "classes/seo.php";
require_once "classes/MailChimp.php";
require_once "classes/Photo.php";
require_once "classes/databuilder.php";
require_once "classes/YaMoneyCommonHttpProtocol.php";
require_once "classes/kassaAPIController.php";
//require_once "mpdf-development/mpdf.php";


use \Interop\Container\ContainerInterface as ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//echo(__NAMESPACE__);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];


$app = new \Slim\App($configuration);
session_start();



$c = $app->getContainer();
$c['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something !!!went wrong!' . $error->getMessage());
    };
};

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../templates', ['cache' => false]);
    $view->addExtension(new Twig_Extension_StringLoader()); 
    $basePath = rtrim(str_ireplace('index.html', '', $container['request']->getUri()->getBasePath()), '/');
    return $view;
};


$cmain = $app->getContainer();
$cmain['main'] = function ($cmain) {
    $main = new main();
    return $main;
};

$app->add(new \RKA\SessionMiddleware(['name' => 'el_session']));

//$rt=new kassaAPIController(2);
$app->add(new redirector($app->getContainer()));
//$app->add(new kassaAPIController($app->getContainer()));
$class_methods = get_class_methods("redirector");
foreach ($class_methods as $method_name) {
    if (strpos($method_name, "___")) {
        $cname = substr($method_name, 5);
        $rname = '\redirector:do___' . $cname;
        $pname = "/" . $cname;
        $app->get($pname, $rname);
        $app->get($pname . "/", $rname);
    }
    if (strpos($method_name, "__one__")) {
        $cname = substr($method_name, 9);
        $rname = '\redirector:do__one__' . $cname;
        $pname = "/" . $cname . "/{name}[/v/{v1}]";
        $app->get($pname, $rname);
    }

    if (strpos($method_name, "__onelist__")) {
        $cname = substr($method_name, 13);
        $rname = '\redirector:do__onelist__' . $cname;
        $pname = "/" . $cname . "/{idd}/{name}[/{param1}]";
        $app->get($pname, $rname);
    }

    if (strpos($method_name, "__postsrv__")) {
        $cname = substr($method_name, 13);
        $rname = '\redirector:do__postsrv__' . $cname;
        //echo($rname." ".$cname."<br />");
        $pname = "/" . $cname;
        $app->post($pname, $rname);

    }
  }

$app->get("/", '\redirector:do_main');
$app->options("/dev/{name}/{password}[/{eid}]",'\redirector:dologon');
$app->get("/dev/{name}/{password}[/{eid}]",'\redirector:dologon');
$app->get("/backdata/{action}[/{v1}[/{v2}[/{v3}]]]",'\redirector:backdata');
$app->post("/backpost/{action}[/{v1}[/{v2}[/{v3}]]]",'\redirector:backpost');
//$app->get("/backpost/{action}[/{v1}[/{v2}[/{v4}]]]",'\redirector:backpost');
$app->get("/users/{v1}[/{v2}[/{v3}[/{v4}]]]",'\redirector:did_users');
$app->get("/dev/Login", '\redirector:do_vklogin');
$app->get("/okvklogin", '\redirector:do_vklogin_ok');


$app->get('/api/tourdescr[/{param1}]', '\redirector:get_api_tourdescr');
$app->get("/api/datainfo[/{id}]", '\redirector:apidatainfo');
$app->post("/api/reservetour/{agencyid}/{turdate}", '\redirector: apireservetour');

$app->get("/api/placeinfo[/{id}]", '\redirector:apiplaceinfo');
$app->get("/api/userbill[/{id}]", '\redirector:userbills');

$app->get("/api/gettourlocations/{tourid}", '\redirector:get_api_gettourlocations');
$app->get("/api/deletetourlocations/{tourid}/{placeid}", '\redirector:get_api_deletetourlocations');
$app->get("/api/addtourlocations/{tourid}/{placeid}", '\redirector:get_api_addtourlocations');


$app->get("/api/gettourdate/{tourid}", '\redirector:get_api_gettourdates');
$app->get("/api/gettourservices/{tourid}", '\redirector:get_api_gettourservices');

$app->get("/api/getpointaddinfo/{param1}", '\redirector:get_api_getpointaddinfo');
$app->get("/api/delpointaddinfo/{param1}", '\redirector:get_api_delpointaddinfo');
$app->post("/api/updatepointaddinfo", '\redirector:post_api_updatepointaddinfo');
$app->get("/api/addpointaddinfo/{param1}", '\redirector:get_api_addpointaddinfo');





$app->get("/api/getphoto/{photoid}", '\redirector:get_api_getphoto');
$app->get("/api/gettourphoto/{param1}", '\redirector:get_api_gettourphoto');
$app->get("/api/getplacephoto/{param1}", '\redirector:get_api_getplacephoto');

$app->get("/api/loadphoto/{param1}/{param2}", '\redirector:get_api_loadphoto');

$app->get("/api/gettourorganizers", '\redirector:get_api_gettourorganizators');

$app->post("/api/photouploader", '\redirector:post_api_photouploader');

$app->post("/api/updateplacephoto", '\redirector:post_api_updateplacephoto');

$app->post("/api/updatephoto/{photoid}", '\redirector:post_api_updatephoto');

$app->get("/api/test_yaCheckOrder", \kassaAPIController::class.':test_checkOrder');
$app->get("/api/test_paymentAviso", \kassaAPIController::class.':test_paymentAviso');
$app->post("/api/yaCheckOrder", \kassaAPIController::class.':checkOrder');
$app->post("/api/paymentAviso", \kassaAPIController::class.':paymentAviso');

$app->post("/api/test_yaCheckOrder", \kassaAPIController::class.':test_checkOrder');
$app->post("/api/test_paymentAviso", \kassaAPIController::class.':test_paymentAviso');



$app->get("/api/deltourdate/{param1}", '\redirector:get_api_deltourdates');
$app->get("/api/deltourphoto/{param1}", '\redirector:get_api_deltourphoto');
$app->post("/api/addtourdate", '\redirector:post_api_addtourdate');
$app->post("/api/updatetourdate",'\redirector:post_api_updatetourdate');

$app->post("/api/addtourservice", '\redirector:post_api_addtourservice');
$app->post("/api/updatetourservices",'\redirector:post_api_updatetourservice');
$app->post("/api/savephotocomment",'\redirector:post_api_savephotocomment');


$app->get("/api/deltourservice/{param1}", '\redirector:get_api_deltourservice');
$app->get("/api/depublishtour/{param1}", '\redirector:get_api_depublishtour');
$app->get("/api/publishtour/{param1}", '\redirector:get_api_publishtour');
$app->get("/api/deletetour/{param1}", '\redirector:get_api_deletetour');
$app->get("/api/gettourorganizators", '\redirector:get_api_deletetour');



$app->post("/api/apiupdatepointdata",\redirector::class.':apiupdatepointdata');
$app->post("/api/updatetourdata",'\redirector:post_api_updatetourdata');



$app->post("/api/addnewpoint",'\redirector:post_api_addnewpoint');
$app->post("/api/addnewtour",'\redirector:post_api_addnewtour');
$app->get("/api/delpoint/{id}",'\redirector:get_api_delpoint');
$app->get("/api/publishpoint/{id}",'\redirector:get_api_publishpoint');
$app->get("/api/depublish/{id}",'\redirector:get_api_depublish');
$app->get("/api/sendcoupon/{param1}",\redirector::class.':get_api_sendcoupon');
$app->get("/api/sendbill/{param1}",\redirector::class.':get_api_sendbill');


$app->post("/api/uploadpointpic",'\redirector:post_uploadpointpic');
$app->post("/api/changereservestatus",'\redirector:post_api_changereservestatus');



$app->get("/api/showreserves[/{id}]",'\redirector:get_api_showreserves');


$app->get("/api/search/{line}", '\redirector:apisearchf');


$app->get("/sitemap.xml", '\redirector:do_sitemap_xml');
//$app->post("/testres",'\redirector:doshowpost');

//$app->get("/{name}/{password}[/{eid}]", '\redirector:do_logon');
//$app->get("/tours/{name}/{idd}", '\redirector:test_test');
//$app->get('/', function ($request, $response, $args) {});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', "*")
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
//$_SESSION["userid"]=98;


$app->run();

if (isset($_GET["debug"]))  echo("SESSION:<br/>".main::getUserId());
