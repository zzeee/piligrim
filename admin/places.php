<?php

require_once "../public_html/palomnichestvo/classes/db.php";
require_once "../public_html/palomnichestvo/classes/main.php";

class places
{
    public $placeslist;
    protected $mysqlil;

    function __construct($mysqli)
    {
        $this->mysqlil = $mysqli;
        $sq = 'select * from places order by id desc';
        $this->placeslist = $this->mysqlil->query($sq);
    }


    function update()
    {

        //$_GET['id'], $_GET['name'], $_GET['date1'], $_GET['datedescr'], $_GET['lat'], $_GET['lon'], $_GET["cityid"], $_GET["showontop"], $_GET["eelitsy"], $_GET["eaddr"], $_GET["maindescr"], $_GET["erating"], $_GET["visible"]
      //  var_dump($_POST);


       // echo('1234234wefwe');

        $rawInput = file_get_contents('php://input');
    //    echo($rawInput);

        $params=json_decode($rawInput, true);
        if ($params==null) { echo(json_encode(["res"=>'Ошибка приема'])); die(); }

        $id=$params["eid"];
        $newname=$params["ename"];
        //$edescr=$params["main_descr"];
        //echo($rawInput);
       // var_dump($params);

        $sq = "update places set name='" . $newname . "' ";
        if (isset($params["date1"])) $sq .= ", date1='" . $params["date1"] . "'";

        if (isset($params["visible"])) $sq.=", visible=".$params["visible"];
        if (isset($params["elat"]) && $params["elat"]!="") $sq .= ", lat='" . $params["elat"] . "'";
        if (isset($params["elon"]) && $params["elon"]!="") $sq .= ", lon='" . $params["elon"]. "'";
        if (isset($params["ecity"]) && $params["ecity"]!= "undefined") $sq .= ", cityid=" . $params["ecity"];
        if (isset($params["showmain"])  && $params["showmain"] == "1") $sq .= ", showtop=1";else $sq .= ", showtop=0";
        //if ($params["eontop"] != "" && $params["eontop"] == "off") $sq .= ", showtop=0";
        if (isset($params["eelitsy"])) $sq .= ", elitsy_url='" . $params["eelitsy"]."'";
        if (isset($params["eaddr"])) $sq.=", address='".$params["eaddr"]."'";
        if ($params["erating"]!= "") $sq .= ", rating=" . $params["erating"]; else $sq .= ", rating=0 ";
        if (isset($params["edescr"])) $sq .= ", main_descr='" . $params["edescr"] . "'";
        if (isset($params["d_author"])) $sq .= ", d_author='" . $params["d_author"] . "'";

        if (isset($params["main_descr"])) $sq.=", descr='".$params["main_descr"]."'";

        $sq .= " where id=" . $id;
       // echo($sq);

        try {
            // echo("tesr".$this->mysqlil."2e32");
            $resq=db::query2($sq);
            //echo($sq);
          //  var_dump($resq);
            echo(json_encode(["status"=>"ok","res"=>$resq]));
        } catch (Exception $e) {
            echo(json_encode(["status"=>"nok","res"=>$e->getMessage()]));
        }
        //echo($this->mysqlil->affected_rows);
    return "1";
    }

    function insert($name, $type, $date1, $datedescr)
    {
        if ($date1 != "" && $datedescr != "") {

            $sq = "insert into places(name, tname, type,date1, datedescr) values('" . $name . "',_fs_transliterate_ru('$name'), " . $type . ",'" . $date1 . "','" . $datedescr . "')";

        } else $sq = "insert into places(name, tname, type) values('" . $name . "',_fs_transliterate_ru('$name')," . $type . ")";
        $res = 0;

        try {
            $rt = $this->mysqlil->exec($sq);
        } catch (Exception $e) {
            echo($e->getMessage());
        }

//        var_dump($rt);


        if ($rt) {
            echo('2');
            $res = $this->mysqlil->lastInsertId;
            echo('1' . $res);
        }

        return $res;
    }

    function delete($id)
    {

        $sq = 'delete from places where id=' . $id;
        if ($this->mysqlil->query($sq)) echo("1");

    }


    function showall()
    {
        $dat = "";
        while ($rt = $this->placeslist->fetch(PDO::fetch_assoc)) {
            $dat .= "<tr><td>" . $rt['name'] . "</td><td>&nbsp;" . $rt['type'] . "</td><td>&nbsp;" . $rt['lat'] . "</td><td>&nbsp;" . $rt['lon'] . "</td><td>&nbsp;" . $rt['date1'] . "</td><td>&nbsp;" . $rt['datedescr'] . "</td></tr>";
        }

        if ($dat != "") $dat = "<table>" . $dat . "</table>";
        echo($dat);
    }


    function showJSON($typ, $typ2)
    {

        $tres = $this->placeslist;
        if ($typ != "") {
            $sq = "select * from places where type=$typ order by name";

            if ($typ == 1) {
                $sq = "select * from places where type=2 order by name";
            }

            if ($typ == 6) {
                $sq = "select * from places where id=$typ2 order by name";
            }
            //  echo($sq);

            $tres = $this->mysqlil->query($sq);
        }

        $arr = array();
        //$qres=$tres->fetch(PDO::FETCH_ASSOC);
        //while ($rt = $tres->fetch_array()) {
        while ($rt = $tres->fetch(PDO::FETCH_ASSOC)) {

            $pq = $rt;
            array_push($arr, $pq);
        }
        echo(json_encode($arr));

    }

    function showDHTML()
    {
        //showTop();
        //$this->showall();
        //$dat='<script lan';

        ?>
        <html>
        <head>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
                  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
                  crossorigin="anonymous">

            <!--


            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
            <!-- Bootstrap core CSS
            <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap theme
            <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
            <!-- IE10 viewport hack for Surface/desktop Windows 8 bug
            <link href="bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

            <!-- Custom styles for this template
            <link href="bootstrap/css/theme.css" rel="stylesheet">-->
            <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.js "></script>
            <script type="text/javascript"
                    src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>



            <?php
            //echo ($_SESSION['id']."wfw".$_COOKIE['id']);
            define("IN_ADMIN", TRUE);
            //          require "sqli.php";

            //            require "commlib.php";
            //echo('112');

            global $mysqli;
            //

            //showTop();
            ?>

            <style type="text/css">
                .editwindow: {
                    width: 400px;
                    position: absolute;
                    top: 0;
                    left: 0;

                    z-index: 10;
                    margin: 40px auto 0px auto;
                    height: 250px;
                    padding: 10px;
                    background-color: white;
                    border-radius: 5px;
                    box-shadow: 0px 0px 10px #000;
                }

                #addnewwindow: {
                    width: 400px;
                    overflow: hidden;
                    position: absolute;
                    top: 20%;
                    left: 40%;
                    z-index: 3;
                    margin: 40px auto 0px auto;
                    height: 250px;
                    padding: 10px;
                    background-color: white;
                    border-radius: 5px;
                    box-shadow: 0px 0px 10px #000;
                }
            </style>

            <script type="text/javascript">



                var places = function () {
                    places.prototype.edit = function (id) {
                        $("#eid").val(id);
                        $("#ename").val(window.arr[id]['name']);
                        $("#showcity").css("visibility", "hidden");

                        typ = window.arr[id]['type'];
                        $("#edate1").val(window.arr[id]['date1']);
                        $("#elat").val(window.arr[id]['lat']);
                        $("#erating").val(window.arr[id]['rating']);
                        $("#elon").val(window.arr[id]['lon']);
                        $("#d_author").val(window.arr[id]['d_author']);

                        $("#edescr").val(window.arr[id]['main_descr']);
                        $("#eelitsy").val(window.arr[id]['elitsy_url']);

                        //alert(+"!!!!!!!!!!!");
                        rt=window.arr[id]['visible'];

                        if (rt==1) {
                            //alert('2');
                            document.getElementById("visible").checked=true;
                        }
                        else document.getElementById("visible").checked=false;
                        $("#eaddr").val(window.arr[id]['address']);
                        $("#main_descr").val(window.arr[id]['descr']);
                     //   alert(window.arr[id]['descr']);

                        $("#editwindow").css("visibility", "visible");

                        if (typ == 2 || typ == 3 || typ == 6 || typ == 7 || typ == 8 || typ == 9 || typ==10 || typ==100) {
                            $("cityselect").css("visibility", "visible");
                            $("#showcity").css("visibility", "hidden");

                            this.id = id;
                            this.cityid = window.arr[id]['cityid'];
                            //  console.log(window.cities);
                            citylist = window.cities.map((function(line) {
                                                             // if (line["id"]==this.cityid) alert(this.id);
                                    //if (this.id==6) console.log("!!!!!!!!!!!"+this.id+" "+line);
                                   // console.log(id+" "+line["cityid"]+" "+line["name"]);
                                    return "<option " + (parseInt(this.cityid) == parseInt(line["id"]) ? " selected " : '') + "  value='" + line["id"] + "'  >" + line["name"] + "</option>"
                                }).bind(this));
                            rt = document.getElementById("cityselect");
                            rt.innerHTML = "<select id='cityid'><option value='0'>нет</option>" + citylist + "</select>";
                            //console.log(citylist);

                        }

                        if (typ == 1 || typ == 2) {
                            $("#showcity").css("visibility", "visible");
                            stop = window.arr[id]['showtop'];
                            //console.log(stop);
                            qtop = (stop == '1');
                            //console.log(id + " " + qtop + " " + stop)
                            rt = document.getElementById("showmain");
                            //console.log(rt);
                            if (stop == 1) {
                                rt.checked = true
                            } else {
                                //console.log("delattr");
                                rt.checked = false;//removeAttribute("checked");
                            }
                            rt.setAttribute("checked", qtop);
                            $("#showmain").checked = true;//prop("checked")=qtop;//prop("checked")=qtop;

                        }
                    }

                    places.prototype.closeedit=function()
                    {
                        $("#editwindow").css("visibility", "hidden");
                        $("#cityselect").css("visibility", "hidden");
                        //$("cityselect").css("visibility", "visible");
                        $("#showcity").css("visibility", "hidden");

                        //this.showList();

                        //alert('close');
                    }
                    places.prototype.addplace = function () {
                        aname = $("#aname").val();
                        atyp = $("#atyp").val();
                        adate1 = $("#adate1").val();
                        adescr = $("#adescr").val();
                        plline = "places.php?action=add&name=" + aname + "&type=" + atyp + "&date1=" + adate1 + "&datedescr=" + adescr;
                        $.ajax({
                            url: plline,
                            cache: false,
                            type: "GET",
                            success: function (data) {
                                console.log(data);
                                place.showList();
                            }
                        });

                    }


                    places.prototype.savePlace = function () {
                        ename = $("#ename").val();
                        eid = $("#eid").val();
                        edate1 = $("#edate1").val();
                        edescr = $("#edescr").val();
                        elat = $("#elat").val();
                        elon = $("#elon").val();
                        ecity = $("#cityid").val();
                        eontop = $("#showmain").val();
                        erating=$("#erating").val();
                        if (document.getElementById("visible").checked) visible=1; else visible=0;

                        eelitsy=$("#eelitsy").val();
                        eaddr=$("#eaddr").val();
                        main_descr=$("#main_descr").val();
                        qtop = document.getElementById("showmain");
                        line = "places.php?action=update&id=" + eid + "&erating="+erating+"&eelitsy="+eelitsy+"&eaddr="+eaddr+"&name=" + ename + "&date1=" + edate1 + "&datedescr=" + edescr + "&lat=" + elat +"&visible="+visible+ "&lon=" + elon + ((typeof ecity) != undefined && ecity != 'undefined' ? "&cityid=" + ecity : "") + (qtop.checked ? "&showontop=1" : "&showontop=0");
                        linearr={};
                        linearr["ename"] = $("#ename").val();
                       // linearr["id"]=
                        //linearr["newname"]=
                        linearr["eid"] = $("#eid").val();
                        linearr["edate1"] = $("#edate1").val();
                        linearr["edescr"] = $("#edescr").val();
                        linearr["d_author"] = $("#d_author").val();
                        linearr["elat"] = $("#elat").val();
                        linearr["elon"] = $("#elon").val();
                        linearr["ecity"] = $("#cityid").val();
                        //linearr["eontop"] = $("#showmain").val();
                        linearr["erating"]=$("#erating").val();
                        if (document.getElementById("visible").checked) linearr["visible"]=1; else linearr["visible"]=0;
                        linearr["eelitsy"]=$("#eelitsy").val();
                        linearr["eaddr"]=$("#eaddr").val();
                        linearr["main_descr"]=$("#main_descr").val();

                        if (document.getElementById("showmain").checked) linearr["showmain"] =1 ; else linearr["showmain"]=0;
                        line = "places.php?action=update&id=" + eid;

                        //console.log(line);
                        console.log(linearr);
//                        window.test=linearr;
                        console.log(JSON.stringify(linearr));
 //alert(linearr);

                        alert(line);
                        $.ajax({
                            url: line,
                            type: "POST",
                            cache: false,
                            async: true,
                            processData: true,
                            contentType: 'application/json; charset=utf-8',
                            dataType: 'text',
                            data: JSON.stringify(linearr),
                            success: function (data, status, obj) {
                                console.log(data);
//                                console.log(status);
  //                              console.log(obj);
                               alert('success');
                                var thisPlace = this;
                            },

                            error: function (data, p1,  p2){
                                console.log(data);
                                console.log(p1);
                                console.log(p2);
                               // alert(data);
                                alert('ee2')
                                }
                        });
                   //alert('esave');
                    }

                    places.prototype.dodelete = function (id) {
                        lline = "places.php?action=delete&id=" + id;
                        $.ajax({
                            url: lline,
                            cache: false,
                            type: "GET",
                            success: function (data) {
                                console.log(data);
                                place.showList();
                            }
                        });
                    }

                    places.prototype.showList = function () {
                           $.ajax({
                                url: "places.php?action=getjson",
                                cache: false,
                                type: "GET",
                                async: true,
                                processData: true,
                                dataType: 'JSON',
                                data: "",
                                success: function (data) {
                                    var items = [];
                                    console.log(data);
                                    i = 0;
                                    //arr="";
                                    a1 = "";
                                    dat = "";
                                    $.each(data, function (key, val) {
                                        a1 = val['id'];
                                        // console.log(a1);
                                        window.arr[a1] = val;
                                      //  if (parseInt(val['cityid']) > 0) console.log("ok!" + val["id"] + " " + val["name"]);
                                        if (val['type'] == 1) {
                                            cities.push(val);
                                        }
                                        datt = val['date1'];
                                        if (datt == '0000-00-00')datt = "";
                                        dat += "<tr><td><a onclick=place.edit(" + a1 + ")>" + val['name'] + "("+a1+")"+'</td><td><a target=_blank href="http://www.elitsy.ru/palomnichestvo/sp/' + a1 + '">' + val['type'] + '</a></td><td>' + val['lat'] + '</td><td>' + val['lon'] + '</td><td>' + datt + '</td><Td>' + val['main_descr'] + '</td><Td>' + val['descr'] + '</td><td>' + val['cityid'] + '</td><td><a onclick="place.dodelete(' + a1 + ')">удалить</a></td></tr>';
                                    });
                                    if (dat != "") dat = '<table class="table">' + dat + '</table>';
                                    //rt=document.getElementById("testzone");
                                    //rt.innerHTML=dat;
                                    //console.log($("testzone").innerText);
                                    $("#testzone").html(dat);
                                    $("#testzone").css("visibility", "visible");
                                },
                                error: function(edata) {console.log(edata);
                    }
                    })
                    }
                    var iarr = new Array();
                    $("#addb").click(function () {
                        place.addplace();
                    });

                    $("#save").click(function () {
                        place.savePlace();
                    });
                    this.showList();
                    $("#show").click((function () {
                        this.showList();
                    }).bind(this));
                };

                window.arr = new Array();
                window.cities = new Array();

                $(document).ready(function () {
                    place = new places();
                });


            </script>
        </head>
        <body>
        <button id="show">show</button>
        <div id="tzone">
            <div id="row">
        <span id="addnewwindow" class="col-md-3" style="display:yes">
        <h1>Добавить</h1>
   <table>
<tr><td>        Имя:</td><td> <input width="50" type="text" id="aname"/></td></tr>
        <tr><td></td>Тип</td><td><select name="atyp" id="atyp"><option value="1">Город</option><option value="2">Монастырь</option>
                    <option value="3">Храм, собор, часовни</option>
                    <option value="6">Святой источник</option>
                    <option value="7">Святые мощи</option>
                    <option value="8">Особо почитаемые иконы</option>
                    <option value="9">Особо почитаемые места и предметы</option>
                    <option value="10">Исторически значимые достопримечательности</option>
                    <option value="100">Паломнический центр</option>

                    <option value="5">Святой</option></select></td></tr>
   <tr><td>Дата1:</td><td><input width="50" type="text" id="adate1"/><br/></td></tr>
   <tr><td>Описание даты:</td><td><input width="50" cols="80" type="text" id="adescr"/><Br/></td></tr>
        <tr><td><button id="addb">Добавить</button></td></tr>
</table>

    </span>

                <span id="editwindow" class="col-md-3 editwindow" style="position:fixed; margin-left:0px; width:100%; height:100%;background-color:white;visibility:hidden">
<div  onclick="place.closeedit()">закрыть</div>
    <h1>Редактирование точки/события</h1>
                    <form name="editform">
    <input type=hidden id="eid"/>
    <table><form id="eform">
            <tr><td>Имя:</td><td><input width="50" type="text" id="ename"/></td></tr>
    <tr><td>Дата 1: </td><td><input width="50" type="text" id="edate1"/></td></tr>
    <tr><td>Описание</td><td><input width="50" cols="80" type="text" id="edescr"/></td></tr>
    <tr><td>Автор </td><td><input width="50" cols="80" type="text" id="d_author"/></td></tr>

    <tr><td>Lat</td><td><input width="50" cols="80" type="text" id="elat"/></td></tr>
        <tr><td>Lon</td><td><input width="50" cols="80" type="text" id="elon"/></td></tr>
    <tr><td>Address</td><td><input width="50" cols="80" type="text" id="eaddr"/></td></tr>
        <tr><td>Rating </td><td><input width="50" cols="80" type="text" id="erating"/></td></tr>
        <tr><td>Visible</td><td><input type="checkbox" id="visible"/></td></tr>

    <tr><td>URL на сайте Елицы</td><td><input width="50" cols="80" type="text" id="eelitsy"/></td></tr>
    <tr><td>Описание </td><td><textarea rows="10" cols="60" type="text" id="main_descr"></textarea></td></tr>


            <tr><td></td><td id="cityselect"></td></tr>
            <tr><td></td><td id="showcity" style="visibility:hidden"><input type="checkbox" id="showmain"/>Показывать на главной</td></tr>
            <tr><td><button id="save">Сохранить</button></td></tr>
</form>
    </table>
                    </form>
</span>
            </div>
        </div>
        <span id="testzone" style="visibility:hidden"></span>

        </body>
        </html>


        <?


    }

    function parseAction($action)
    {
        switch ($action):
            case 'delete':
                $this->delete($_GET['id']);
                break;
            case 'update':
                $this->update();
                break;
            case 'add':
                $this->insert($_GET['name'], $_GET['type'], $_GET['date1'], $_GET['datedescr']);
                break;
            case 'getjson':
                $this->showJSON($_GET['type'], $_GET['type2']);
                break;
            default:
                $this->showDHTML();
        endswitch;

    }


}

define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";


global $mysqli;
$rt = new places($mysqli);
//$rt->showall();
//echo("!".$_GET['action']);

$action=$_GET['action'];

if ($_SERVER["REQUEST_METHOD"]=="POST") $action=$_POST['action'];
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $rt->update();
    die();
    //echo("wef2we");
}


//echo($action);
$rt->parseAction($action);

//$rt->update(40, "Амвросий Оптинский", "2016-10-23", "празднование");
//$rt->showJSON();
//$rt->insert('Серафим Саровский',5, '2017-01-15','ДЕНЬ ПАМЯТИ ПРЕПОДОБНОГО СЕРАФИМА САРОВСКОГО');
//$rt->delete(46);
?>