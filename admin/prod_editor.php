<?php
require_once "../public_html/palomnichestvo/classes/db.php";
require_once "../public_html/palomnichestvo/classes/main.php";


class prod_editor
{
    public $products;
    protected $mysqlil;

    function __construct()
    {
        $sq = 'select * from add_services';
        $rt= db::query2($sq);
        //echo($rt);
        $this->products=$rt;
    }


    function showall()
    {
        $dat = "";
        while ($rt = $this->products->fetch_array()) {
            $dat .= "<tr><td>" . $rt['title'] . "</td><td>&nbsp;" . $rt['type'] . "</td><td>&nbsp;" . $rt['price1'] . "</td><td>&nbsp;" . $rt['description'] . "</td></tr>";
        }

        if ($dat != "") $dat = "<table>" . $dat . "</table>";
        echo($dat);
    }





    function update($id, $newname, $type, $price, $price1,$price2, $price3, $price4, $price5, $tourid, $descr, $pics)
    {
        $sq="update add_services  set title='".$newname."' ";
        if ($type!="") $sq.=", type='".$type."'";
        if ($price!="") $sq.=", price='".$price."'";
        if ($price1!="") $sq.=", price1='".$price1."'";
        if ($price2!="") $sq.=", price2='".$price2."'";
        if ($price3!="") $sq.=", price3='".$price3."'";
        if ($price4!="") $sq.=", price4='".$price4."'";
        if ($price5!="") $sq.=", price5='".$price5."'";
        if ($type==3) {$sq.=", placeid=".$tourid.""; } else
        {        if ($tourid!="") $sq.=", tourid=".$tourid."";}
        $sq.=", description='".$descr."'";
        $sq.=" where id=".$id;
        try {
            if (db::query2($sq)) echo ('1');
            db::query2("update photos set asid=".$id." where id in (".$pics.")");
        }
        catch (Exception $e) {echo($e->getMessage());}
    }

    function insert($name, $type, $price, $tourid )
    {
        if ($price!="" )
        {
            $sq="insert into add_services(title, type, price) values('".$name."', ".$type.",".$price.")";
            if ($tourid!="")
            {
if ($type!=3)
                $sq="insert into add_services(title, type, price, tourid) values('".$name."', ".$type.",".$price.",".$tourid.")";
else
    $sq="insert into add_services(title, type, price, placeid) values('".$name."', ".$type.",".$price.",".$tourid.")";
            }

        }
        else $sq="insert into add_services(title, type) values('".$name."', ".$type.")";
        //echo($sq);
        $res=0;
        if ($qres=db::query2($sq))
        {
            $res=db::lastInsertId();
          //  echo ('1'.$res);
        }
        //echo($qres);
        return $res;
    }

    function delete($id)
    {
        $sq='delete from add_services where id='.$id;
        if (db::query2($sq)) echo (json_encode(array("result"=>"1")));

    }

    function getPJSON($id)
    {
        $sq="select * from photos where asid=".$id;
      //  echo($sq);
        $rm=db::query2($sq);
        $rarr=array();

        while ($rt = $rm->fetch(PDO::FETCH_ASSOC)) {
            array_push($rarr, $rt);
        }

        echo(json_encode($rarr));

        }




    function showJSON()
    {
        $arr = array();
        while ($rt = $this->products->fetch(PDO::FETCH_ASSOC)) {

            $pq = $rt;
            array_push($arr, $pq);
        }

        echo(json_encode($arr));

    }

    function showDHTML()
    {


        ?>
        <html>
        <head>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

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

            <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


            <script type="text/javascript"src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
            <script type="text/javascript"src="js/notify.min.js"></script>


            <?php
            //echo ($_SESSION['id']."wfw".$_COOKIE['id']);
          //  define("IN_ADMIN", TRUE);
        //    require "sqli.php";
            //require "commlib.php";
            global $mysqli;
            //

            //showTop();
            ?>

            <style type="text/css">
                a, #closeadd, #closeedit {cursor:pointer}

                .editwindow: {width:400px;
                    position: absolute;
                    top: 0;
                    left: 0;

                    z-index:10;
                    margin:40px auto 0px auto;
                    height: 250px;
                    padding:10px;
                    background-color: white;
                    border-radius:5px;
                    box-shadow: 0px 0px 10px #000;
                }
                #addnewwindow: {
                    position:absolute; top:40%; left:40%; width:400px; height:250px; z-index:10; border:thin solid red;


                }
            </style>

            <script type="text/javascript" >

                var prod=function()
                {
                    prod.prototype.edit=function(id)
                    {
                        $("#prod_eid").val(id);
                        $("#prod_ename").val(window.arr[id]['title']);
                        $("#prod_eprice").val(window.arr[id]['price']);
                        $("#prod_eprice1").val(window.arr[id]['price1']);
                        $("#prod_eprice2").val(window.arr[id]['price2']);
                        $("#prod_eprice3").val(window.arr[id]['price3']);
                        $("#prod_eprice4").val(window.arr[id]['price4']);
                        $("#prod_eprice5").val(window.arr[id]['price5']);
                        $("#prod_etourid").val(window.arr[id]['tourid']);
                        if (window.arr[id]['type']==3)
                        {
                            $("#prod_etourid").val(window.arr[id]['placeid']);

                        }
                        $("#prod_etype").val(window.arr[id]['type']);


                        $("#prod_descr").val(window.arr[id]['description']);


                        $("#bedit").css("visibility", "visible");
                        plline="prod_editor.php?action=showpjson&id="+id;
                        console.log(plline);


                        $.ajax({
                            url: plline,
                            async: true,
                            processData: false,
                            dataType: 'JSON',
                            data: "",
                            cache: false,
                            type: "GET",
                            success: function (data) {
                                console.log("!!!"+data);
                                datastr="";
                                $.each(data, function (key, val) {
                                    //console.log(key);
                                    fname=val['name'];
                                    if (fname!="")datastr+='<img src="img/'+fname+'" />';

                                    //if (key=="name")
                                    //if (key=="userdata") uarr=val;
                                    //console.log(key+" "+val+" "+val['id']);
                                    //dataarr=val;
                                });
                                console.log(datastr);

                                if (datastr!="")  {
                                    datastr="<span>"+datastr+"</span>";

                                    $("#pics_area").html(datastr);
                                    //alert($("pics_area").html());
                                }


                                //prod1.showList();
                            }, error:function (xhr, ajaxOptions, thrownError) {

                                console.log(xhr.status+" "+thrownError+" ");
                            }
                        } );



                    }

                    prod.prototype.addplace=function()
                    {
                        aname = $("#prod_name").val();
                        atyp = $("#prod_typ").val();
                        price = $("#prod_price").val();
                        tourid=$("#prod_tourid").val();
                        if (String(tourid)=="undefined") tourid="";
                         plline="prod_editor.php?action=add&name="+aname+"&type="+atyp+"&price="+price+(tourid!=""?"&tourid="+tourid:"") ;
                        console.log(plline);
                        $.ajax({
                            url: plline,
                            async: true,
                            processData: false,
                            dataType: 'text',
                            data: "",
                            cache: false,
                            type: "GET",
                            success: function (data) {
                                console.log(data);
                                prod1.showList();
                            }, error:function (xhr, ajaxOptions, thrownError) {

                                console.log(xhr.status+" "+thrownError+" ");
                            }
                        } );

                    }


                    prod.prototype.savePlace=function () {
                        ename = $("#prod_ename").val();
                        eid = $("#prod_eid").val();
                        eprice=$("#prod_eprice").val();
                        eprice1=$("#prod_eprice1").val();
                        eprice2=$("#prod_eprice2").val();
                        eprice3=$("#prod_eprice3").val();
                        eprice4=$("#prod_eprice4").val();
                        eprice5=$("#prod_eprice5").val();
                        etourid=$("#prod_etourid").val();
                        eproddescr=$("#prod_descr").val();
                        pics=$("#prod_pics").val();
                        etype=$("#prod_etype").val();


                        line = "prod_editor.php?action=update&id=" + eid + "&name=" + ename + "&price=" + eprice + "&price1=" + eprice1 + "&price2=" + eprice2 + "&price3=" + eprice3 + "&price4=" + eprice4 + "&price5=" + eprice5 + "&type=" + etype+"&tourid=" + etourid+"&descr="+eproddescr+"&pics="+pics;
                        console.log(line);
                        $.ajax({
                            url: line,
                            cache: false,
                            type: "GET",
                            async: true,
                            processData: true,
                            dataType: 'text',
                            data: "",
                            success: function (data) {
                                console.log(data);
                                var thisPlace=this;
                                //alert(thisPlace.html);
                                $("#bedit").css("visibility", "hidden");

                                prod1.showList();
                            }
                        });

                    }

                    prod.prototype.dodelete=function (id)
                    {
                        lline="prod_editor.php?action=delete&id="+id;
                        $.ajax({
                            url: lline,
                            cache: false,
                            type: "GET",
                            success: function (data) {
                                console.log(data);
                                prod1.showList();
                            }
                        } );
                    }

                    prod.prototype.showList=function()
                    {
                        //jsline2 = "";

                        $.ajax({
                            url: "prod_editor.php?action=getjson",
                            cache: false,
                            type: "GET",
                            async: true,
                            processData: true,
                            dataType: 'JSON',
                            data: "",
                            success: function (data) {
                                var items = [];
                                  //console.log(data);
                                i=0;
                                //arr="";
                                a1="";
                                dat="";
                                $.each(data, function (key, val) {
                                    a1=val['id'];
                                //    console.log(a1);
                                    window.arr[a1]=val;
                                    //  console.log(window.arr[a1]);

                                    //datt=val['date1'];
                                    //if (datt=='0000-00-00')datt="";
                                    typp=val['type'];
//                                    console.log(val['title']+"~"+typp);
                                    typstr="";
                                    if (parseInt(typp)==0) typstr='опция тура';
                                    if (parseInt(typp)==1) typstr='товар';

                                    if (parseInt(typp)==2) typstr='опция туров';
                                    if (parseInt(typp)==3) typstr='опция размещения';


                                    dat+="<tr><td><a onclick=prod1.edit("+a1+")>"+val['title']+'</td><td>'+typstr+'</td><td>'+val['tourid']+'</td><td>'+val['price']+'</td><td>'+val['price1']+'</td><td>'+val['price2']+'</td><td>'+val['price3']+'</td><td>'+val['price4']+'</td><td>'+val['price5']+'</td><td><a onclick="prod1.dodelete('+a1+')">удалить</a></td></tr>';
                                });
                                if (dat!="") dat='<table class="table">'+dat+'</table>';
                                //rt=document.getElementById("testzone");
                                //rt.innerHTML=dat;
                                //console.log($("testzone").innerText);
                                $("#testzone").html(dat);
                                $("#testzone").css("visibility", "visible");
                            }

                        });

                    }

                    var iarr=new Array();
                    //console.log('конструктор');
                    this.showList();
                    $("#prod_addb").click(function() {
                        prod1.addplace();
                        $("#baddnew").css("visibility", "hidden");
                    });

                    $("#prod_save").click(function() {
                        prod1.savePlace();
                    });


                    $("#closeadd").click(function() {
                        $("#baddnew").css("visibility", "hidden");
                    });

                    $("#prod_add").click(function() {
                        $("#baddnew").css("visibility", "visible");
                    });



                    $("#closeedit").click(function() {
                        $("#bedit").css("visibility", "hidden");
                    });


//                    $("#test").click(function() {
  //                  });




                    /*

                                    $('#photoimg').live('change', function() {
                                        var A=$("#imageloadstatus");
                                        var B=$("#imageloadbutton");

                                        $("#imageform").ajaxForm({target: '#preview',
                                            beforeSubmit:function(){
                                                A.show();
                                                B.hide();
                                            },
                                            success:function(){
                                                A.hide();
                                                B.show();
                                            },
                                            error:function(){
                                                A.hide();
                                                B.show();
                                            }
                                        }).submit();
                                    });
                    */

        };


                window.arr=new Array();


                $(document).ready(function() {
//                    $( "#dialog" ).dialog({title:"Сообщение мяв"});

                    prod1=new prod();



                });

function uupload(file)
{
    console.log('d');


    /* Is the file an image? */
    if (!file || !file.type.match(/image.*/)) return;

    /* It is! */
    document.body.className = "uploading";

    /* Lets build a FormData object*/
    var fd = new FormData(); // I wrote about it: https://hacks.mozilla.org/2011/01/how-to-develop-a-html5-image-uploader/
    fd.append("image[]", file); // Append the file
    var xhr = new XMLHttpRequest(); // Create the XHR (Cross-Domain XHR FTW!!!) Thank you sooooo much imgur.com
    xhr.open("POST", "http://dev2.elitsy.ru/uploader.php"); // Boooom!
    xhr.onload = function() {
        // Big win!
        
        console.log(xhr.responseText);
        pidarr=JSON.parse(xhr.responseText);
//pidarr=0;

        console.log(pidarr);
        $("#prod_pics").val(pidarr);
        //document.querySelector("#link").href = JSON.parse(xhr.responseText).data.link;
        //document.body.className = "uploaded";
    }

    //xhr.setRequestHeader('Authorization', 'Client-ID 28aaa2e823b03b1'); // Get your own key http://api.imgur.com/

    // Ok, I don't handle the errors. An exercise for the reader.

    /* And now, we send the formdata */
    xhr.send(fd);

    
}
                
            </script>
        </head>
        <body>

        <button id="prod_test" >Тест</button>

        <button id="prod_add" >Добавить</button>

        <span id="testzone" style="visibility:hidden; position:absolute; z-index:1"></span><br />
                <div id="tzone">
            <div id="row"><span id="baddnew" style="visibility:hidden; position:absolute; top:0; left:0; width:100%; height:100%; background-color: whitesmoke ; z-index:9 ">
        <span id="addnewwindow" class="col-md-3"  style="position:absolute; left:10%; top:0%; width:400px;  height:300px; border-radius: 5px; background: white; padding: 10px">
            <span class="glyphicon glyphicon-remove" id="closeadd" aria-hidden=true style="align:right; position: absolute; right:10; top:10"></span>
        <h1>Добавить</h1>
   <table border="0">
<tr><td>        Имя:</td><td> <input width="50" type="text" id="prod_name" /></td></tr>
        <tr><td>Тип</td><td><select name="atyp" id="prod_typ"><option value="0">Опция тура</option><option value="1">Товар</option><option value="2">Опция туров</option><option value="3">Опция размещения</option></select></td></tr>
   <tr><td>Цена базовая</td><td><input width="50" type="text" id="prod_price" /><br /></td></tr>
   <tr><td>Номер тура или места/гостиницы (если применимо)</td><td><input width="50" cols="80" type="text" id="prod_tourid" /><Br /></td></tr>
        <tr><td><button  id="prod_addb">Добавить</button></td></tr>
</table>

    </span></span>
                <span id="bedit" style="visibility:hidden; position:absolute; top:0; left:0; width:100%; height:100%; background-color: whitesmoke ; z-index:9 ">
                <span id="editwindow" class="col-md-3 editwindow"  style="background-color: white; border-radius: 10px; left:10%; width:350px; padding: 10px" >
                    <span class="glyphicon glyphicon-remove" id="closeedit" aria-hidden=true style="align:right;  position: absolute; right:0; top:0"></span>
    <h1>Редактирование точки</h1>
    <input type=hidden id="prod_eid" />
    <table>
            <tr><td>Имя:</td><td><input width="50" type="text" id="prod_ename" /></td></tr>
    <tr><td>Цена базовая</td><td><input width="50" type="text" id="prod_eprice" /></td></tr>
    <tr><td>Тур ид</td><td><input width="50" type="text" id="prod_etourid" /></td></tr>
    <tr><td>Тип</td><td><select id="prod_etype"><option value="0">Опция</option><option value="1">Товар</option><option value="2">Опция туров</option><option value="3">Опция размещения</option></select></td></tr>

        <tr><td>Цена1</td><td><input width="50" type="text" id="prod_eprice1" /></td></tr>
    <tr><td>Цена2</td><td><input width="50" type="text" id="prod_eprice2" /></td></tr>
    <tr><td>Цена3</td><td><input width="50" type="text" id="prod_eprice3" /></td></tr>
    <tr><td>Цена4</td><td><input width="50" type="text" id="prod_eprice4" /></td></tr>
    <tr><td>Цена5</td><td><input width="50" type="text" id="prod_eprice5" /></td></tr>
    <tr><td>Описание</td><td><textarea id="prod_descr"></textarea></td></tr>
        <tr><Td colspan="2"><span id="pics_area"></span></Td></tr>
        <tr><td colspan="2"><div><button onclick="document.querySelector('#senddata').click()">Выбрать файл</button></div>
<input id="senddata" style="visibility: collapse; width: 0px;" type="file"  onchange="uupload(this.files[0])"><input type="hidden" id="prod_pics" />

</td></tr>
        <tr><td></td><td></td></tr>










    <tr><td><button id="prod_save">Сохранить</button></td></tr></table>
</span></span>
            </div>
        </div>

        </body></html>


        <?




    }

    function parseAction($action)
    {
        switch ($action):
            case 'delete': $this->delete($_GET['id']); break;
            case 'update': $this->update($_GET['id'], $_GET['name'],$_GET['type'],  $_GET['price'],$_GET['price1'],$_GET['price2'],$_GET['price3'],$_GET['price4'], $_GET['price5'], $_GET['tourid'] , $_GET['descr'], $_GET['pics']); break;
            case 'add':$this->insert($_GET['name'],$_GET['type'], $_GET['price'],$_GET['tourid'] ); break;
            case "showpjson":$this->getPJSON($_GET['id']);break;
            case 'getjson':$this->showJSON();break;
            default: $this->showDHTML();
        endswitch;

    }



}
define("IN_ADMIN", TRUE);
//require "sqli.php";
//require "newconnect-head.php";


$rt=new prod_editor($mysqli);

//$rt->showall();
$rt->parseAction($_GET['action']);

//$rt->update(40, "Амвросий Оптинский", "2016-10-23", "празднование");
//$rt->showJSON();
//$rt->insert('Серафим Саровский',5, '2017-01-15','ДЕНЬ ПАМЯТИ ПРЕПОДОБНОГО СЕРАФИМА САРОВСКОГО');
//$rt->delete(46);
?>