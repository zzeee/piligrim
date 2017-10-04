<?php
/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 02.12.2016
 * Time: 11:29
 */
/*
 * Обработчики:
 * saints - святые
 * points - географические точки (город, деревня итп)
 * sp - святые места (монастырь, храм, колокольня, часовня, источник)
 * mm  - монумент, музей итп
 * /users - ЗАКРЫТО(!)
 * user/232 - личный кабинет
 * /orders - список заказов пользователя
 * /orders/1 - конкретный заказ
 * /tours - поиск туров
 * /tours/1 - просмотр конкретного тура
 * /products - список товаров и опций
 * /products/1 - конкретный товар (опция)
 */


class redirector
{
  private $container;
  var $siteid = 1;

  private $appid = "5775015";
  private $skey = "mntK3M8n1KbTAuiTQu9S";
  private $seo;

  function getPrefix()
  {
    switch ($this->siteid):
      case 1:
        return "nov_";
      case 2:
        return "el_";
    endswitch;
    return "";
  }


  function getTName($url)
  {
    $res = "";
    switch ($url):
      case "/":
        $res = "index.html";
        break;
      case "/users":
        $res = "users.html";
    endswitch;

    return $this->getPrefix() . $res;
  }

  function __construct($container)
  {
    $srvname = $_SERVER['SERVER_NAME'];
    $p1 = strpos($srvname, "elitsy");
    $p2 = strpos($srvname, "molrus.tmweb");
    if ($p1 !== false) $this->siteid = 2;
    if ($p2 !== false) $this->siteid = 1;
    $this->seo = main::getSeo($this->siteid);
    $this->orderFactory = main::orderFactory();
    $this->container = $container;
  }


  function authOpenAPIMember()
  {
    $session = array();
    $member = FALSE;
    $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
    if (isset($_COOKIE['vk_app_' . $this->appid])) {
      $app_cookie = $_COOKIE['vk_app_' . $this->appid];
      if ($app_cookie) {
        $session_data = explode('&', $app_cookie, 10);
        foreach ($session_data as $pair) {
          list($key, $value) = explode('=', $pair, 2);
          if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
            continue;
          }
          $session[$key] = $value;
        }
        foreach ($valid_keys as $key) {
          if (!isset($session[$key])) return $member;
        }
        ksort($session);

        $sign = '';
        foreach ($session as $key => $value) {
          if ($key != 'sig') {
            $sign .= ($key . '=' . $value);
          }
        }
        $sign .= $this->skey;
        $sign = md5($sign);
        if ($session['sig'] == $sign && $session['expire'] > time()) {
          $member = array(
            'id' => intval($session['mid']),
            'secret' => $session['secret'],
            'sid' => $session['sid']
          );
        }
      }
    }
    return $member;
  }

  public function __invoke($request, $response, $next)
  {
    $session = new \RKA\Session();

    $userid = $session->get("userid", 'no');
    $thash = md5($userid . $_SERVER['REMOTE_ADDR']);
    $rhash = $session->get("hashe", 'no');
    $equ = 0;

    ///echo("dcd");
    if (isset($_COOKIE['userid']) && (isset($_COOKIE['hashe']))) {
      $cuserid = $_COOKIE['userid'];
      $chashe = $_COOKIE['hashe'];
      $cthash = md5($cuserid . $_SERVER['REMOTE_ADDR']);
      if ($chashe == $cthash) $equ = 1;
    }

    $member = $this->authOpenAPIMember();
    if ($member !== FALSE) {
      //echo('авторизован');
      /* Пользователь авторизирован в Open API */
    } else {
      //echo('не авторизован');
      /* Пользователь не авторизирован в Open API */
    }
    if ($thash == $rhash || $equ == 1) {
      //   echo("мы вас узнали! $equ <a href='/logout'>выход</a>");
      main::setUserId($userid);
    }
    $response = $next($request, $response);
    return $response;
  }

  public function dologon($request, $response, $args)
  {
    // $session = new \RKA\Session();


    $req["name"] = $request->getAttribute('name');
    $req["password"] = $request->getAttribute('password');
    $session = new \RKA\Session();

    $euser = $request->getAttribute('eid');
    if ($euser) $req["eid"] = $euser;
    $users = main::getUsers();
    $noauth["auth"] = "no";
    $res = $users->checkAuth($req["name"], $req["password"], $euser);
    if ($res) {
      main::setUserId($res["id"]);
      echo(json_encode($res));
    } else echo(json_encode($noauth));
  }


  public function did_users($request, $response, $args)
  {
    $idd = $request->getAttribute('v1');
    $pth = substr_replace($request->getRequestTarget(), "", strpos($request->getRequestTarget(), "/" . $idd));
    $main = $this->container->get("main");
    $view = $this->container->get("view");
    $users = $main->getUsers();
    $lin = [];
    $rt = main::getUserId();
    $params = array('seo' => $this->seo->getTexts($pth), 'arr' => $lin, 'mainmenu' => main::getTopMenu());
    $view->render($response, $this->getPrefix() . 'user.html', $params);
  }


  public function do__one__tours($request, $response, $args)
  {
    //Страница списка - непонятно чего... туров КАТЕГОРИИ. Например /tours/palomnichestvo
//echo('2222');
//die();
    $idd = $request->getAttribute('name');
    $idq = $request->getAttribute('idd');
    $cd = $this->container->get("main");
    $qr = $cd->getTour();
    //$qcat=$qr->getCategories();
    $cid = $qr->getCategoryByUrl($idd);
    //echo ($cid."gyugy ".$idd);
    if ($cid === false) {
      echo('не найдено!');
      $response->withStatus(404)
        ->withHeader('Content-Type', 'text/html')
        ->write('Page not found');
      die();
    }
    $idname = $qr->getCategoryNameById($cid);
    $resarr = $qr->showList($cid);//
    $qrr = new toptours($cid);
    $toplist = $qrr->getList($cid);//надо заменить на выборку по текущей категории
    $params = array('testdata' => "Тестовые данные", 'cid' => $cid, 'idd' => $idd, 'idname' => $idname, 'arr' => $resarr, 'toplist' => $toplist, 'mainmenu' => main::getTopMenu());

    $view = $this->container->get("view");
    $view->render($response, $this->getPrefix() . 'tourlist.html', $params);
  }




  public function get_api_gettourorganizators($request, $response, $args)
  {
      return json_encode(main::getOrganizators());

  }


    public function do__onelist__tours($request, $response, $args)
  { //страница ТУРА(!)
    $idd = $request->getAttribute('name');
    $idq = $request->getAttribute('idd');
    $param1 = $request->getAttribute('param1');
    //echo($param1);

      //    echo("$idd $idq\n<br/>");

    $cd = $this->container->get("main");
    $rt = $cd->getTour();
    $resarr = $rt->showOne($idd);
    if (!isset($resarr["turdata"]) || count($resarr["turdata"]) == 0) {
      $response->withStatus(302)->withHeader('Location', 'https://www.elitsy.ru/palomnichestvo');
      die();
    } else {
      $tprice = new price(main::getUserId());
      $qprice = $tprice->getTourPrice($idd, $param1);
      $lprice = $tprice->getTourLenPrice($idd, $param1);
      $params = array('arr' => $resarr, 'price' => $qprice, 'tprice' => $lprice, 'mainmenu' => main::getTopMenu());


      $view = $this->container->get("view");
      //echo(json_encode($resarr));
      //var_dump(array_keys($resarr));
      //var_dump(array_keys($resarr["turdata"]));

      //var_dump($resarr[2]);
      //var_dump($resarr[3]);
      // var_dump($params);
      $view->render($response, $this->getPrefix() . 'tour.twig', $params);
    }
  }


  public function do___tours($request, $response, $args)
  {//Страница ВСЕ ТУРЫ /tours
    // echo('2232');
    $cd = $this->container->get("main");
    $qr = $cd->getTour();
    $resarr = $qr->showList();

    $qrr = new tours();
    $toplist = $qrr->getList(1);
    //var_dump($toplist);

    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr, 'toplist' => $toplist, 'mainmenu' => main::getTopMenu());

    //var_dump($params);
    $view = $this->container->get("view");
    $view->render($response, $this->getPrefix() . 'tourlist.html', $params);
  }


  /* SP - монастыри  храмы*/
  public function do___sp($request, $response, $args)
  {
    $cd = $this->container->get("main");
    $qr = $cd->getMonastery();
    $visibility=1;
    if (isset($_GET["nonvisible"])) $visibility=0;
    $resarr = $qr->showList(0,$visibility);

    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr, 'mainmenu' => main::getTopMenu());
    $view = $this->container->get("view");
    $view->render($response, $this->getPrefix() . 'monasteries.html', $params);
  }

  public function do__one__razm($request, $response, $args)
  {
    $cd = $this->container->get("main");
    $idd = $request->getAttribute('name');
    if (!intval($idd)) {
      $idd = main::getPlaceIdByTitle($idd);
    }
    $rt = main::getPoints();

    $resarr = $rt->getHotelServices($idd);

    $params = array('testdata' => "Тестовые данные",
      'arr' => $resarr,
      'hotel' => $rt->getHotelById($idd),
      'mainmenu' => main::getTopMenu());
    $view = $this->container->get("view");
    $view->render($response, $this->getPrefix() . 'hotel.html', $params);
  }


  public function do__one__sp($request, $response, $args)
  {
    $cd = $this->container->get("main");
    $idd = $request->getAttribute('name');


    if (!intval($idd)) {
      $idd = main::getPlaceIdByTitle($idd);
    }
    $rt = $cd->getMonastery();
    $pl = $cd->getPoints();
    $resarr = $rt->showOne($idd);
    $citydata = "";
    if (isset($resarr) && isset($resarr["data"][0]) && isset($resarr["data"][0]["cityid"])) $citydata = $pl->getOne($resarr["data"][0]["cityid"]);
    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr,
      'citydata' => $citydata,
      'month' => main::monthsArr(),
      'mainmenu' => main::getTopMenu());
      $view = $this->container->get("view");
    $view->render($response, $this->getPrefix() . 'monastery.html', $params);
  }


  public function get_api_deletetourlocations($request, $response, $args)
  {
      $id = intVal($request->getAttribute('tourid'));
      $place = intVal($request->getAttribute('placeid'));
      $qt = new tours();
      $gt=$qt->deleteTourLocations($id, $place);
      return json_encode($gt);
  }


  public function get_api_addtourlocations($request, $response, $args)
  {
      $id = intVal($request->getAttribute('tourid'));
      $place = intVal($request->getAttribute('placeid'));
      main::logVar($id." ++".$place);
      $qt = new tours();
      $gt=$qt->addTourLocations($id, $place);
      return json_encode($gt);
  }

  public function get_api_getpointaddinfo($request, $response, $args)
  {
      $placeid = intVal($request->getAttribute('param1'));
      //$place = intVal($request->getAttribute('placeid'));
      //main::logVar($id." ++".$place);
      $qt=new pointsList();

      $gt=$qt->getPointAddInfo($placeid);
      return json_encode($gt);
  }

public function get_api_delpointaddinfo($request, $response, $args)
  {
      $param1 = intVal($request->getAttribute('param1'));
      //$place = intVal($request->getAttribute('placeid'));
      //main::logVar($id." ++".$place);
      $qt=new pointsList();

      $gt=$qt->delPointAddInfo($param1);
      return json_encode($gt);
  }

public function post_api_updatepointaddinfo($request, $response, $args)
  {
      $paramss=main::readInJSON();
      $params=$paramss[0];
      //echo(json_encode($params)."\n".$params[0]["title"]);

      //$place = intVal($request->getAttribute('placeid'));
      //main::logVar($id." ++".$place);
      $qt=new pointsList();
      $gt=$qt->savePointAddInfo($params);
      return json_encode($gt);
  }




  public function get_api_depublishtour($request, $response, $args)
  {
      $id = intVal($request->getAttribute('param1'));
      $qt = new tours();
      $gt=$qt->depublishTour($id);
      return json_encode($gt);
  }
  public function get_api_publishtour($request, $response, $args)
  {
      $id = intVal($request->getAttribute('param1'));
      $qt = new tours();
      $gt=$qt->publishTour($id);
      return json_encode($gt);
  }
  public function get_api_deletetour($request, $response, $args)
  {
      $id = intVal($request->getAttribute('param1'));
      $qt = new tours();
      $gt=$qt->deleteTour($id);
      return json_encode($gt);
  }


  public function get_api_gettourlocations($request, $response, $args)
  {
      $id = intVal($request->getAttribute('tourid'));
      $qt = new tours();
      $gt=$qt->getTourLocations($id);
      return json_encode($gt);
  }

  public function get_api_showreserves($request, $response, $args)
  {
    $id = intVal($request->getAttribute('id'));

    $qt = new order();
    $rt = "";
    if ($id > 0) {
      $rt = $qt->showAdminReservedList($id);
    } else {
      $rt = $qt->showAdminList();
    }
    return json_encode($rt);
  }


  public function post_api_changereservestatus($request, $response, $args){
      $res=main::readInJSON();
      $ord=new order();

      if ($res["itype"]=="RESERVE_STATUS_DELETE") return (json_encode($ord->deleteReserve($res["reserveid"])));
      if ($res["itype"]=="RESERVE_STATUS_CANCELLED") return (json_encode($ord->deleteReserve($res["reserveid"],1)));
      if ($res["itype"]=="RESERVE_STATUS_APPROVED") return (json_encode($ord->changeReserveStatus($res["reserveid"],1)));
      if ($res["itype"]=="PILIGRIM_GO") return (json_encode($ord->changeTripStatus($res["reserveid"],10)));
      if ($res["itype"]=="PILIGRIM_NGO") return (json_encode($ord->changeTripStatus($res["reserveid"],5)));
      if ($res["itype"]=="PILIGRIM_BADGO") return (json_encode($ord->changeTripStatus($res["reserveid"],6)));



      return  json_encode($res);

  }


  public function do___sp_json($request, $response, $args)
  {
    //Оставлен как пример - УБРАТЬ
    $cd = $this->container->get("main");
    $qr = $cd->getMonastery();
    $resarr = $qr->showList();
    $newResponse = $response->withJson($resarr);
    return $newResponse;
  }


  /* города - points*/

  public function do___points($request, $response, $args)
  {
    $main = $this->container->get("main");
    $view = $this->container->get("view");
      $visibility=1;
      if (isset($_GET["nonvisible"])) $visibility=0;


    $qr = $main->getPoints();

      $resarr = $qr->getList();
    $points = $qr->getTopPoint();

    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr, 'points' => $points, 'mainmenu' => main::getTopMenu());
    $view->render($response, $this->getPrefix() . 'points.html', $params);
  }

  public function do__one__points($request, $response, $args)
  {
    $cd = $this->container->get("main");
    $view = $this->container->get("view");
    $idd = $request->getAttribute('name');

    //echo($request->getBasePath());
    if (intval($idd)) //Переадресация на ЧПУ и проверка на существование
    {
      $lin = $request->getUri();
      $lin = substr($lin, 0, strlen($lin) - strlen($idd));
      //$srv="https://elitsy.ru/palomnichestvo/points/";
      $res = main::getPlaceTitleById($idd, 1);
      if ($res != "")
        return $response->withStatus(302)->withHeader('Location', $lin . $res);
      else {
        $view->render($response, main::get404(), array('mainmenu' => main::getTopMenu()));
        return $response->withStatus(404);
      }
    }
    if (!intval($idd)) {
      $idd = main::getPlaceIdByTitle($idd);

      if (!intval($idd)) {
        $view->render($response, main::get404(), array('mainmenu' => main::getTopMenu()));
        return $response->withStatus(404);
      }
    }

    $rt = $cd->getPoints();
    $resarr = $rt->getOne($idd);
    $points = $rt->getTopPoint();


    $params = array('testdata' => "Тестовые данные",
      'arr' => $resarr, 'photos' => $rt->getOnesPhoto($idd), 'tours' => $rt->getToursToPlace($idd),
      'mon' => $rt->getMonasteriesofPlace($idd), 'ist' => $rt->getSaintWater($idd), 'hram' => $rt->getHram($idd),
      'hallow' => $rt->getHallow($idd), 'icon' => $rt->getIcon($idd), 'points' => $points,
      'hist' => $rt->getHist($idd), 'hotel' => $rt->getHotels($idd),
      'month' => main::monthsArr(),
      'mainmenu' => main::getTopMenu());
    // echo($idd);
    //echo(json_encode($rt->getHotels($idd)));
    //var_dump($params);
      //var_dump($rt->getToursToPlace($idd));
    $view->render($response, $this->getPrefix() . 'point.html', $params);
  }

  /* святые - saints*/

  public function do___saints($request, $response, $args)
  {
    $main = $this->container->get("main");
    $view = $this->container->get("view");
    $qr = $main->getSaints();
    $resarr = $qr->getList();

    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr);
    //var_dump($params);
    $view->render($response, $this->getPrefix() . 'saints.html', $params);
  }

  public function do__one__saints($request, $response, $args)
  {
    $cd = $this->container->get("main");
    $view = $this->container->get("view");
    $idd = $request->getAttribute('name');
    $rt = $cd->getSaints();
    $resarr = $rt->getOne($idd);
    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr, 'mainmenu' => main::getTopMenu());
    $view->render($response, $this->getPrefix() . 'saint.html', $params);
  }


  /*
   * Продукты - товары, опции туров, общие для всех туров
   *
   * */

  public function do___products($request, $response, $args)
  {
    $main = $this->container->get("main");
    $view = $this->container->get("view");
    $qr = $main->getProducts();
    $resarr = $qr->getList();

    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr, 'mainmenu' => main::getTopMenu());
    //var_dump($params);
    $view->render($response, 'products.html', $params);
  }

  public function do__one__products($request, $response, $args)
  {
    $cd = $this->container->get("main");
    $view = $this->container->get("view");
    $idd = $request->getAttribute('name');
    $rt = $cd->getProducts();
    $resarr = $rt->getOne($idd);
    $params = array('testdata' => "Тестовые данные", 'arr' => $resarr, 'mainmenu' => main::getTopMenu());
    $view->render($response, 'product.html', $params);
  }


  public function do___orders($request, $response, $args)
  {
    $user = main::getUserId();
    if ($user > 0) {//если авторизован
      $cd = $this->container->get("main");
      $view = $this->container->get("view");
      $qr = $cd->getOrders($user);
      $resarr = $qr->getList();
      // $neworder=$cd->makeNewOrder($user);
      $neworder = $cd->getOneOrder(32);

      $conf['fio'] = 'Петя';
      $conf['phone'] = '3241234132';
      // $neworder->addTourLine(123,3,$conf);

      $conf1['23r432'] = 'ewfwef';
      $params = array('dataarr' => $resarr);
      $view->render($response, $this->getPrefix() . 'orders.html', $params);
    } else {
      echo('<br/> Необходимо авторизоваться: <br />');

    }
  }


  public function do__one__orders($request, $response, $args)
  {
    $idd = $request->getAttribute('name');
    $view = $this->container->get("view");

    $user = main::getUserId();
    if ($user > 0) {
      $cd = $this->container->get("main");
      $rt = $cd->getOrder($user);
      $resarr = $rt->showOne($idd);

      $params = array('dataarr' => $resarr);
      $line = $this->getPrefix() . 'order.html';
      try {
        $view->render($response, $line, $resarr);
      } catch (Exception $e) {
        echo($e->getMessage());
      }
    } else {
      echo("auth needed");
    }
  }

  public function do__postsrv__makeOrder($request, $response, $args)
  {
    $rt = $request->getParsedBody();
    //var_dump($rt);
  }

  public function do__postsrv__login($request, $response, $args)
  {

    $rt = $request->getParsedBody();
    if (is_array($rt) && isset($rt['login']) && isset($rt['pwd'])) {
      $login = $rt['login'];
      $pwd = $rt['pwd'];
      $uid = main::checkAuth($login, $pwd);
      if ($uid > 0) {
        $session = new \RKA\Session();
        $session->set("userid", $uid);
        $hase = md5($uid . $_SERVER['REMOTE_ADDR']);
        $session->set("hashe", $hase);
        if (isset($rt["cookie"]) && $rt["cookie"] == "on") {
          setcookie("userid", $uid);
          setcookie("hashe", $hase);
        }
        echo($uid);
      }
    }

    // echo(session_id());
    // echo('!!!!!-');
    // echo(session_encode());
  }


  public function do___logout($request, $response, $args)
  {
    setcookie("userid", 0);
    setcookie("hashe", 0);
    session_destroy();
  }


  public function do___login($request, $response, $args)
  {
    $main = $this->container->get("main");
    $view = $this->container->get("view");

    $params = array('testdata' => "Тестовые данные");
    //var_dump($params);
    $view->render($response, 'login.html', $params);
  }

  public function do_main($request, $response, $args)
  {
    //var_dump($request);
    //var_dump($args);


    $view = $this->container->get("view");
    $params = databuilder::getQuery($this->siteid, "main");
    $name = $this->getPrefix() . 'index.html';
    return $view->render($response, $name, $params);
  }


  public function do___schedule($request, $response, $args)
  {
    $view = $this->container->get("view");


    $sq = "select dates.id, ct.surl, tourid, tours.title, date, timestampdiff(month, now(),date) as dtm from dates 
join tours on tours.id=dates.tourid 
join categories ct on tours.type=ct.id 

where timestampdiff(month, now(),date)<8 and tours.visible=1 and tours.type in (1,2) order by tourid, dtm, date";

    $month=[];
      $month['1'] = 'январь';
      $month['2'] = 'февраль';
      $month['3'] = 'март';
      $month['4'] = 'апрель';
      $month['5'] = 'май';
      $month['6'] = 'июнь';
      $month['7'] = 'июль';
      $month['8'] = 'август';
      $month['9'] = 'сентябрь';
      $month['10'] = 'октябрь';
      $month['11'] = 'ноябрь';
      $month['12'] = 'декабрь';


      $sq = "select dates.id, ct.surl, tourid, tours.title, realmaxlimit, (realmaxlimit-free.sum) as freespacedate, timestampdiff(month, now(),date) as dtm, date, free.sum, photos.name from dates
  join tours on tours.id=dates.tourid
  join categories ct on tours.type=ct.id
  left join
  (select turdate, count(id) as sum from u_reserves where ifnull(deleted,0)<>1 and turdate<>0  and orderid<>0 group by turdate) as free on  dates.id=free.turdate
  left join (
              select min(sorder),any_value(id) as id,tid as tid, any_value(name) as name from photos where tid>0 group by tid) as photos on photos.tid=tours.id
where timestampdiff(month, now(),date)<8 and tours.visible=1 and tours.type in (1,2) order by tourid, dtm, date";



    $rs = db::query2($sq);
    //echo($sq);
    $tours=new tours();
    $res=$tours->getList(1,100);
    $params = array('title' => 'Расписание', 'month'=>$month,'toplist'=>$res,'arr' => $rs, 'mainmenu' => main::getTopMenu());
    $name = $this->getPrefix() . 'schedule.html';
//      $ch = curl_init("http://sms.ru/sms/send");

//      echo(mail("zzeeee@gmail.com","test1 -заказ","Вам пришел заказ"));
      main::sendMail("zzeeee@gmail.com","test1","тестовое письмо");

      return $view->render($response, $name, $params);
  }


  public function apisearchf($request, $response, $args)
  {
    $idd = $request->getAttribute('line');
    $res = main::siteSearch($idd, 7);
    return json_encode($res);

  }

  public function do___search($request, $response, $args)
  {
    $view = $this->container->get("view");
    $searchline = "";
    if (isset($_GET["searchf"])) $searchline = $_GET["searchf"];

    $rs = main::siteSearch($searchline);   //echo ($searchline);
    $params = array('title' => "Результат поиска фразы '$searchline'", 'arr' => $rs, 'mainmenu' => main::getTopMenu());
    //var_dump($params);
    $name = $this->getPrefix() . 'searchres.html';
    return $view->render($response, $name, $params);
  }

  public function do___addtour($request, $response, $args)
  {
    $view = $this->container->get("view");
    $params = ['mainmenu' => main::getTopMenu()];
    //var_dump($params);
    $name = $this->getPrefix() . 'addtour.html';
    return $view->render($response, $name, $params);
  }

  public function do___about($request, $response, $args)
  {

    $view = $this->container->get("view");
    $params = ['mainmenu' => main::getTopMenu()];
    //var_dump($params);
    $name = $this->getPrefix() . 'about.twig';
    return $view->render($response, $name, $params);
  }


  function showPdfTour($id)
  {
    //echo ($id);
    try {
      $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 1, 1, 1, 1);

      $html = "<table><tr><td>ddd</td></tr>";
      $mpdf->charset_in = 'cp1251';
      $mpdf->list_indent_first_level = 0;
      $mpdf->WriteHTML($html, 2);
      $mpdf->Output('mpdf.pdf', 'I');

    } catch (Exception $e) {
      echo($e->getMessage());
    }


  }

    public function do__one__bill($request, $response, $args)
    {
        $view = $this->container->get("view");

        $id = $request->getAttribute('name');
        $id2 = $request->getAttribute('v1');
        $name = $this->getPrefix() . 'topay.twig'    ;
        $rt = new orders($id);
        $res = $rt->showOne($id2);
        //echo(json_encode($res));
        //$res["main_orderid"]=$id;
        //echo ($id);
        $response = $view->render(  $response, $name, ["res"=>$res,"orderid"=>$id2]);


    }


        public function do__one__printtour($request, $response, $args)
  {
    $view = $this->container->get("view");


    $id = $request->getAttribute('name');
    $id2 = $request->getAttribute('v1');
      ///printtour/$uid/v/$orderid
    exec("/go/bin/qrcode \"https://elitsy.ru/palomnichestvo/printtour/$id/v/$id2\" > /var/www/elitsy/public_html/palomnichestvo/img/order$id2.png");
    $rt = new orders($id);

    $res = $rt->showOne($id2);
    //echo(json_encode($res));
    $name = $this->getPrefix() . 'printticket.html'    ;

    //$res["main_orderid"]=$id;
      //echo ($id);
    $response = $view->render(  $response, $name, ["res"=>$res,"orderid"=>$id2]);

    //$html = substr($html, strpos($html, '<!DOCTYPE html>'));
    //$response = $response->withHeader('Content-type', 'application/pdf');
    //$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 1, 1, 1, 1);

    //$mpdf->charset_in = 'utf-8';
    //$mpdf->list_indent_first_level = 0;
    //$mpdf->WriteHTML($html, 2);
    //$mpdf->Output('mpdf.pdf', 'I');

    //   $response->write( $mpdf->Output('My cool PDF', 'S') );

    //echo("@@@@");

    return $response;
  }


  public function do__postsrv__addtour($request, $response, $args)
  {
    $view = $this->container->get("view");
    $yres = "";
    if (isset($_POST["yphone1"])) $yphone = $_POST["yphone1"];
    if (isset($_POST["yname"])) $yres = $yres . "\n" . $_POST["yname"];
    if (isset($_POST["ycity"])) $yres = $yres . "\n" . $_POST["ycity"];
    if (isset($_POST["typ"])) $yres = "Тип:" . $yres . "\n" . $_POST["typ"];
    if (isset($_POST["t1"])) $yres = " " . $yres . "\n" . $_POST["t1"];
    if (isset($_POST["t2"])) $yres = " " . $yres . "\n" . $_POST["t2"];
    if (isset($_POST["t3"])) $yres = " " . $yres . "\n" . $_POST["t3"];
    $sq = "insert into el_offer(phone, qtext)values('$yphone','$yres')";
    db::query2($sq);
    //echo($yres);
    $arr = [];
    $arr[0] = 'Ваше сообщение было успешно отправлено. По мере рассмотрения заявок с вами свяжется ответственный сотрудник ';
    $arr[1] = $yres;
    $params = array('title' => 'Сообщение отправлено', 'arr' => $arr, 'mainmenu' => main::getTopMenu());

    $name = $this->getPrefix() . 'allok.html';
    return $view->render($response, $name, $params);
  }


  public function doshowpost($request, $response, $args)
  {
    echo($request->getBody());
  }

  public function backdata($request, $response, $args)
  {
    $action = $args["action"];
    $res = "";
    switch ($action):
      case "showtour":
        $tid = $args["v1"];
        $tour = new tours();
        $res = $tour->showOneCorrected($tid, 2);
        break;
      case "showhotel":
        $hid = $args["v1"];
        $rt = main::getPoints();
        $res = $rt->showHotel($hid);
        break;

      case "getuserfullinfo":
        $userid = 0;
        $res = [];
        if (isset($args["v1"])) {
          $userid = $args["v1"];
          $user = new users($userid);
          $res = $user->getUserInfo($userid);

        }
        break;

      case "getuserorders":
        $userid = 0;
        $res = [];
        if (isset($args["v1"])) {
          $userid = $args["v1"];
          $order = new orders($userid);
          $res = $order->getList();
        }
        break;
      case "getorderdetails":
        $userid = $args["v1"];
        $orderid = $args["v2"];
        $order = new orders($userid);
        $res = $order->getOne($orderid);
        break;
      case "userinfo":
        if (isset($_SESSION["userqid"])) {
          $userid = $_SESSION["userqid"];
          $action = $args["v1"];
          if ($action == "show") {
            if ($userid > 0) {
              $usr = new users();
              $res = $usr->getUserById($userid);
            }
          }
        } else {
          $res = "";
        }
        break;
      case "regel":
        $eluser = $args["v1"];
        $usr = new users();
        $res = $usr->regElUser($eluser);

        break;
    endswitch;
    echo(json_encode($res));
  }

  public function backpost($request, $response, $args)
  {
    //$main = $this->container->get("main");
    $action = $args["action"];
    $res = "";
    switch ($action):
      case "addorder":
        $data = (array)json_decode(file_get_contents("php://input"), true);
        $ofr = main::orderFactory();
        $res = $ofr->makeRawOrder($data);
        $ofr->noticer($data, $res);
        //$res = $request->getBody();
        break;

      case "addhotelorder":

        $data = (array)json_decode(file_get_contents("php://input"), true);
        $ofr = main::orderFactory();
        $res = $ofr->makeHotelOrder($data);

        break;
      case "changeudata":
        $data = (array)json_decode(file_get_contents("php://input"), true);
        $ofr = new users();
        $ofr->setRawData($data);
        break;
    endswitch;
    echo(json_encode($res));
  }

  public function get_api_tourdescr($request, $response, $args)
  {
    $idd = $request->getAttribute('param1');
    $tour_dat = new tours();
    if (intval($idd) > 0) $res = $tour_dat->exportOne($idd); else $res = $tour_dat->exportList();
    return json_encode($res);
  }

  public function apidatainfo($request, $response, $args)
  {
    $idd = $request->getAttribute('id');
    $tour_dat = new tours();
    $rt = $tour_dat->exportDates();
    return json_encode($rt);
  }

  public function apireservetour($request, $response, $args)
  {

  }

  public function apiupdatepointdata($request, $response, $args)
  {
      $req = main::readInJSON();

      return (new pointsList())->updatePoint($req);
  }


  public function post_api_updatetourdata($request, $response, $args)
  {
    $req = main::readInJSON();


    $res = (new tours())->updateTour($req);
    //echo $req;
    return $res;
    //return json_encode(["res"=>"ok", "dres"=>$res]);
  }


  public function post_api_addnewpoint($request, $response, $args)
  {
    $req = main::readInJSON();
    $res = (new pointsList())->newPoint($req["name"], $req["type"]);
    return (json_encode(Array("resid" => $res)));
  }
public function post_api_addnewtour($request, $response, $args)
  {
    $req = main::readInJSON();
    $res = (new tours())->addNewTour($req["title"], $req["type"]);
    return (json_encode(Array("resid" => $res)));
  }

  public function get_api_delpoint($request, $response, $args)
  {
    $idd = $request->getAttribute('id');
    $res = (new pointsList())->delPoint($idd);
    return (json_encode(Array("resid" => $res)));
  }

  public function get_api_publishpoint($request, $response, $args)
  {
    $idd = $request->getAttribute('id');
    $res = (new pointsList())->publishpoint($idd);
    return (json_encode(Array("resid" => $res)));
  }

  public function get_api_depublish($request, $response, $args)
  {
    $idd = $request->getAttribute('id');
    $res = (new pointsList())->depublish($idd);
    return (json_encode(Array("resid" => $res)));
  }


  public function post_uploadpointpic($request, $response, $args)
  {
    $req = main::readInJSON();

    try {
      if (isset($req["picid"]) && isset($req["file"])) {
        ;
        $file = main::readInFile($req["file"]);
        $res = main::updatePictureByPicId($req["picid"], $file);
        //        echo('wdw-1'.json_encode($res));
      } else
        if (isset($req["pointid"]) && isset($req["file"])) {
          $file = main::readInFile($req["file"]);
          //$res=json_encode($file);
          $res = main::updatePictureByPlaceId($req["pointid"], $file);
        }
    } catch (Exception $e) {
      $res = json_encode($e->getMessage());
    }
    return (json_encode(Array("resid" => $res)));
//        (new pointsList())->updatePointPic();
  }
public function post_api_photouploader($request, $response, $args)
  {
    $req = main::readInJSON();

    $rt=new Photo();
    $res=$rt->addUpdateBase64Photo($req);
    return (json_encode($res));
//        (new pointsList())->updatePointPic();
  }
public function post_api_savephotocomment($request, $response, $args)
  {
    $req = main::readInJSON();

    $rt=new Photo();
    $res=$rt->savePhotoComment($req);
    return (json_encode($res));
  }


  public function userbills($request, $response, $args)
  {
    $idd = $request->getAttribute('id');
    $usr = main::getUsers();
    $ulist = $usr->getUserBills($idd);
    return json_encode($ulist);

  }

  public function apiplaceinfo($request, $response, $args)
  {
    $pl = new pointsList();
    $res = $pl->exportPoints();
    return json_encode($res);
  }


  public function do_sitemap_xml($request, $response, $args)
  {
    $pl = new pointsList();
    $view = $this->container->get("view");
    $params = array('testdata' => "Тестовые данные", 'arr' => $pl->getPlacesUrlArray());
    return $view->render($response, 'el_sitemapxml.twig', $params);
  }


  public function do_vklogin($request, $response, $args)
  {
    //       echo('23423423423');
    //echo($this->siteid);
    $view = $this->container->get("view");
    //var_dump($request)        ;
//echo($_SERVER["QUERY_STRING"]);
    $uid = $_GET["uid"]; //ПЕРЕДЕЛАТЬ НА SLIM(!!!)
    $hash = $_GET["hash"];
    $appid = "5775015";
    $skey = "mntK3M8n1KbTAuiTQu9S";

    $app_cookie = $_COOKIE["vk_app_$appid"];

    $thash = $appid . $uid . $skey;
    if ($hash == md5($thash)) {
      //Добавить проверку существования юзера с таким uid в базе
      return $response->withStatus(302)->withHeader('Location', '/okvklogin?uid=' . $uid);

    } else             echo('Ошибка авторизации');

  }

  public function get_api_gettourdates($request, $response, $args)
  {
    $idd = intVal($request->getAttribute('tourid'));
    if ($idd<=0) return false;
    $rt=new tours();
    $gt=$rt->getTourDates($idd);
    return json_encode($gt);
  }
  public function get_api_gettourphoto($request, $response, $args)
  {
    $idd = intVal($request->getAttribute('param1'));
    if ($idd<=0) return false;
    $rt=new tours();
    $gt=$rt->getTourPhoto($idd);
    return json_encode($gt);
  }
  public function get_api_getplacephoto($request, $response, $args)
  {

    $idd = intVal($request->getAttribute('param1'));

    if ($idd<=0) return false;
    $rt=new pointsList();
    $gt=$rt->getPointPhotos($idd);
    return json_encode($gt);
  }

  public function get_api_loadphoto($request, $response, $args)
  {
    $type = ($request->getAttribute('param1'));
    $id = ($request->getAttribute('param2'));
    $res="";

    $rt=new Photo();
    $gt=$rt->getTourPhotoName($type, $id);
    if ($gt!="") $res=$rt->getBase64Photo($gt);


    return json_encode(["photo"=>$res]);
  }






public function get_api_gettourservices($request, $response, $args)
  {
    $idd = intVal($request->getAttribute('tourid'));
    if ($idd<=0) return false;
    $rt=new tours();
    $gt=$rt->getAddServicesByTourId($idd);
    return json_encode($gt);
  }


  public function get_api_deltourdates($request, $response, $args)
  {
    $idd = intVal($request->getAttribute('param1'));
    if ($idd<=0) return false;
    $rt=new tours();
    $gt=$rt->delTourDate($idd);
    return json_encode($gt);
  }
  public function get_api_deltourphoto($request, $response, $args)
  {
    $idd = intVal($request->getAttribute('param1'));
    if ($idd<=0) return false;
    $rt=new Photo();
    $gt=$rt->delPhoto($idd);
    return json_encode($gt);
  }


  public function get_api_deltourservice($request, $response, $args)
  {
    $idd = intVal($request->getAttribute('param1'));
    if ($idd<=0) return false;
    $rt=new tours();
    $gt=$rt->delTourService($idd);
    return json_encode($gt);
  }

  public function get_api_sendcoupon($request, $response, $args)
  {
      $idd = intVal($request->getAttribute("param1"));
    //var $idd=0;
      //$gt="TEST";
      $to=new order();
      $res=$to->sendUserNotice($idd);

      return json_encode($res);
  }
public function get_api_sendbill($request, $response, $args)
  {
      $idd = intVal($request->getAttribute("param1"));
    //var $idd=0;
      //$gt="TEST";
      $to=new order();
      $res=$to->sendUserNotice($idd);

      return json_encode($res);
  }

  public function post_api_addtourdate($request, $response, $args)
  {
    $params=main::readInJSON();
    $res=false;
    if ($params)
    {
      $rt=new tours();
      $res=$rt->addTourDate($params);
    }
    return json_encode($res);
  }
  public function post_api_updatetourdate($request, $response, $args)
  {
    $params=main::readInJSON();
    $res=false;
    if ($params)
    {
      $rt=new tours();
      $res=$rt->updateTourDate($params);
    }
    return json_encode($res);
  }
public function post_api_addtourservice($request, $response, $args)
  {
    $params=main::readInJSON();
    $res=false;
    if ($params)
    {
      $rt=new tours();
      $res=$rt->addTourService($params);
    }
    return json_encode($res);
  }
  public function post_api_updatetourservice($request, $response, $args)
  {
    $params=main::readInJSON();
    $res=false;
    if ($params)
    {
      $rt=new tours();
      $res=$rt->updateTourService($params);
    }
    return json_encode($res);
  }






    public function do_vklogin_ok($request, $response, $args)
  {
    $uid = $_GET["uid"]; //ПЕРЕДЕЛАТЬ НА SLIM(!!!)
    echo("Проверено:" . $uid);
  }


}