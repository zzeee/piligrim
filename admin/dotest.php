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
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.js "></script>
<script type="text/javascript"src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript"src="js/notify.min.js"></script>


<?php
//echo ($_SESSION['id']."wfw".$_COOKIE['id']);
define("IN_ADMIN", TRUE);
require "sqli.php";
require "commlib.php";
global $mysqli;
//

//showTop();
?>

    <style type="text/css">
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
        #addnewwindow: {width:400px;
            overflow:hidden;
            position:absolute;
            top:20%;
            left:40%;
            z-index:3;
            margin:40px auto 0px auto;
            height: 250px;
            padding:10px;
            background-color: white;
            border-radius:5px;
            box-shadow: 0px 0px 10px #000;
        }
    </style>

    <script type="text/javascript" >
        window.arr=new Array();
        function edit(id)
        {
            $("#eid").val(id);
            $("#ename").val(window.arr[id]['name']);
            $("#edate1").val(window.arr[id]['date1']);
            $("#edescr").val(window.arr[id]['datedescr']);
            $("#editwindow").css("visibility", "visible");

        }

        function addplace()
        {
            aname = $("#aname").val();
            atyp = $("#atyp").val();
            adate1 = $("#adate1").val();
            adescr = $("#adescr").val();
            plline="http://nov-rus.ru/places.php?action=add&name="+aname+"&type="+atyp+"&date1="+adate1+"&datedescr="+adescr;
            console.log(plline);
            $.ajax({
                url: plline,
                cache: false,
                type: "GET",
                success: function (data) {
                    console.log(data);
                    showList();
                }
            } );

        }


        function savePlace() {
            ename = $("#ename").val();
            eid = $("#eid").val();
            edate1 = $("#edate1").val();
            edescr = $("#edescr").val();
            line = "http://nov-rus.ru/places.php?action=update&id=" + eid + "&name=" + ename + "&date1=" + edate1 + "&datedescr=" + edescr;
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
                    showList();
                }
            });
        }

        function dodelete (id)
        {
            lline="http://nov-rus.ru/places.php?action=delete&id="+id;
            $.ajax({
                url: lline,
                cache: false,
                type: "GET",
                success: function (data) {
                    console.log(data);
                    showList();
                }

            } );


        }

        function showList()
        {
            //jsline2 = "";

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
                  //  console.log(data);
                    i=0;
                    //arr="";
                    a1="";
                    dat="";
                    $.each(data, function (key, val) {
                        a1=val['id'];
                        console.log(a1);
                        window.arr[a1]=val;
                      //  console.log(window.arr[a1]);

                        datt=val['date1'];
                        if (datt=='0000-00-00')datt="";
                        dat+="<tr><td><a onclick=edit("+a1+")>"+val['name']+'</td><td>'+val['type']+'</td><td>'+datt+'</td><Td>'+val['datedescr']+'</td><td><a onclick="dodelete('+a1+')">удалить</a></td></tr>';
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

$(document).ready(function() {

showList();

    $("#addb").click(function() {
//        alert('ded');
        addplace();
    });


    $("#save").click(function() {
        savePlace();
    });



                  });


</script>
</head>
<body>
<div id="tzone">
    <div id="row">
        <span id="addnewwindow" class="col-md-3" style="display:yes" >
        <h1>Добавить</h1>
   <table>
<tr><td>        Имя:</td><td> <input width="50" type="text" id="aname" /></td></tr>
        <tr><td></td>Тип</td><td><select name="atyp" id="atyp"><option value="1">Город</option><option value="2">Монастырь</option><option value="5">Святой</option></select></td></tr>
   <tr><td>Дата1:</td><td><input width="50" type="text" id="adate1" /><br /></td></tr>
   <tr><td>Описание даты:</td><td><input width="50" cols="80" type="text" id="adescr" /><Br /></td></tr>
        <tr><td><button  id="addb">Добавить</button></td></tr>
</table>

    </span>

        <span id="editwindow" class="col-md-3 editwindow" style="visibility:hidden" >
    <h1>Редактирование точки/события</h1>
    <input type=hidden id="eid" />
    <table>
            <tr><td>Имя:</td><td><input width="50" type="text" id="ename" /></td></tr>
    <tr><td>Дата 1: </td><td><input width="50" type="text" id="edate1" /></td></tr>
    <tr><td>Описание даты</td><td><input width="50" cols="80" type="text" id="edescr" /></td></tr>
    <tr><td><button id="save">Сохранить</button></td></tr></table>
</span>
    </div>
</div>
<span id="testzone" style="visibility:hidden"></span>

</body></html>