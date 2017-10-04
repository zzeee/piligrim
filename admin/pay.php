<?php
/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 29.06.2016
 * Time: 21:54
 */

$payment=$_GET['sum'];
$phone=$_GET['phone'];
$client_name=$GET['name'];
?>

<form method="POST" action="https://money.yandex.ru/eshop.xml">
    <input type="hidden" name="shopId" value="61769">
    <input type="hidden" name="scid" value="60111">

    Идентификатор клиента:<br>
    <input type=text name="customerNumber" size="64"><br><br>
    Сумма (руб.):<br>
    <input type=text name="sum" size="64"><br><br>

    Телефон:<br>
    <input type=text name="cps_phone" size="64"><br><br>

    Ф.И.О.:<br>
    <input type=text name="custName" size="43"><br><br>
    Адрес доставки:<br>
    <input type=text name="custAddr" size="43"> <br><br>
    E-mail:<br>
    <input type=text name="custEmail" size="43"><br><br>
    Содержание заказа:<br>
    <textarea rows="10" name="orderDetails" cols="34"></textarea><br><br>

    Способ оплаты:<br><br>
    <input name="paymentType" value="PC" type="radio" checked="checked"/>Со счета в Яндекс.Деньгах (яндекс кошелек)<br/>
    <input name="paymentType" value="AC" type="radio" />С банковской карты<br/>

    <input type=submit value="Оплатить"><br>

    <!--
    Ниже перечислены доступные формы оплаты.
    Перечисленные методы оплаты могут быть доступны в боевой среде после подписания Договора.
    Какие именно методы доступны для вашего Договора, вы можете уточнить у своего персонального менеджера.

    AB - Альфа-Клик
    AC - банковская карта
    GP - наличные через терминал
    MA - MasterPass
    MC - мобильная коммерция
    PB  -интернет-банк Промсвязьбанка
    PC - кошелек Яндекс.Денег
    SB - Сбербанк Онлайн
    WM - кошелек WebMoney
    WQ - Qiwi
    QP - Куппи.ру
    KV - КупиВкредит

    <input name="paymentType" value="GP" type="radio">Оплата по коду через терминал<br>
    <input name="paymentType" value="WM" type="radio">Оплата cо счета WebMoney<br>
    <input name="paymentType" value="AB" type="radio">Оплата через Альфа-Клик<br>
    <input name="paymentType" value="PB" type="radio">Оплата через Промсвязьбанк<br>
    <input name="paymentType" value="MA" type="radio">Оплата через MasterPass<br>
    <input name="paymentType" value="QW" type="radio">Оплата через Qiwi<br>
    <input name="paymentType" value="QP" type="radio">Куппи.ру<br>
    <input name="paymentType" value="KV" type="radio">КупиВкредит<br>

    Перечисление всех методов оплаты https://tech.yandex.ru/money/doc/payment-solution/reference/payment-type-codes-docpage/
    -->

    <!--
    EPS и PNG файлы яндекс.кошелька
    https://money.yandex.ru/partners/doc.xml?id=522991

    EPS и PNG других платежных методов
    https://money.yandex.ru/doc.xml?id=526421
    -->


</form>
