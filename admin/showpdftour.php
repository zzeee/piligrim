<?php
//header("content-type:text/html; charset=utf-8");
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";


function showPdfTicket($rid)
{

    $sq='select * from my_reserves where id='.$rid;
    $sq1='select * from add_reserve_data where reserve_id='.$rid;
    $adddata="";
    global $mysqli;

    $prs=$mysqli->query($sq1);
   while($p = $prs->fetch_assoc()) {
//       $vall=$p['value'];
       $tit=iconv("utf-8", "windows-1251", $p['title']);
       $rvalue=iconv("utf-8", "windows-1251", $p['value']);
       if ($rvalue=="1") $rvalue="��";

       $adddata.='<tr ><Td style="font-size:16">'.$tit.':</Td><td style="font-size:16">'.$rvalue.'</td></tr>';
   }
   if ($adddata!="") $adddata="<table border='0' style='font-size:20'><tr><td colspan='2'>��������� �����:</td></tr>".$adddata."</table>";


//echo ($sq);
  //  echo ($adddata);
    $html = '<table border="0" width="100%">';
    try {
       // if ($res = $mysqli->query($sq)) {
       //     $rm = $res->fetch_assoc();
            //$sq2='select * from add_services'.($tourid!=""?" where tourid=".$tourid:"");
            //�������: �������� ������ ���������� � ���  ��������

            $res=$mysqli->query($sq);
            $rows = array();
            while($r = $res->fetch_assoc()) {
                $row['id'] = $r['id'];
                $row['title'] = $r['title'];
                $row['description'] = $r['description'];
                $p=$r['price'];
                if ($_GET['molodrus']=="1") $p=$r['price1'];
                if ($_GET['dealer']=="1") $p=$r['price3'];
                if ($_GET['inaction']=="1") $p=$r['price2'];
                $row['price']=$p;
                $turid = iconv("utf-8", "windows-1251", $r['turid']);
            $tur = getTourDataById($turid);
                $turtitle=iconv("utf-8", "windows-1251", $tur['title']);

                $turdate = iconv("utf-8", "windows-1251", $r['turdate']);
            $turstr= iconv("utf-8", "windows-1251",getDateById($turdate));

                $price=$r['price'];
                $syst=$r['sourcesyst'];
                $ctype=$r['ctype'];
                $rtext=$syst;
                $pr=$r['payed'];
                //   echo ($price."!".$rtext."!".$syst."!".$pr);
                if ($syst=='biglion') $rtext='������������ ������ ��� ������������ ������ Biglion';
                if ($syst=='groupon') $rtext='������������ ������ ��� ������������ ������ Groupon';
                if ($syst=='kupikupon') $rtext='������������ ������ ��� ������������ ������ Kupikupon';
                $udata=getClientData($r['uid']);

                $pr=$r['payed'];
                $fio=iconv("utf-8", "windows-1251", $r['fio']);


                $badge="<tr><td colspan='3' align=center><span style='font-size:22'>".$fio."</span></td></tr>";
                $badge=$badge.'<Tr><Td rowspan=2 valign=top><img src="/myqr.php?id=http://www.nov-rus.ru/index.php?action=my---rid='.$rid.'--action=my" /></td>';
                $badge=$badge.'<td valign=center colspan=2><br />'.$turtitle." ".$turstr."<br /> ".$r['uid']."---".$rid."---".$ctype.'</td></tr>';
                $badge=$badge."<tr><Td colspan=2 align=bottom valign=bottom align=left><img src='/imgi/logo-big.png' width=200 /></td></tr>";
                $badge="<span ><table style='padding:5px;  border:thin solid black' border=0 >".$badge."</table></span>";

                $html=$html."<tr><Td><br /><br />���������� ����� � �������� � ����� � �������:".$badge.'</td></tr>';

                //$html=$html."<tr><td align='center'><img src='/imgi/logo-big.png' /></td></tr>";
          //  $html=$html."<tr><Td align='center'><h1 style='font-size:30'>���������� ����� �".$r['uid']."-".$rid."</h1></td></tr>";
          //  $html = $html . "<tr><Td style='font-size:20'>���:" . $turtitle . '</td></tr>';
          //  $html = $html . "<tr><Td style='font-size:20'>����: " .$turstr. '</td></tr>';

//            $html = $html . "<tr><Td style='font-size:20'>" . iconv("utf-8", "windows-1251", $tur['main_descr']) . '<br />���������:</td></tr>';
            $html = $html . "<tr><Td style='font-size:10'><pre>" . iconv("utf-8", "windows-1251", $tur['program']) . '</pre></td></tr>';
             //$html = $html . "<tr><Td style='font-size:20'>���������� ����: " .iconv("utf-8", "windows-1251", $rm['num']). '</td></tr>';

             $html = $html . "<tr><Td style='font-size:20'>".$rtext."</td></tr>";

                $lim="<h1>������� � �����������</h1><ul>        <li>� �������� ����������� ������ ����������������, ���� ������ 18 ��� ����� ��������� ������� � �������� ������ � �������������� </li>

                <li>������� ���������� � ������������ � ����������.</li>
                <li>����� ��������� � ��������� ����� �������� ��������������� � ����� ���������� � ������������ � �������� ����������� </li>
                <li>����������� � ������ ��� ��� �������� ������������, � ����� � ��� �� �� ����� ����������� ������ ��������� �� ��������� � ��������� (�� ����������� ��������������� ���������� ��������� � ���������� ������������� ������)</li>
                <li>�� �� ����� ��������������� �� ����� ������� ��������� � �������� �����������</li>
                <li>�� �� ����� ��������������� ����� ��������� ������ � ������ ��������� ������� �������� �� ����� ��������� � ��������� ������</li>
                <li>� ������� � ������� �� ����������� ���� � ��������� ��������� � ��������� ������ </li>
                <li>����������� ������������ � ��������: �������, �����������, ������� � ������ ��������� ������� </li>
                <li>�� �� ����� ����������� �������� ��������� ��� ������� �������  � ������������ ������������ ����������� ������� </li>
                <li>�� ��������� ������� ���� �������� ������� ������ ���� ����� � �������������� ���������� ����� �/��� � ����� ������ ����� </li>
                <li>�������������� ����� ����� ���������� ���������� �������������� ������������ ��� ���������� ����������� � ������: �������� ���������, ������� � ��������, � ������ ���� ��������� ���������� ����� ������� �� ����� ��������� ��������� �������. </li>
                <li>�� ��������� �� ������ ������������� � ������������� �������.  ��� ����� ��������������� � ������������� ������� ��������� �������� �������� ��������: �������� �������� ��������, ���������� ������ ������� � ������ ������� �������� ��������: �������, ��������� ������������-��������� ��������� ������ ���. </li>
                </ul>";


            if ($pr==0) {
                $html=$html."<tr><Td style='font-size:20'>������ ���� �� �������������. ��������� ������� �� �����</td></tr> ";
            if ($price!=0)             $html = $html . "<tr><Td style='font-size:20'>� ������: " .iconv("utf-8", "windows-1251", $price). '</td></tr>';
            }
                if ($adddata!="") $html=$html."<tr><Td>".$adddata.'</td></tr>';

                //$html = $html . "<tr><Td style='font-size:20'>������� �� ���: " .$fio. '- '.$r['phone'].'</td></tr>';

                $html = $html . "<tr><Td style='font-size:20'>��������:" .iconv("utf-8", "windows-1251", $udata['name']." ".$udata['phone']).'</td></tr>';


                $html=$html."<tr><td>".$lim."</td></tr>";



                $comment=$r['comment'];

            //if ($comment!="") $html = $html . "<tr><Td style='font-size:20'>�����������: " .iconv("utf-8", "windows-1251", $comment). '</td></tr>';




        }
//        echo ($html." ".$sq);

    }
    catch (Exception $e) {echo ($e->getMessage());}
    $html = $html . "</table>";

    //echo ($html);
    $mpdf1 = new mPDF('utf-8', 'A4', '8', '', 10, 10, 1, 1, 1, 1);


    $mpdf1->charset_in = 'cp1251';
    $mpdf1->list_indent_first_level = 0;
    $mpdf1->WriteHTML($html, 2);
    $mpdf1->Output('mpdfticket.pdf', 'I');


}


function showPdfTour($id)
{
    //echo ($id);
    $sq="select * from tours where id=".$id;
    global $mysqli;
    //echo ($sq."<br/>");
    $html = '<table border="0">';
    try {
        if ($res = $mysqli->query($sq)) {
            $rm = $res->fetch_assoc();
            $dat="";
            //  $html =
            $head="";
            $dat="";

            if ($_GET['nologo']!="1") $dat.="<tr><td align='center'><img  height='500' src='/imgi/logo-big.png'/></td></tr>";
            if ($rm['type']==1) $dat=$dat.'<tr><Td align="center"><h2>���������� � ������������� �������</h2></Td></tr>';
            if ($rm['type']==2) $dat=$dat.'<tr><Td align="center"><h2>���������� � ������������� �������</h2></Td></tr>';
            //  echo ($rm['baseprice']);

            $dat=$dat."<tr><td align='center'><h1 style='font-size:60'>".iconv("utf-8", "windows-1251", $rm['title'])."</h1></td></tr>";

            $dat=$dat."<tr><td  align='center'><h2>".iconv("utf-8", "windows-1251",$rm['main_descr'])."</h2></td></tr>";
            $dat=$dat.'<tr><td align="center"><img height=400 src="img/'.$rm['mainfoto'].'"/> </td></tr>';
            $datline="";

            $sqlm = "SELECT id, comment, day(date) as day, month(date) as month, year(date) as year FROM `dates` where date>now() and year(date)=2016 and tourid=" . $id;
            //echo ($sqlm);
            $rmq = $mysqli->query($sqlm);
            $i=0;
            while ($rmdate = $rmq->fetch_assoc()) {
                $comm=iconv("utf-8", "windows-1251",$rmdate['comment']);

                $datline=$datline.($i==0?"":", ").iconv("utf-8", "windows-1251",$rmdate['day']) . "." . iconv("utf-8", "windows-1251",$rmdate['month']).($comm!=""?" -".$comm:"");

                $i++;

            }
            //echo ($datline);

            //$dat=$dat."<tr><td style='font-size:20'>����:".$datline."</td><tr>";
            $dat=$dat."<tr><td align=center style='font-size:28px;text-align:center'>23-25 �������<br /> </td><tr>";
            $dat=$dat."<tr><Td style='font-size:20'><br />������ �� ��������: 8-916-124-32-43. <br /><br />��������� � ������� �� �����: http://nov-rus.ru/index.php?action=showatour&tournumber=$id</td></tr>";

            $price=$rm['baseprice'];
            if ($_GET['molodrus']!="") $price=$rm['price1'];
            if ($_GET['dealer']!="") $price=$rm['price3'];

            if ($_GET['noprice']!="1")             $dat=$dat."<tr><td><h1 style='font-size:28px'>���������:&nbsp;".$price." ���.</h1></td></tr>";
            $dat=$dat.'<tr><td><br /><pre>'.iconv("utf-8", "windows-1251",$rm['program']).'</pre><td></tr>';

            //echo('1+1');
            //$dat= . "&nbsp;" . $rm['main_descr'];
            //$dat=convert_cyr_string($dat,"i","w");


            //$dat = iconv("utf-8", "windows-1251", $dat);
            // echo ($dat);
            $html=$html.$head.$dat."</table>";
            // echo ($html."@");

            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 1, 1, 1, 1);


            $mpdf->charset_in = 'cp1251';
            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html, 2);
            $mpdf->Output('mpdf.pdf', 'I');

        }
    }
    catch (Exception $e) {echo($e->getMessage()); }



}


if ($_GET['tournumber']!="") showPdfTour($_GET['tournumber']);
if ($_GET['bnum']!="") showPdfTicket($_GET['bnum']);



?>
