<?
define("IN_ADMIN", TRUE);
require "sqli.php";
//require "connect.inc";

require "newconnect-head.php";

//echo ('.!!!'.$_SESSION['userid']);
$method=$_GET['action'];
//echo ($method);

if ($_GET['action']=="") showTop();
if ($_POST['login']!="") checkUser();

switch ($method):
    case "":
        showLoginPWform(); break;
    case "login":
        checkUser();break;
    case "exit": doExit();break;
    default:
        showLoginPWform();
endswitch;



function showLoginPWform()
{

    if (strlen($_SESSION['id']) > 0) {

        echo("<div class='alert alert-warning'>Вы уже авторизованы с id=" . $_SESSION['id'] . " <br /><a href='login.php?action=exit'>Нажмите чтобы выйти из системы</a></div>");
    }
    else showLogin();
}

function showLogin()
{
    ?>
    <div class="row" >
        <div class="col-md-4  well" style="margin-top: 0px;">
            <form class="form col-md-12" method="POST" action="<? echo ($_SERVER['PHP_SELF']); ?>?action=login">

                <h3>Вход в систему</h3>

                <div class="form-group">
                    <label class="sr-only" for="exampleInputEmail2"></label>
                    <input type="text" class="form-control" name="login" placeholder="Номер телефона или е-мейл (для агентств)">
                </div>

                <div class="form-group">
                    <label class="sr-only" for="exampleInputEmail2">Пароль</label>
                    <input type="password" class="form-control" name="pwd" placeholder="Пароль">
                </div>

                <button type="submit" style="margin-bottom: 15px;" class="btn btn-info col-md-6">Войти</button>
            </form>
        </div>

    </div>
    <?
}

function checkUser()
{

    global $mysqli;

    $login=$_POST['login'];

    //echo ($login);
    if (strpos($login,'@')>0) $type=1; else $type=2;
//    echo ($type);

    if ($type==1) {

        $sq = "select * from main_users where user_login='" . $_POST['login'] . "' and user_password='" . $_POST['pwd'] . "'";

        $res = $mysqli->query($sq);
        $rmm = $res->fetch_assoc();
        //   echo ($rmm);
        if (count($rmm) > 0) {
            //     echo ("авторизация ок".$rmm['user_id']);
            $_SESSION['id'] = $rmm['user_id'];
            // $id=$_SESSION['id'];
            //  return $id;
            // setCookie("id", $rmm['user_id'],time()+50000000);
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = '/index.php';
            $redirstr = "Location: http://" . $host . $uri . $extra;

            header($redirstr);
            // echo("<div class='alert alert-warning'>".$redirstr."</div>");

            echo("<div class='alert alert-success'>Вы успешно вошли в систему. <a href='index.php'>Перейти к списку туров</a></div>");

            exit;


//        echo (time()+5000000);
            //       setCookie("autorized","yes");
            //   echo ('.!!!'.$_SESSION['userid']);

        } else {
            echo('<div class="alert alert-danger" role="alert">Вы ошиблись при вводе логина и пароля, проверьте не нажат ли у вас Caps Lock и  <a href=login.php>попробуйте еще раз</a></div>');
            die();
        }
    }

    if ($type==2) {

  //      echo ('user cabinet');
        $sq="select * from clients where phone='".$login."' and pwd='".$_POST['pwd']."'";


//        $sq=$mysqli->escape_string($sq);
//echo ($sq);
        $res=$mysqli->query($sq);
        $rmm = $res->fetch_assoc();
        //  echo ($rmm);
        if (count($rmm) > 0) {
//echo ('успешно');
            $id=$rmm['id'];
            $_SESSION['id'] = $id;
            ?>
            <script type="text/javascript">
                localStorage.setItem("uid",<? echo($rmm['user_id']);?> );
                window.location="http://www.nov-rus.ru/index.php?action=my";
            </script>
            <?

            $sq='select * from u_reserves where uid='.$id;
echo ($sq);
            $resm=$mysqli->query($sq);


            while ($rtl = $resm->fetch_assoc()) {
                $turdate=$rtl['turdate'];
                $turid=$rtl['turid'];
                //echo ($turid);
                $tur=getTourDataById($turid);
                echo ($tur['title']."<br />");


            }

            }

        $_SESSION['type']=$type;
            //$mysqli-
         exit;

    }

}

function isAuth()
{
    //  echo("Сессия".$_SESSION['id']);
    //  if (strlen($_SESSION['id'])>0) echo ("авторизован<a href=list.php>тест</a>");else echo ('не автор<a href=list.php>тест</a>');
}

function doExit()
{
    $_SESSION['id']="";
    setCookie("id","");
    session_write_close();
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = '/login.php';
    $redirstr="Location: http://".$host.$uri.$extra;

    header($redirstr);
    echo('<script language="javascript">document.location.href ="'.$redirstr.'";</script>');


    exit;
}




?>

</div>



</body>
</html>
