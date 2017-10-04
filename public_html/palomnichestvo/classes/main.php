<?php

/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 30.11.2016
 * Time: 22:33
 */
class main
{
  static $siteid = 0;
  var $app;
  var $redir;
  static $app2;
  private $container;
  static $users = null;
  static $tours = null;
  static $monastery = null;
  static $orders = null;
  static $points = null;
  static $products = null;
  static $saints = null;
  static $order_details = null;
  static $order_factory = null;
  static $seo = null;
  private static $userid = 0;

  var $main;

  public function __invoke($request, $response, $next)
  {

    // $response->getBody()->write('BEFORE');
    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');

    return $response;
  }


  function __construct()
  {
  }

  static function fabr($fid)
  {


  }


  static function getPlaceIdByTitle($title)
  {
    $sq = "select id from places where tname='$title'";
    $rt = db::query2($sq);
    $res = 0;
    if ($rt) $res = ($rt->fetch(PDO::FETCH_ASSOC))["id"];
    return $res;
  }

  static function getPlaceTitleById($id, $type)
  {
    $sq = "select tname from places where id='$id' and type=$type";
    $rt = db::query2($sq);
    $res = 0;
    if ($rt) $res = ($rt->fetch(PDO::FETCH_ASSOC))["tname"];
    return $res;
  }

  static function get404()
  {
    return 'el_404.html';

  }

  static function checkAuth($user, $pwd)
  {
    $smtp = db::prepare("SELECT id  FROM clients WHERE logemail = :login and pwd=:pwd");
    $res = $smtp->execute(array(':login' => $user, ':pwd' => $pwd));
    if (!$res) return 0;
    $result = $smtp->fetchAll();
    if (count($result) == 0) return 0;
    $rdata = $result[0];
    return $result[0]["id"];
  }

  static function getSiteId()
  {
    return self::$siteid;
  }


  static function getUsers()
  {
    if (self::$users == null) {
      self::$users = new users();
    }

    return self::$users;
  }


  static function getSeo($id)
  {
    if (self::$seo == null) {
      self::$seo = new seo($id);
    }

    return self::$seo;
  }


  static function setUserId($id)
  {
    self::$userid = $id;
    $_SESSION["userqid"] = $id;
    if (isset($_GET["debug"])) echo("<br/>main:$id" . "<br/>sid:" . session_id());
    return $id;

  }

  static function siteSearch($searchline, $limit = 0)
  {
    $sq = "select id, name, main_descr, descr, 1 as type, 1 as var1,concat(pt.surl,'/',tname) as surl from places 
left join places_types pt on pt.type=places.type

where (main_descr like '%$searchline%')  or (descr like '%$searchline%') or (name like '%$searchline%')
union
select tours.id, title as name, main_descr, description as descr,2 as type, 1 as var1, categories.surl as surl from tours  
join categories on tours.type=categories.id
where (main_descr like '%$searchline%') or (description like '%$searchline%') 
union 
select dates.id, tours.title, tours.description, dates.comment as main_descr,3 as type, tours.id as var1, categories.surl as surl from dates 
left join tours on dates.tourid=tours.id 
join categories on tours.type=categories.id
where dates.comment like '%$searchline%'

" . ($limit > 0 ? "limit $limit" : "");
    //   echo($sq);
    $rs = db::query2($sq);
    $res = "";
    if ($rs) $res = $rs->fetchAll();


    return $res;

  }

  static function getTopMenu()
  {
    function getQ($id)
    {
      $sq = "SELECT tours.id, tours.title, tours.baseprice, dates.id AS did, dates.date AS date, categories.surl FROM tourmain
  LEFT JOIN dates ON tourmain.dateid=dates.id
  LEFT JOIN tours ON dates.tourid=tours.id
  LEFT JOIN categories ON tours.type=categories.id
WHERE tourmain.locid IN ($id)";
      $res = db::query2($sq);
      if ($res) $rs = $res->fetchAll();
      return $rs;

    }

    function get10()
    {
      $sq = "select tm.superphotoid,tm.id,tm.picurl, tm.siteid, tm.locid, tm.tourid, tm.sorder, tm.dateid, tm.text1, tm.text2, tm.text3, tm.url, ph.supername as picname from tourmain tm left join photos ph
on tm.superphotoid=ph.id
where tm.locid=10";
      $rs = db::query2($sq);
      $rm = [];
      if ($rs) $rm = $rs->fetch(PDO::FETCH_ASSOC);
      return $rm;
    }

    $res = [];
    $qrr = new tours();
    //$toplist =
    $res["5"] = $qrr->getList(1);//getQ(5);
    $res["6"] = getQ(6);
    $res["7"] = getQ(7);
    $res["10"] = get10();
    $res["variables"] = $_SERVER;
    $srv = $_SERVER["SERVER_NAME"];
//echo ($_SERVER["SERVER_NAME"]);
//echo ($_SERVER["HTTP_USER_AGENT"]);
    if (strpos($_SERVER["SERVER_NAME"], "dev4.elitsy") !== false || strpos($_SERVER["SERVER_NAME"], "elitsy.pozamerkam") !== false)
      $res["type"] = "debug";
    else $res["type"] = "prod";
    
    //echo $res["type"];
    return $res;

    /*
     * if (strpos($_SERVER['SERVER_NAME'], "elitsy") >= 0 && ($_SERVER['REMOTE_ADDR'] != '85.235.189.246')) {
      if (strpos($_SERVER['HTTP_USER_AGENT'], "MI MAX") < 0) die();

     * */

  }

    static function directoryRoot()
    {
        return "/var/www/eltest/public_html/palomnichestvo/";
    }


        static function picDirectoryRoot()
  {
    return "/var/www/elitsy/public_html/palomnichestvo/img/";
  }

  static function getUserId()
  {
    $userid = 0;
    if (isset($_SESSION["userqid"])) {
      $userid = $_SESSION["userqid"];
      if (isset($_GET["debug"])) echo("<br/>main:$userid" . "<br/>sid:" . session_id());
    }

    return $userid;
  }

  function getOrder($uid)
  {
    if (self::$order_details == null) {
      self::$order_details = new orders($uid);
    }
    return self::$order_details;
  }


  function getOrders($uid)
  {
    if (self::$orders == null) {
      self::$orders = new orders($uid);
    }
    return self::$orders;
  }

  function getOneOrder($oid)
  {
    return new oneOrder($oid);
  }

  static function getPoints()
  {
    if (self::$points == null) {
      self::$points = new pointsList();
    }
    return self::$points;
  }

  static function monthsArr()
  {
    $month = [];
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
    return $month;

  }

  function getTour()
  {
    if (self::$tours == null) {
      self::$tours = new tours();
    }
    return self::$tours;
  }

  function getMonastery()
  {
    if (self::$monastery == null) {
      self::$monastery = new monastery();
    }
    return self::$monastery;
  }


  function getSaints()
  {
    if (self::$saints == null) {
      self::$saints = new saints();
    }
    return self::$saints;

  }


  function getProducts()
  {
    if (self::$products == null) {
      self::$products = new products();
    }
    return self::$products;

  }


  public static function orderFactory()
  {
    if (self::$order_factory == null) {
      self::$order_factory = new order();
    }
    return self::$order_factory;
  }


  function makeNewOrder($uid, $dealerid, $req)
  {
    $sq = "insert into orders(uid, dealerid) values($uid, $dealerid)";
    echo($sq);
    $rt = db::query2($sq);
    $rm = db::lastInsertId();
    return new OneOrder($rm);

  }


  static function sendSms($phones, $smstext)
  {
      //$body = curl_exec($ch);
      //$res = substr($body, 4, 14);
      //$sq = "insert into smshistory(phones, smstext, date, status) values('" . $phones . "','" . $smstext . //"',now(),'" . $res . "' )";
//    db::query($sq);
      $resarr=[];
      $resvarr="";
      exec('/usr/bin/php ./cli/send.php $phones "$smstext"', $resarr, $resvarr);
      //exec('/usr/bin/php --version', $resarr, $resvarr);
      //var_dump($resarr);
     // echo("<br />");
     // var_dump($resvarr);
  }

  static function sendMail($email, $subject, $text)
  {
   file_put_contents(self::directoryRoot()."tmpmail", "From: <andrey@nov-rus.ru>\nContent-type:text/html\nSubject:<$subject>   \n"."$text\n");
   $arg1=[];
   //$arg1=shell_exec("./sendm $email");
   $arg1=exec(self::directoryRoot()."sendm $email ", $arg1);
   //var_dump($arg1);


  }


  static function logVar($str)
  {
      $resstr=date("j.m.y H:i ").$_SERVER['PHP_SELF'].":".$str."\n";
      file_put_contents("/var/www/eltest/public_html/palomnichestvo/php.log", $resstr, FILE_APPEND);
  }


  static function generate_password1($number)
  {
    $arr = array('a', 'b', 'c', 'd', 'e', 'f',
      'g', 'h', 'i', 'j', 'k', 'l',
      'm', 'n', 'o', 'p', 'r', 's',
      't', 'u', 'v', 'x', 'y', 'z',
      'A', 'B', 'C', 'D', 'E', 'F',
      'G', 'H', 'I', 'J', 'K', 'L',
      'M', 'N', 'O', 'P', 'R', 'S',
      'T', 'U', 'V', 'X', 'Y', 'Z',
      '1', '2', '3', '4', '5', '6',
      '7', '8', '9', '0');
    // Генерируем пароль
    $pass = "";
    for ($i = 0; $i < $number; $i++) {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
  }

  static function readInJSON()
  {
    $rawInput = file_get_contents('php://input');
    $params = json_decode($rawInput, true);
    if ($params == null) {
      echo(json_encode(["res" => 'error processing',"src"=>$rawInput]));
      die();
    }
    return $params;
  }

  static function readInFile($file)
  {
    $fline = substr($file, strlen("data:image/png;base64,"));
    $fline2 = substr($file, strlen("data:image/jpeg;base64,"));
    $qrt2="";
    if (strlen($fline)>0)     $qrt2 = base64_decode($fline);
    else if  (strlen($fline2)>0)$qrt2 = base64_decode($fline2);
    return $qrt2;
  }


  static function updatePictureByPlaceId($placeid, $file)
  {
    return main::addNewPointPicture($placeid, $file);
  }

  static function updatePictureByPicId($id, $file)
  {
    $sq = "select id,name from photos where id=$id";
    $qt = db::fetchOne($sq);
    if ($qt) {
      $filename = $qt["name"];
      main::writeToFileWBak($filename, $file);
      return $qt;
    }
  }

  static function addNewPointPicture($placeid, $file)
  {
    $newfilename = "pic__file_" . rand(0, 9) . rand(0, 9) . rand(0, 9) . $placeid;
    main::writeToFile($newfilename, $file);
    $sq = "insert into photos(name, pid) values('$newfilename', $placeid)";
    $rt = db::query2($sq);
    $res = 0;
    if ($rt) $res = db::lastInsertId();
    return $res;
  }

  static function addNewTourPicture($id, $file)
  {
    $newfilename = "pic__file_" . rand(0, 9) . rand(0, 9) . rand(0, 9) . $id;
    main::writeToFile($newfilename, $file);
    $sq = "insert into photos(name, tid) values('$newfilename', $id)";
    $rt = db::query2($sq);
    $res = 0;
    if ($rt) $res = db::lastInsertId();
    return $res;
  }


  static function writeToFile($filename, $file)
  {
      main::logVar("TRYING TO SAVE");
      main::logVar(json_encode($file));

      $fp = fopen(main::picDirectoryRoot() . $filename, 'w');

    if ($fp) {
      if (fwrite($fp, $file) === FALSE) {
        main::logVar("Не могу произвести запись в файл");
        exit;
      }
      fclose($fp);
      return "ok";
    }
  }

  static function writeToFileWBak($filename, $file)
  {
    copy(main::picDirectoryRoot() . $filename, main::picDirectoryRoot() . "___" . $filename . ".bak");
    return main::writeToFile($filename, $file);
  }

  function resetPassword($id)
  {
    $pwd = generate_password1(6);
    $sq = "update clients set pwd='" . $pwd . "' where id=" . $id;
    db::query2($sq);
    return $pwd;
  }

  static function getOrganizators()
  {
      $sq='select * from clients where istourmaster=1';
      $res="";
      $resdb=db::query2($sq);
      if ($resdb) $res=$resdb->fetchAll();
      return $res;

  }

  static function checkDebug()
  {
    if (strpos($_SERVER['SERVER_NAME'], "elitsy.pozamerkam") >= 0 && ($_SERVER['REMOTE_ADDR'] != '85.235.189.246')) {
      if (strpos($_SERVER['HTTP_USER_AGENT'], "MI MAX") < 0) die();
    }
  }


  /*

  function getSaint($id)
  {
       // echo("!!!!".$id);
      return new saints($this->$id);
  }



  function getSaintsIds()
  {
      $sq='select id from saints where visible=1';
      echo($sq);

      $rt=$this->query($sq);
      //var_dump($rt);
      echo(count($rt));
      $res=array();
      while($rm=$rt->fetch_assoc())
      {
          try {
            //  ECHO ($rm instanceof mysqli_result);
              $val = $rm['id'];
              echo('5');
              echo($val);
          }
          catch(Exception $e) {
              echo('2323');
              echo($e->getMessage());}

          array_push($res,$val);
      }
      return $res;
  }

*/

}
