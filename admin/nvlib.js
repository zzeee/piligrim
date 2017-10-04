///УДАЛИТЬ(!)


function showServer(uid, type)
{
    str="";
    console.log("SERVER"+uid+" "+type);
    var cname;
    cname="serverspace";
    cname2="cserverspace";
    if (type==2) {

        cname="panelbaraction";
        cname2="cpanelbaraction";
    }
    //alert('srv');
    $.ajax({
        url: "backdata.php?action=showures&uid=" + uid,
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
                status=val['status'];
                if (status==0) status="";
                str+="<tr><td>"+val['title']+'</td><td>'+val['day']+'.'+val['month']+'.'+val['year']+'</td><td>'+val['fio']+'</td><td>'+status+'</td><td><a target=_blank href="showpdftour.php?bnum='+val['id']+'">e-билет</a></td></tr>';
                i++;
            //    console.log(str);
   });
            if (str!="") str="<table class='table'><thead><td>"+i+" id</td><td>Тур</td><Td>&nbsp;&nbsp;дата</td></tr>"+str+"</table>";
            console.log(cname);


//            return str;
            document.getElementById(cname).innerHTML=str;
            document.getElementById(cname2).style.visibility="visible";
        },
        error: function (xhr, ajaxOptions, thrownError) {

            console.log(xhr.status+" "+thrownError+" "+uid+" "+type);
        }
    });
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
    console.log('reg');
    rt='<div id="rpwindow" ><div class="b-container"></div><div class="reg-popup"><div  style="margin-top: 0px; width=100%">';
    rt+=' <a class="close-dialog" href="javascript: closedialog('+"'authwindow'"+')"></a>';
    rt+='<h3>Регистрация</h3>';
    rt+='<p>Укажите номер телефона и е-мейл</p>';
    rt+='<span id="tcom"></span>';
    rt+='<div  class="form-group"><input type="text" class="form-control" onkeyup="checklogin(this.value)" id="r-login" placeholder="Номер с восьмеркой (!) 89161234567"></div>';
    rt+='<div class="form-group"><input type="email" class="form-control" id="r-email" placeholder="e-mail"></div>';
    rt+='<div class="form-group"><input type="password" class="form-control" id="r-pwd" placeholder="Пароль"></div>';
    rt+='<div class="form-group"><input type="password" class="form-control" id="r-pwd2" placeholder="Пароль (еще раз)"></div>';
    rt+='<div id="notif"></div>'

    rt+='<a href="javascript:doallreg();" id="regwin" class="btn btn-info">Зарегистрироваться</a>';
    qrt=document.getElementById("authwindow");
    qrt.innerHTML=rt;



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
   // console.log(qrt.innerHTML);
    qrt.innerHTML="";
}

function showAuth2()
{
    console.log("auth");
    rt='<div id="authwindow" ><div class="b-container"></div><div class="b-popup" ><div  style="margin-top: 0px; width=100%"><form id="logform" class="form col-md-12" method="POST" >';
    rt+=' <a class="close-dialog" href="javascript: closedialog('+"'authwindow'"+')"></a>';
    rt+='<h3>Вход в систему</h3><div class="form-group"><label class="sr-only" for="exampleInputEmail2"></label>';
    rt+='<input type="text" class="form-control" id="e-login" name="login" placeholder="Номер телефона или е-мейл"></div>';


    rt+='<div class="form-group">';
    rt+='<label class="sr-only" for="e-pwd">Пароль</label>';
    rt+='<input type="password" class="form-control" id="e-pwd" name="pwd" placeholder="Пароль"></div>'

    rt+='<a id="entersite" onclick="alert('+'"'+'tere'+'"'+');" style="margin-bottom: 15px;" class="btn btn-info col-md-6">Войти</a><a  style="cursor:pointer" onclick="showRPassword();return false;" id="rpassword" class="col-md-6">Напомнить пароль</a><a id="registration" style="cursor:pointer" onclick="showReg();return false;"  class="col-md-6">Регистрация</a>'
    rt+='</form></div> </div></div></div>';
    //   $('#authwindow').innerHTML=rt;
    // $('#authwindow').style.visibility="visible";
//alert(rt);
    console.log(rt);

    //document.write(rt);
    qrt=document.getElementById("authwindow");
    qrt.outerHTML=rt;
    //qrt.class="b-popup";
    //   document.getElementById("authwindow").style.visibility="visible";
    console.log("Dat!!!!a");
    //qrt2.outerHTML=rt;

}



function sshow()
{
    if (typeof(Storage) !== "undefined") {

        uid=localStorage.getItem("uid");
        console.log("!!!"+uid);
        if (uid!=0 && action!="makeauth"  && action!="my") showServer(uid);

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
        if (dat!="")
        {
            document.getElementById("allnewreserves").style.visibility="visible";
            uid=localStorage.getItem("uid");

            dat = "<table class='table'><thead><td></td><td>ФИО</td><td>Телефон</td><td>Цена, руб </td></thead>" + dat + "</table>";
            dat+='<h3>Итого к оплате:'+totprice+' руб.</h3>';

            phone=localStorage.getItem("phone");


            if (String(phone)=='null' ) {
                dat += '<table><tr><Td><span>Ваш номер телефона (обязателен для бронирования): </span></Td><td><input type="text" id="myphonetosend" onkeyup="checklogin(this.value)" name="myphonenum" /></td></tr><tr><Td><span>Уже покупали у нас? <a onclick="showAuth2()" class="loginbutton"  >Вход</a></span></Td><td><span id="tcom"></span></td></tr></table>';
            }else
                dat+='<span>Заказ будут оформлен на пользователя с номером телефона'+phone+'</span>';

            dat+='<br /><button  class="btn btn-primary"  onclick="sendToServer(1)" id="sendCtoserver">Перейти к оплате</button>';



            rt.innerHTML=dat;

        }

    }
}

function sendToServer(typ)
{
 //alert(localStorage.getItem("uid")+" "+typ);
    console.log("!!!"+typ);
    //return false;
    //return false;
    var uphone="";
    if (typ==2) uphone=document.getElementById("phones-l").value;
    else uphone=$('[name=myphonenum]').val();
    if (String(uphone)=="undefined") uphone="";
    console.log(uphone);



    if (uphone.length!=11 && typ==2) {alert('Введите номер телефона - 11 цифр c восьмеркой , например 89161234567'); return false;}


    if (typeof(Storage) !== "undefined") {
        if (typ==1){
            uid=localStorage.getItem("uid");
            console.log(uid);
            if (String(uid)=='null' || isNaN(uid) || parseInt(uid)==0 ) {
                console.log('Нужна аутентификация');
                phone=uphone=$('[name=myphonenum]').val();

//                alert('аут');
               // showFastReg('Быстрая регистрация','Укажите ваш номер телефона, на него придет пароль от личного кабинета. ');
            }

        }


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
                console.log("line"+q+" "+i+" :"+mod + "=" + localStorage.getItem(mod));
            }
            //  dataarr[mod]=rt;

        }

        //uphone=document.getElementsByName("name").val;
        // console.log(uphone);
        if (q==0) {alert('Добавьте как минимум одного участника поездки'); return false}


        str=str+',"uphone":"'+uphone+'"';
        str='"ordernum":'+parseInt(q)+str;

        str="{"+str+"}";
        console.log(str);

        jsona= JSON.parse(str);
        //              console.log(jsona);
        //  strInForm=JSON.stringify(json);
        //            console.log('test');



        $.ajax({
            url: "backdata.php?action=sendtoserver",
            cache: false,
            type: "POST",
            async: false,
            processData: true,
            dataType: 'JSON',
            data: jsona,
            success:  function( data ) {
                var items = [];
                console.log(data);
                $.each( data, function( key, val ) {
                    console.log('todel '+parseInt(key)+" "+key+' '+val);
                    if (key=="uid") {
                        userid=parseInt(val);
                        localStorage.setItem("uid",userid);

                    }
                    if (key=="phone") localStorage.setItem("phone", val);




                    if (parseInt(key)!=0) {dodelete(key);//ПОКА ПРОСТО УДАЛЯЕМ.
                        console.log(key+' :deleted');}
                });
                console.log(userid);
                showServer(userid);

                //items.push( "<li id='" + key + "'>" + val + "</li>" );

            },

            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
            }


        });




        //  console.log(dataarr['ordernum']);


    }


}



function getUrlVars() {
    var vars = {};

    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {

        vars[key] = value;

    });

    return vars;

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
