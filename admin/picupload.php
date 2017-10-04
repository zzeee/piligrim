<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "commlib.php";
ini_set('display_errors','On');
error_reporting(E_STRICT|E_ALL);


class picupload
{
    public $pics;
    protected $mysqlil;




    function __construct($mysqli)
    {
        $this->mysqlil = $mysqli;
        $sq = 'select * from photos order by id desc';
        $this->pics = $this->mysqlil->query($sq);
    }


    function showall()
    {
        $dat = "";
        while ($rt = $this->pics->fetch(PDO::FETCH_ASSOC)) {
            $dat .= "<tr><td>" . $rt['title'] . "</td><td>&nbsp;" . $rt['type'] . "</td><td>&nbsp;" . $rt['price1'] . "</td><td>&nbsp;" . $rt['description'] . "</td></tr>";
        }

        if ($dat != "") $dat = "<table>" . $dat . "</table>";
        echo($dat);
    }





    function update($id, $comment, $sorder, $turid, $asid, $pid)
    {
        //echo('erqe32');
        $sq="update photos set comment='".$comment."' ";
        if ($sorder!="") $sq.=", sorder=".$sorder."";
        if ($turid!="") $sq.=", tid='".$turid."'";
        if ($asid!="") $sq.=", asid='".$asid."'";
        if ($pid!="") $sq.=", pid='".$pid."'";
        //  if ($date3!="") $sq.=", date2='".$date2."'";
        $sq.=" where id=".$id;


       //echo($sq);
        try {
            // echo("tesr".$this->mysqlil."2e32");
            $this->mysqlil->query($sq);


            //$this->mysqlil->query("update photos set asid=".$id." where id in (".$pics.")");
        }
        catch (Exception $e) {echo($e->getMessage());}
        $rt=$this->mysqlil->affected_rows;
        echo(json_encode($rt));
        //echo();


    }

    function insert($name, $type, $price, $tourid )
    {
        if ($price!="" )
        {

            $sq="insert into photos(title, type, price) values('".$name."', ".$type.",".$price.")";

            if ($tourid!="")
            {

                $sq="insert into photos(title, type, price, tourid) values('".$name."', ".$type.",".$price.",".$tourid.")";

            }

        }
        else $sq="insert into photos(title, type) values('".$name."', ".$type.")";
        // echo($sq);
        $res=0;
        if ($this->mysqlil->query($sq))
        {
            $res=$this->mysqlil->insert_id;
            echo ('1'.$res);
        }
        return $res;
    }

    function delete($id)
    {


        $sq='delete from photos where id='.$id;
        if ($this->mysqlil->query($sq)) echo ("1");

    }

    function getPJSON($id)
    {
        $sq="select * from photos where id=".$id;
        //  echo($sq);
        $rm=$this->mysqlil->query($sq);
        $rarr=array();

        while ($rt = $rm->fetch(PDO::FETCH_ASSOC)) {
            array_push($rarr, $rt);
        }

        echo(json_encode($rarr));

    }




    function showJSON()
    {
        $arr = array();
        while ($rt = $this->pics->fetch(PDO::FETCH_ASSOC)) {

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
//echo('1edwknln lkml66dw');
  //    define("IN_ADMIN", TRUE);
           // require "sqli.php";





//            require "commlib.php";
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

                var picc=function()
                {

                    picc.prototype.getUrlVars= function () {
                        var vars = {};
                        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                            vars[key] = value;
                        });
                        return vars;
                    }


                    picc.prototype.edit=function(id)
                    {



                        $("#pic_eid").val(id);
                       // console.log(window.arr[id]['asid']);
                        $("#pic_comment").val(window.arr[id]['comment']);
                        $("#pic_asid").val(window.arr[id]['asid']);
                        $("#pic_poi").val(window.arr[id]['pid']);

                        // alert($("#pic_asid").val());
                        $("#pic_tourid").val(window.arr[id]['tid']);
                        $("#pic_sorder").val(window.arr[id]['sorder']);
                        $("#name").val(window.arr[id]['name']);



                        $("#bedit").css("visibility", "visible");
                        plline="picupload.php?action=showpjson&id="+id;
                        //console.log(plline);


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

                                fname=data['name'];
                                console.log(fname);

                                $.each(data, function (key, val) {
                                    //console.log(key);
                                    fname=val['name'];
                                    if (fname!="")datastr+='<img src="/palomnichestvo/img/'+fname+'" />';

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


                                //pic1.showList();
                            }, error:function (xhr, ajaxOptions, thrownError) {

                                console.log(xhr.status+" "+thrownError+" ");
                            }
                        } );



                    }

                    picc.prototype.addpic=function()
                    {
                        id = $("#pic_pics").val();

                        tourid=$("#apic_tourid").val();
                        asid=$("#apic_asid").val();
                        comment=$("#apic_comment").val();
                        sorder=$("#apic_sorder").val();



                        if (String(tourid)=="undefined") tourid="";
                        plline="picupload.php?action=add&name="+aname+"&type="+atyp+"&price="+price+(tourid!=""?"tourid="+tourid:"") ;
                        console.log(plline);
                        alert(plline);
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
                                this.showList();
                            }, error:function (xhr, ajaxOptions, thrownError) {

                                console.log(xhr.status+" "+thrownError+" ");
                            }
                        } );

                    }


                    picc.prototype.savePlace=function () {
                        //ename = $("#prod_ename").val();
                        pics = $("#pic_eid").val();
                        //alert(pics);
                        comment=$("#pic_comment").val();
                        sorder=$("#pic_sorder").val();
                        tourid=$("#pic_tourid").val();
                        asid=$("#pic_asid").val();
                        pid=$("#pic_poi").val();
                        ///pics=$("#pic_pics").val();


                        line = "picupload.php?action=update&id=" + pics +  "&comment=" + comment + "&sorder=" + sorder + "&tourid=" + tourid + "&asid=" + asid+"&pid="+pid ;
                        console.log(line);
                        //alert(line);
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
                                //alert(this);

                                pic1.showList();
                            }
                        });

                    }

                    picc.prototype.dodelete=function (id)
                    {
                        lline="picupload.php?action=delete&id="+id;
                        $.ajax({
                            url: lline,
                            cache: false,
                            type: "GET",
                            success: function (data) {
                                console.log("DEL"+data);
                                pic1.showList();
                            }, error: function (data){console.log("!!!!!!!!!!!!!!!!!!!!!!!"+data);alert('err');}
                        } );
                    }

                    picc.prototype.showList=function()
                    {
                        //jsline2 = "";

                        param=this.getUrlVars();
                        tid=param['tid'];
                        asid=param['asid'];
                     //   alert(tid+" "+asid);
                        lline="picupload.php?action=getjson&tid="+tid+"&asid="+asid;
console.log(lline);
                        $.ajax({
                            url:lline ,
                            cache: false,
                            type: "GET",
                            async: true,
                            processData: true,
                            dataType: 'JSON',
                            data: "",
                            success: function (data) {
                                var items = [];

                                //console.log(data);
                                //console.log(data.length);
                               // alert('w');
                                window.i=0;
                                //arr="";
                                a1="";
                                dat="";
                                $.each(data, function (key, val) {
                                    window.i=window.i+1;

                                    a1=val['id'];
                                    console.log(a1);
                                    window.arr[a1]=val;
                                    console.log(i+" "+val['name']+"~");
                                    typstr="";
                                    a1=val['id'];
                                    pname=val['name'];
                                    tid=val['tid'];
                                    asid=val['asid'];
                                    picc=val['pid'];
                                    sorder=val['sorder'];
                                    comment=val['comment'];
                                    dat+='<tr><td><img onclick="javascript:pic1.edit('+a1+')" width=300 src="http://www.elitsy.ru/palomnichestvo/img/'+pname+'" id="p'+window.i+'" /></td><td><span id="s'+window.i+'"></span></td><td>'+tid+'</td><td>'+asid+'</td><td>'+sorder+'</td><td>'+picc+'</td><td>'+comment+'</td><td><a onclick="pic1.dodelete('+a1+')">удалить</a></td></tr>';

                                 //   dat+="<tr><td><a onclick=pic1.edit("+a1+")>"+val['title']+'</td><td>'+typstr+'</td><td>'+val['tourid']+'</td><td>'+val['price']+'</td><td>'+val['price1']+'</td><td>'+val['price2']+'</td><td>'+val['price3']+'</td><td>'+val['price4']+'</td><td>'+val['price5']+'</td><td><a onclick="pic1.dodelete('+a1+')">удалить</a></td></tr>';
                                });
                                if (dat!="") dat='<table class="table">'+dat+'</table>';
                                //rt=document.getElementById("testzone");
                                //rt.innerHTML=dat;
                                //console.log($("testzone").innerText);
                                $("#testzone").html(dat);
                                for (i=1;i<data.length; i++)
                                {
                                    console.log("p"+i);

                                    qrt=document.getElementById("p"+i);
                                  //  console.log(qrt);
                                   // console.log(typeof(qrt));
                                   // if (!isNaN(qrt)) console.log(qrt.naturalHeight);
                                    p1=qrt;

                                    comm=document.getElementById("s"+i);
                                    if (p1!=null) {

                                        console.log(p1.naturalHeight + " " + p1.naturalWidth);
                                        //console.log(comm.innerHTML);
                                        comm.innerHTML = p1.naturalHeight + " " + p1.naturalWidth;
                                    }
                                }
                                $("#testzone").css("visibility", "visible");
                            }

                        });

                    }

                    var iarr=new Array();
                    //console.log('конструктор');
                    this.showList();
                    $("#pic_addb").click(function() {
                        pic1.addpic();
                        $("#baddnew").css("visibility", "hidden");
                    });

                    $("#pic_save").click(function() {
//                        alert(this.name);
                        pic1.savePlace();
                    });


                    $("#closeadd").click(function() {
                        $("#baddnew").css("visibility", "hidden");
                    });

                    $("#prod_add").click(function() {
                        $("#baddnew").css("visibility", "visible");
                        //$("#baddnew").scrollTop(60);

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

                    pic1=new picc();



                });

                function uupload(file)
                {
                    /* Is the file an image? */
                    if (!file || !file.type.match(/image.*/)) return;
                    /* It is! */
                    document.body.className = "uploading";
                    console.log('d');
                    /* Lets build a FormData object*/
                    var fd = new FormData(); // I wrote about it: https://hacks.mozilla.org/2011/01/how-to-develop-a-html5-image-uploader/
                    fd.append("image[]", file); // Append the file
                    var xhr = new XMLHttpRequest(); // Create the XHR (Cross-Domain XHR FTW!!!) Thank you sooooo much imgur.com
                    xhr.open("POST", "http://dev2.elitsy.ru/uploader.php"); // Boooom!
                  //  alert('11');
                    xhr.onerror=function(data)
                    {
                        console.log(data);
                        alert('err');
                    }
                    xhr.onload = function() {
                        // Big win!
                        console.log(xhr.responseText);
                        pidarr=JSON.parse(xhr.responseText);
                        alert("Файл успешно загружен"+pidarr);
                        window.location.reload(true);
                        pic1.showList();
//pidarr=0;
                        console.log(pidarr);
                        $("#pic_pics").val(pidarr);
                        //document.querySelector("#link").href = JSON.parse(xhr.responseText).data.link;
                        //document.body.className = "uploaded";
                    }

                    //xhr.setRequestHeader('Authorization', 'Client-ID 28aaa2e823b03b1'); // Get your own key http://api.imgur.com/

                    // Ok, I don't handle the errors. An exercise for the reader.

                    /* And now, we send the formdata */
                    console.log(fd);
                    xhr.send(fd);
                    $("#baddnew").css("visibility", "hidden");

                    pic1.showList();


                }

            </script>
        </head>
        <body>

        <button id="prod_test" >Тест</button>

        <button id="prod_add"  style="position:fixed;z-index:1000">Добавить</button>

        <span id="testzone" style="visibility:hidden; position:absolute; z-index:1"></span><br />
        <div id="tzone">
            <div id="row"><span id="baddnew" style="visibility:hidden; position:fixed; top:0; left:0; width:100%; height:100%; background-color: whitesmoke ; z-index:9 ">
        <span id="addnewwindow" class="col-md-3"  style="position:absolute; left:10%; top:0%; width:400px;  height:300px; border-radius: 5px; background: white; padding: 10px">
            <span class="glyphicon glyphicon-remove" id="closeadd" aria-hidden=true style="align:right; position: absolute; right:10; top:10"></span>
        <h1>Добавить</h1>
            <!--
   <table border="1">
<tr><td>        Комментарий:</td><td> <input width="50" type="text" id="apic_comment" /></td></tr>
        <td>Номер тура (если к туру)</td><td><input width="50" type="text" id="apic_tourid" /><br /></td></tr>
   <tr><td>Номер доп. товара (если к товару)</td><td><input width="50" cols="80" type="text" id="apic_asid" /><Br /></td></tr>
   <tr><td>Sorder</td><td><input width="50" cols="80" type="text" id="apic_sorder" /><Br /></td></tr>-->
<table>
            <tr><td colspan="2"><div><button onclick="document.querySelector('#senddata').click()">Выбрать файл</button></div>
<input id="senddata" style="visibility: collapse; width: 0px;" type="file"  onchange="uupload(this.files[0])"><input type="hidden" id="pic_pics" />


        <!--<tr><td><button  id="pic_addb">Добавить</button></td></tr>-->
</table>

    </span></span>
                <span id="bedit" style="visibility:hidden; position:fixed; top:0; left:0; width:100%; height:100%; background-color: whitesmoke ; z-index:9 ">
                <span id="editwindow" class="col-md-3 editwindow"  style="background-color: white; border-radius: 10px; left:10%; width:350px; padding: 10px" >
                    <span class="glyphicon glyphicon-remove" id="closeedit" aria-hidden=true style="align:right;  position: absolute; right:0; top:0"></span>
    <h1>Редактирование </h1>
<tr><td><button id="pic_save">Сохранить</button></td></tr>
    <table>
            <tr><td>Комментарий:</td><td><input width="50" type="text" id="pic_comment" /></td></tr>
    <tr><td>Тур ид</td><td><input width="50" type="text" id="pic_tourid" /></td></tr>
    <tr><td>Товар ид</td><td><input width="50" type="text" id="pic_asid" /></td></tr>
     <tr><td>POI  id</td><td><input width="50" type="text" id="pic_poi" /></td></tr>

        <tr><td>Sorder</td><td><input width="50" type="text" id="pic_sorder" /></td></tr>
<input type=hidden id="pic_eid" />

        <tr><Td colspan="2"><span id="pics_area"></span></Td></tr>

</td></tr>
        <tr><td></td><td></td></tr>










    </table>
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
            case 'update': $this->update($_GET['id'], $_GET['comment'],$_GET['sorder'],  $_GET['tourid'],$_GET['asid'], $_GET['pid']); break;
            case 'add':$this->insert($_GET['name'],$_GET['type'], $_GET['price']); break;
            case "showpjson":$this->getPJSON($_GET['id']);break;
            case 'getjson':$this->showJSON();break;
            default: $this->showDHTML();
        endswitch;

    }



}



global $mysqli;
$rt=new picupload($mysqli);

//$rt->showall();
@$rt->parseAction($_GET['action']);

//$rt->update(40, "Амвросий Оптинский", "2016-10-23", "празднование");
//$rt->showJSON();
//$rt->insert('Серафим Саровский',5, '2017-01-15','ДЕНЬ ПАМЯТИ ПРЕПОДОБНОГО СЕРАФИМА САРОВСКОГО');
//$rt->delete(46);
?>