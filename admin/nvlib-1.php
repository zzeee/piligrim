<?php

if(!defined("IN_ADMIN")) die;

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 08.09.2016
 * Time: 3:50
 */

?>
<script type="text/javascript">


    function dopayment(id)
    {
        //alert(id);
        phone="";
        var phone=0;
        var email=0;
        var comment="";
        var paymentid=0;
        var customernum=0;
        var customername="";
        var dataarr={};
        var uarr={};

        sum="";

        $.ajax({
            url: "backdata.php?action=showures&uid=" + id+"&type=5",
            cache: false,
            type: "GET",
            async: true,
            processData: true,
            dataType: 'JSON',
            data: "",
            success: function (data) {
                var items = [];
                console.log(data);
                i=0;
                $.each(data, function (key, val) {

                    if (key=="reservedata") dataarr=val;
                    if (key=="userdata") uarr=val;
                    //console.log(key+" "+val+" "+val['id']);
                    //dataarr=val;
                });
                sum=dataarr['price'];


                str='<form method="POST" action="https://money.yandex.ru/eshop.xml">';
                str+='<input type="hidden" name="shopId" value="66217" /><input type="hidden" name="scid" value="63082" />';
                str+='<input type=hidden name="cps_phone" value="'+uarr['phone']+'" size="64">';
                str+='<input type=hidden name="custEmail" value="'+uarr['email']+'" size="64">';
                str+='<input type=hidden name="custName" value="'+uarr['fio']+'" size="64">';
                str+='<input type=hidden name="orderDetails" value="Оплата тура:'+dataarr['title']+'" size="64">';
                str+='<input type=hidden name="customerNumber"  value="'+uarr['id']+"-"+id +'" size="64">';
                str+='<input type=hidden name="sum" value="'+sum+'" size="64">';
                str+='<input type=hidden name="custName" value="'+customername+'" size="43">';
                str+='Сумма к оплате:'+sum+'<br />';
                str+='Способ оплаты:<br><br>';
                str+='<input name="paymentType" value="PC" type="radio" checked="checked"/>Со счета в Яндекс.Деньгах (яндекс кошелек)<br/>';
                str+='<input name="paymentType" value="AC" type="radio" />С банковской карты<br/>';
                str+='<input name="paymentType" value="WQ" type="radio" />Qiwi<br/>';
                str+='<input name="paymentType" value="KV" type="radio" />КупиВкредит<br/>';
                str+='<input name="paymentType" value="GP" type="radio">Оплата по коду через терминал, включая Евросеть, Связной, Сбербанк <br>';
                str+='<input type=submit value="Оплатить через Яндекс.Кассу "><br>';
                str+='</form>';

                cname="panelbaraction";
                cname2="cpanelbaraction";


                document.getElementById(cname).innerHTML=str;
                document.getElementById(cname2).style.visibility="visible";





            },
            error:function (xhr, ajaxOptions, thrownError) {

                console.log(xhr.status+" "+thrownError+" ");
            }
        });






    }


    function showServer(uid, type, utype, tourid)
    {
        str="";
        console.log("showServer"+uid+" "+type+" "+utype+" "+tourid);
        var cname;
        cname="serverspace";
        cname2="cserverspace";
        if (type==2) {

            cname="panelbaraction";
            cname2="cpanelbaraction";
        }

        if (utype==6){
            cname="ticketlist";
            cname2="allmytickets";
        }


        astr="";
        if (utype==1) astr="&type=1";
        if (utype==2) astr="&type=2";
        if (utype==3) astr="&type=3";
        if (utype==4) astr="&type=4";
        if (utype==6) astr="&type=6&tourid="+tourid;
//alert('srv');
        $.ajax({
            url: "backdata.php?action=showures&uid=" + uid+astr,
            cache: false,
            type: "GET",
            async: true,
            processData: true,
            dataType: 'JSON',
            data: "",
            success: function (data) {
                var items = [];
                console.log(data);
                i=0;
                $.each(data, function (key, val) {
//console.log("SRV: "+key + ' ' + val);
                    aline="";

                    price=val['price'];
                    //if (price==0) price="";

                    if (utype==2) aline='<td><a target=_blank href="showpdftour.php?bnum='+val['id']+'">e-билет</a></td>';
                    if (utype==4) aline='<td><a target=_blank href="javascript:dopayment('+val['id']+", "+price+')">оплата</a><span class="glyphicon glyphicon-pencil" aria-hidden=true></span><span class="glyphicon glyphicon-remove" aria-hidden=true></span></td>';

                    tourline='<a target=_blank href="index.php?action=showatour&tournumber='+val['turid']+'">'+val['title']+"("+val['day']+'.'+val['month']+'.'+val['year']+')</a>';

                    strplus="<tr><td>"+val['fio']+'</td><td>'+tourline+'</td><td>'+val['reservedate']+'</td><td>'+(price>0?price:"")+'</td>'+aline+'</tr>';
                    if (utype==6) strplus='<tr><td>'+val['fio']+'</td><td>'+tourline+'</td><td><a target=_blank href="showpdftour.php?bnum='+val['id']+'">Скачать билет</a></td></tr>';

                    str+=strplus;
                    i++;
//    console.log(str);
                });

                strhead="<table class='table'><thead><td>Фио</td><Td>Тур</td><Td>Дата резерва</td><td>Цена</td></tr>";
                strfooter="</table>";

                if (utype==6) strhead="<table class='table'><thead><td>Фио</td><Td>Тур</td><Td></td></tr>";
                if (str!="") str=strhead+str+strfooter;

                console.log(cname);


//            return str;

                document.getElementById(cname).innerHTML=str;
                if (str!="") document.getElementById(cname2).style.visibility="visible";

            },
            error: function (xhr, ajaxOptions, thrownError) {

                console.log(xhr.status+" "+thrownError+" "+uid+" "+type);
            }
        });
    }



    function showOptions()
    {
//alert('options');
        cname="panelbaraction";
        cname2="cpanelbaraction";
        if (typeof(Storage) !== "undefined") {
            phone=localStorage.getItem("phone");
            fio=localStorage.getItem("fio");
            typ=localStorage.getItem("type");
            email=localStorage.getItem("email");
            if (String(fio)=="null")fio="";
            if (String(email)=="null") email="";
            if ($typ==0) $typ="";
            str = "<table class='table'><tr><td>Телефон</td><td>" + phone + "</td></tr><tr><Td>e-mail</td><td>"+email+"</td></tr><tr><td>ФИО</td><td>"+fio+" <a href=''>изменить</a></td></tr><tr><Td>Тип</td><td>"+typ+"</td></tr>";

            str+="<tr><Td><a href=''>изменить пароль</a></td><Td></td></tr>";

            str+="</table>";
        }

        console.log(str);
        document.getElementById(cname).innerHTML=str;
        if (str!="") document.getElementById(cname2).style.visibility="visible";
    }




    function checklogin(vall)
    {
        console.log('!'+vall);
        vall=vall.replace(/^\s*(.*)\s*$/, '$1');///ПРОВЕРИТЬ(!) удаление оконечных пробелов
        cl=String(vall).length;
        rl=document.getElementById("tcom");
        if (cl<11) {
            if (rl!="null")         rl.innerHTML='<span class="label label-info">Укажите номер мобильного телефона с восьмеркой</span>';
        }
        if (cl>11) {
            if (rl!="null")         rl.innerHTML='<span class="label label-info">Стоп! Много цифр</span>';
        }
        if (cl==11) {
            $.ajax({
                url: "backdata.php?action=isexist&line=" + vall,
                cache: false,
                type: "GET",
                async: false,
                processData: true,
                dataType: 'JSON',
                data: "",
                success: function (data) {
                    var items = [];
                    console.log(data);
                    i=0;
                    $.each(data, function (key, val) {
                        console.log("IXE: "+key + ' ' + val);
                        if (key=='result') {
                            if (val == '1') {
//alert(vall);
                                text = '<span class="label label-danger">Имя занято</span>&nbsp; <a class="label label-info"  onclick="javascript:showRPassword(' + "'" + vall + "'" + ')">Напомнить?</a><br />';
                                rl.innerHTML = text;
                                goodname = false;
// alert(text);
                            }
                            if (val == '0') {
                                rl.innerHTML = '<span class="label label-success">Имя свободно</span><br />';
                                goodname = true;
                            }
                        }
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status+" "+thrownError+" "+vall);
                }
            });
        }
    }


    function doallreg()
    {

        login=document.getElementById("r-login").value;
        pwd=document.getElementById("r-pwd").value;
        email=document.getElementById("r-email").value;

//alert(login+)

        if (goodname==false) {
            $("#r-login").notify("Номер телефона уже используется");
// $(this).addClass("errtextbox");
            return ;

        }

        if (String(login).length!=11 ) {
//alert('reg');
            $("#r-login").notify("Ошибка в номере телефона");
// $(this).addClass("errtextbox");
            return ;

        }

        if (errorMail==true || email=="") {
            $("#r-email").notify("E-mail некорректен");
            return;
        }

        if (errorPass==true || pwd=="") {
            $("#r-pwd").notify("от 5 до 10 символов и пароли должны совпадать");
            return;
        }
        console.log(login.outerHTML+" "+pwd+" "+email);
        $.ajax({
            url: "backdata.php?action=register&login=" + login+"&password="+pwd+"&email="+email,
            cache: false,
            type: "GET",
            async: false,
            processData: true,
            dataType: 'JSON',
            data: "",
            success: function (data) {
                var items = [];
                console.log(data);
                i=0;
                $.each(data, function (key, val) {
                    console.log("IXE: "+key + ' ' + val);
                    if (key=='result') {
                        if (val=='0')

                            $("#regwin").notify("Ошибка при регистрации","error");

                        if (val>0) {
// $("#regwin").notify("Регистрация успешно завершена. Войдите на сайт для продолжения","success");
                            alert("Регистрация успешно завершена. Войдите на сайт для продолжения");
                            showAuth2();
// alert('успешноё'+val)

                        }

                    }

                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }

    function showReg()
    {
        console.log('reg!');
        rt='<div id="authwindow" class="col-xs-12"><div id="rpwindow" ><div class="b-container"></div><div class="reg-popup"><div  style="margin-top: 0px; width=100%">';
        rt+=' <a class="close-dialog" href="javascript: closedialog('+"'authwindow'"+')"></a>';
        rt+='<h3>Регистрация</h3>';
        rt+='<p>Укажите номер телефона и е-мейл</p>';
        rt+='<span id="tcom"></span>';
        rt+='<div  class="form-group"><input type="text" class="form-control" onkeyup="checklogin(this.value)" id="r-login" placeholder="Номер с восьмеркой (!) 89161234567"></div>';
        rt+='<div class="form-group"><input type="email" class="form-control" id="r-email" placeholder="e-mail"></div>';
        rt+='<div class="form-group"><input type="password" class="form-control" id="r-pwd" placeholder="Пароль"></div>';
        rt+='<div class="form-group"><input type="password" class="form-control" id="r-pwd2" placeholder="Пароль (еще раз)"></div>';
        rt+='<div id="notif"></div>'

        rt+='<a href="javascript:doallreg();" id="regwin" class="btn btn-info">Зарегистрироваться</a></div>';
        qrt=document.getElementById("authwindow");
        qrt.outerHTML=rt;



        $(document).on('focusout',"#r-email",function(){
            var value = $(this).val().trim();
            //   alert('echeck');
            console.log('echeck');
            /* Для этого используем регулярное выражение  */

            if (value.search(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i) != 0) {
                $(this).notify("E-mail введён не корректно", "error");
                $(this).addClass("errtextbox");
                errorMail = true;
            } else {
                $(this).removeClass("errtextbox");
                errorMail = false;
            }
        });


        $(document).on('focusout','#r-pwd',function(){
            var value = $(this).val();
            if (value.length <= 4) {
                $(this).notify("Минимум 5 символов", "error");
                $(this).addClass("errtextbox");
                errorPass = true;
            } else {
                if (value.length > 9) {
                    $(this).notify("Миксимум 10 символов", "error");
                    $(this).addClass("errtextbox");
                    errorPass = true;
                } else {
                    errorPass = false;
                    $(this).removeClass("errtextbox");
                }
            }
        });

        /* Проверяем соответствие пароля и подтверждения */
        $(document).on('focusout','#r-pwd2',function(){
            var value = $(this).val();
            if (value != $("#r-pwd").val()) {
                $(this).notify("Пароль не совпадает", "error");
                $(this).addClass("errtextbox");
                errorPass = true;
            } else {
                errorPass = false;
                $(this).removeClass("errtextbox");
            }
        });




    }




    function doretrieve()
    {

        rt=document.getElementById('retrievepwd').value;

        if (rt!="") {

            $.ajax({
                url: "backdata.php?action=retrieve&line=" + rt,
                cache: false,
                type: "GET",
                async: true,
                processData: true,
                dataType: 'JSON',
                data: "",
                success: function (data) {
                    var items = [];
                    console.log(data);
                    i=0;
                    $.each(data, function (key, val) {
                        if (key=='result') {
                            if (val==0) alert('Мы не можем найти такого пользователя');
                            if (val>='1') alert('Вам успешно выслан пароль');
                        }
                        console.log("RTR: "+key + ' ' + val);
                        if (val!=0)  closedialog("rpwindow");
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //
                    console.log(xhr.status);
                    console.log(thrownError);
                }
            });


        }
        //alert('напомина');
    }


    function showRPassword(pwf)
    {
        console.log('!rp!!'+this.name+" "+pwf);
        if (String(pwf=='undefined')) text='';
        else text=pwf;

        rt='<div id="rpwindow" ><div class="b-container"></div><div class="r-popup"><div  style="margin-top: 0px; width=100%">';
        rt+=' <a class="close-dialog" href="javascript: closedialog('+"'authwindow'"+')"></a>';
        rt+='<h3>Напоминание пароля</h3>';
        rt+='<p>Укажите номер телефона указанный при регистрации и мы вышлем вам пароль</p>';
        rt+='<div class="form-group"><input type="text" class="form-control" id="retrievepwd" placeholder="Номер с восьмеркой, например 89161234567" value="'+text+'"></div><br /><span id="tcom"></span>';
        rt+='<a href="javascript:doretrieve();" class="btn btn-info">Напомнить пароль</a>';
        qrt=document.getElementById("authwindow");

        qrt.innerHTML=rt;
        qrt.style.visibility="visible";


    }





    function showFastReg(header, text)
    {
        console.log('fastreg');

        rt='<div id="frwindow" ><div class="b-container"></div><div class="r-popup"><div  style="margin-top: 0px; width=100%">';
        rt+=' <a class="close-dialog" href="javascript: closedialog('+"'authwindow'"+')"></a>';
        rt+='<h3>'+header+'</h3>';
        rt+='<p>'+text+'</p>'
        rt+='<div class="form-group"><input type="text" onkeyup="checklogin(this.value)" class="form-control" id="phone" name="myphonenum" placeholder="Номер с восьмеркой (!) 89161234567"></div>'
        rt+='<a href="javascript:doreg(1);" class="btn btn-info">Продолжить</a></a>';
        qrt=document.getElementById("authwindow");
        console.log(qrt.innerHTML);
        //console.log();
        alert(qrt.outerHTML);

        qrt.innerHTML=rt;
        qrt.style.visibility="visible";


    }



    function doreg(id)
    {
        rt=document.getElementById("myphonenum");

        rm=rt.value;



    }

    function checkserverauth(red) {

        login = $('#e-login').val();
        pwd = $('#e-pwd').val();
        console.log(login + " " + pwd);
        if (pwd=="")  {
            alert('Укажите пароль!');
            return;
        }


        $.ajax({
            url: "backdata.php?action=checkauth&p1=" + login + "&p2=" + pwd,
            cache: false,
            type: "GET",
            async: true,
            processData: true,
            dataType: 'JSON',
            data: "",
            success: function (data) {
                var items = [];
                console.log("!"+data+"!");
                i = 0;
                uid = "";
                phone = "";
                tname = "";
                utype=0;
                $.each(data, function (key, val) {
//console.log("SRV: "+key + ' ' + val);
                    if (key == "uid") uid = val;
                    if (key == "phone") phone = val;
                    if (key == "name") tname = val;
                    if (key == "type") utype = val;
//    console.log(str);
                });
//alert(uid);

                if (parseInt(uid) > 0) {
                    $.notify("Успешный вход", "success");

                    localStorage.setItem("uid", uid);
                    localStorage.setItem("phone", phone);
                    localStorage.setItem("type", utype);
                    console.log("saved:"+uid+" "+phone+" "+tname+" "+utype);
                    localStorage.setItem("name", tname);
                    if (String(red)=="undefined") red="http://nov-rus.ru/index.php?action=my";
                    if (red!=window.location.href) window.location.href = red; else closedialog("authwindow");


                } else {
                    //              alert('Ошибка входа');
                    $.notify("Неправильное имя входа или пароль. ", "error");
                    showAuth2(red);

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                console.log(xhr.status + " " + thrownError + " " + login + " " + pwd);
            }
        });
    }




    function closeAuth()
    {
        console.log("closew");
        qrt=document.getElementById("authwindow");
        qrt.style.visibility="hidden";
    }

    function closedialog(element)
    {
        qrt=document.getElementById(element);
        console.log(element);
        console.log(qrt.innerHTML);
        console.log(qrt.outerHTML);

        qrt.innerHTML="";
    }

    function showAuth2(red)
    {
        console.log("auth"+red);
        rt='<div id="authwindow" class="col-xs-12"><div class="b-container"></div><div class="b-popup col-xs-12" ><div  style="margin-top: 0px; width=100%"><form id="logform" class="form col-md-12" method="POST" >';
        rt+=' <a class="close-dialog" href="javascript: closedialog('+"'authwindow'"+')"></a>';
        rt+='<h3>Вход в систему</h3><div class="form-group"><label class="sr-only" for="exampleInputEmail2"></label>';
        rt+='<input type="text" class="form-control" id="e-login" name="login" placeholder="Номер телефона или логин"></div>';


        rt+='<div class="form-group">';
        rt+='<label class="sr-only" for="e-pwd">Пароль</label>';
        rt+='<input type="password" class="form-control" id="e-pwd" name="pwd" placeholder="Пароль"></div>'

        rt+='<a id="entersite" onclick="checkserverauth('+"'"+red+"'"+')" style="margin-bottom: 15px;" class="btn btn-info col-md-6">Войти</a><a  style="cursor:pointer" onclick="showRPassword();return false;" id="rpassword" class="col-md-6">Напомнить пароль</a><a id="registration" style="cursor:pointer" onclick="showReg();return false;"  class="col-md-6">Регистрация</a>'
        rt+='</form></div> </div></div></div>';
        //   $('#authwindow').innerHTML=rt;
        // $('#authwindow').style.visibility="visible";
        //alert(rt);
        //console.log(rt);
        ``
        //document.write(rt);
        qrt=document.getElementById("authwindow");
        qrt.outerHTML=rt;
        //qrt.class="b-popup";
        //   document.getElementById("authwindow").style.visibility="visible";
        //console.log("Dat!!!!a");
        //qrt2.outerHTML=rt;

    }



    function sshow()
    {
        console.log("sshow");

        if (typeof(Storage) !== "undefined") {

            uid=localStorage.getItem("uid");
            ///console.log("!!!"+uid+"sshow");
            if (uid!=0 && action!="makeauth"  && action!="my" && action!="showatour") showServer(uid);
            if (action=="showatour") {
                console.log('Страница тура'+vars['tournumber']);
                showServer(uid, 0, 6, vars['tournumber']);}

            ordernum=localStorage.getItem("ordernum");

            if (ordernum<=0){
                localStorage.setItem("ordernum",0);
                return;
            }
            rt=document.getElementById("reservespace");

            //    rt=document.getElementById("reservespace");
            dat="";
            totprice=0;
            for(i=0;i<=ordernum;i++)
            {
                tid=localStorage.getItem("tourid_"+i);
                //  alert(tid+" "+tourid);
                if (tid==tourid) {
                    fio_l=localStorage.getItem("fio-l_" + i);
                    if (fio_l=="null") fio_l="";
                    opt=localStorage.getItem("opt_" + i);
                    if (String(opt)=="null") opt="";

                    price1=localStorage.getItem("totalprice_" + i);
                    totprice=totprice+parseInt(price1);
                    //opt="";
                    dat += '<tr><td>' + i + '</td><td>' + fio_l + '</td><td>' + localStorage.getItem("phones-l_" + i) + '</td><td>' + price1  + " " +localStorage.getItem("options_" + i) + '</td><td><span style="cursor:pointer" class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td><td><span   id="delete' + i + '" onclick="dodelete(' + i + ')"class="delete glyphicon glyphicon-remove" aria-hidden="true"></span></td></tr>';
                    console.log('loaded:'+("fio-l_"+i)+" to:"+localStorage.getItem("fio-l_" + i));

                }

            }
            if (dat!="") {
                // return;
                //$("#allnewreserves").style.visibility="visible";
                qq = document.getElementById("allnewreserves");
                //  console.log('tetete');
                if (String(qq) != "null") {
                    //    console.log(qq.outerHTML);

                    //console.log(rt.outerHTML);
                    qq.style.visibility = "visible";

                }

                //.
                uid = localStorage.getItem("uid");

                dat = "<table class='table'><thead><td></td><td>ФИО</td><td>Телефон</td><td>Цена, руб </td></thead>" + dat + "</table>";
                if (totprice!=0) dat += '<h3>Итого к оплате:' + totprice + ' руб.</h3>';

                phone = localStorage.getItem("phone");
                console.log(phone+" -phn, uid-"+localStorage.getItem("uid"));

                if (totprice!=0) bline="Перейти к оплате";else bline="Присоединиться";

                if (String(phone) == 'null') {

                    //alert(totprice+" "+$bline);
                    turstr=window.location.href;//"http://nov-rus.ru/index.php?action=showatour&tournumber="+vars['tournumber'];
                    dat += '<table><tr><Td><span>Ваш номер телефона (обязателен для бронирования): </span></Td><td><input type="text" id="myphonetosend" onkeyup="checklogin(this.value)" name="myphonenum" /></td></tr><tr><Td><span>Уже покупали у нас? <a onclick="showAuth2('+"'"+turstr+"'"+')" class="loginbutton"  >Вход</a></span></Td><td><span id="tcom"></span></td></tr></table>';
                    dat += '<br /><button  class="btn btn-primary"  onclick="sendToServer(3)" id="sendCtoserver">'+bline+'</button>';

                } else {
//'                                        dat += '<span>Заказ будут оформлен на пользователя с номером телефона: ' + phone + ' Если это не вы <a class="logoutbutton">Выход</a></span>';

                    dat += '<br /><button  class="btn btn-primary"  onclick="sendToServer(1)" id="sendCtoserver">'+bline+'</button>';
                }
//                                    dat+=' <progress id="progressbar" value="0" max="100"></progress>';

                rt.innerHTML = dat;
            }

        }

    }


    function sendToServer(typ)
    {
        //typ=1 - пользователь аутентифицирован. берем все из стораджа
        //typ=3 - берем информацию из поля с name=myphonenum. Пользователь не аутентифицирован
        //alert(localStorage.getItem("uid")+" "+typ);
        console.log("!!!"+typ);
        uid=0;
        if (typeof(Storage) !== "undefined")   {
            uid=localStorage.getItem("uid");
        }

        //return false;
        //return false;
        var uphone="";
        if (typ==3)   uphone=$('[name=myphonenum]').val();
        if (typ==2) uphone=document.getElementById("phones-l").value;
        if (typ==1 && (typeof(Storage) !== "undefined")  ) {
            uid=localStorage.getItem("uid");
            uphone=localStorage.getItem("phone");
        }

        //if (typ==1) uphone=localStorage.getItem("phone");
        if (String(uphone)=="undefined") uphone="";
        console.log(uphone);



        if (uphone.length!=11 && typ==2) {alert('Введите номер телефона - 11 цифр c восьмеркой , например 89161234567'); return false;}


//                                if (typeof(Storage) !== "undefined") {

        //  if (typ==1){
        //  uid=localStorage.getItem("uid");
        // console.log(uid);
        // if (String(uid)=='null' || isNaN(uid) || parseInt(uid)==0 ) {
        //  console.log('Нужна аутентификация');
        //phone=uphone=$('[name=myphonenum]').val();

        //                alert('аут');
        // showFastReg('Быстрая регистрация','Укажите ваш номер телефона, на него придет пароль от личного кабинета. ');
        //  }

        //   }


        dataarr=new Array();

        ordernum=localStorage.getItem("ordernum");
        console.log(ordernum);
        if (ordernum<=0) {
            alert('Простите, но вам нужно указать данные хотя бы одного участника поездки');
            localStorage.setItem("ordernum",0);
            return false;
        }

        console.log(ordernum);
        q=0;
        str="";
        //dataarr['ordernum']=ordernum;
        for(i=0;i<=ordernum;i++){
            tournum=localStorage.getItem("tourid_"+i);
            if (tournum==tourid) {
                //                        dat='index_'+i;
                dat1='index_'+q;
                mod='fio-l_'+ i;
                mod1='fio-l_'+q;
                mpas='fio-p_'+i;
                mpas1='fio-p_'+q;
                mphn='phones-l_'+i;
                mphn1='phones-l_'+q;
                mtur='tourid_'+i;
                mtur1='tourid_'+q;
                mturdate='tourdate_'+i;
                mturdate1='tourdate_'+q;
                moptions='options_'+i;
                moptions1='options_'+q;
                mtotal="totalprice_"+i;
                mtotal1="totalprice_"+q;
                q++;
                str += ', "' + mod1 + '":' + '"' + localStorage.getItem(mod) + '"';
                str += ', "' + mpas1 + '":' + '"' + localStorage.getItem(mpas) + '"';
                str += ', "' + mphn1 + '":' + '"' + localStorage.getItem(mphn) + '"';
                str += ', "' + mtur1 + '":' + '"' + localStorage.getItem(mtur) + '"';
                str += ', "' + mturdate1 + '":' + '"' + localStorage.getItem(mturdate) + '"';
                str += ', "' + moptions1 + '":' + '"' + localStorage.getItem(moptions) + '"';
                str += ', "' + mtotal1 + '":' + '"' + localStorage.getItem(mtotal) + '"';
                str += ', "' + dat1 + '":' + '"' + i + '"';
//                                console.log("line"+q+" "+i+" :"+mod + "=" + localStorage.getItem(mod));
            }
        }
        if (q==0) {alert('Добавьте как минимум одного участника поездки'); return false}
        str=str+',"uphone":"'+uphone+'"';
        if (typ==3) str=str+', "createuser":1';
        if (uid!=0)  str=str+',"uid":"'+uid+'"';
        str='"ordernum":'+parseInt(q)+str;
        str="{"+str+"}";
        jsona= JSON.parse(str);
        console.log(str);


        $.ajax({
            url: "backdata.php?action=sendtoserver",
            cache: false,
            type: "POST",
            async: true,
            processData: true,
            dataType: 'JSON',
            data: str,

            success:  function( data ) {
                var items = [];
                console.log("!"+data);
                $.each( data, function( key, val ) {
                    console.log('sansw '+" "+key+' '+val+" "+typ);
                    // if (key=="uid") {
                    // userid=parseInt(val);
                    // localStorage.setItem("uid",userid);
                    // }
                    // if (key=="phone") localStorage.setItem("phone", val);
                    if (parseInt(key)>0) {
                        dodelete(key);//ПОКА ПРОСТО УДАЛЯЕМ.
                        console.log(key+" "+parseInt(key)+' :deleted');}
                });
                console.log(userid);
                if (typ==1 )
                {
                    $.notify("Ваше бронирование осуществлено. Перенаправляем в личный кабинет","success");
                    window.location.href="http://nov-rus.ru/index.php?action=my";
                }
                if (typ==2) {

                    if (uid!="" && uid>0) {
                        window.location.href="http://nov-rus.ru/index.php?action=my";
                        $.notify("Ваше бронирование осуществлено. Для продолжения воспользуйтесь паролем от личного кабинета из смс сообщения","success");}
                    //
                    else {
                        showAuth2();

                    }

//                                            showServer(uid);

                }
                if (typ==3){
                    //  alert('Ваше бронирование осуществлено. Для продолжения воспользуйтесь паролем от личного кабинета из смс сообщения',"success");

                    $.notify("Ваше бронирование осуществлено. Для продолжения воспользуйтесь паролем от личного кабинета из смс сообщения.","success");

                    //$.notify("Данные для входа были вам отправлены по sms на номер: "+uphone,success);
                    sshow();
                    showAuth2();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status+thrownError);
                //console.log();
            }

        });

    }






    function saveToStorage()
    {
        var msg = "";
        var addservices = $('input:checkbox').serialize();
        tdate=$('[name=tourdate]').serialize();
        if (tdate!="") tdate=String(tdate).substr(9);
        if (typeof(Storage) !== "undefined") {
            // Code for localStorage/sessionStorage.
            //alert('qdq');
            ordernum=localStorage.getItem("ordernum");
            ordernum++;
            localStorage.setItem("ordernum",ordernum);
            //    alert(ordernum);
            fiol=document.getElementById("fio-l").value;
            fiop="";//document.getElementById("fio-p").value;
            var phonesl=document.getElementById("phones-l").value;
            if (phonesl.length!=11) {alert('Введите номер телефона - 11 цифр c восьмеркой , например 89161234567');return false;}

            //alert("fio-l_"+ordernum+"->"+fiol);
            //                localStorage.setItem("tourpair",i+"---equiv---"+tourid);

            localStorage.setItem("fio-l_"+ordernum, fiol);
            localStorage.setItem("fio-p_"+ordernum, fiop);
            localStorage.setItem("phones-l_"+ordernum, phonesl);
            localStorage.setItem("tourid_"+ordernum, tourid);
            localStorage.setItem("index_"+ordernum, ordernum);

            localStorage.setItem("tourdate_"+ordernum, tdate);
            //localStorage.setItem("opt_"+ordernum, addservices);
            localStorage.setItem("options_"+ordernum, checkedservices);
            //console.log(totalprice);
            localStorage.setItem("totalprice_"+ordernum, totalprice);
            // console.log(String(checkedservices));




        } else {
            alert('К сожалению, вам необходимо обновить браузер. Позвоните 8 499 390 18 08 для бронирования');
            // Sorry! No Web Storage support..
        }
        console.log('sav');


        return ordernum;
    }


    function dodelete(i)
    {
        ordernum=localStorage.getItem("ordernum");
        for (ii=i;ii<ordernum;ii++)
        {
            if (ii!=ordernum) newitem=ii+1;
            from_name='fio-l_'+parseInt(ii+1);
            to_name='fio-l_'+String(parseInt(ii));
            movVal=localStorage.getItem(from_name);
            console.log("del"+ii+" "+newitem+" to:"+to_name+"="+from_name+" vale:"+movVal);

            localStorage.setItem(to_name,movVal);
        }
        localStorage.removeItem('fio_l_'+ordernum);
        ordernum--;
        localStorage.setItem("ordernum",ordernum);
        sshow();
    }






</script>

