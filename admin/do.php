<h1>Добавление туров</h1>

<?php
function showAdd()
{
    ?>
    <form action="/demo/test/do.php" name="tour" method="POST" enctype="multipart/form-data">
        <input type="hidden" value="dat" name="tname"/>
        <table>
            <tr>
                <td>Название тура</td>
                <td><input type="text" width=40 name="title"/></td>
            </tr>
            <tr>
                <td>Описание тура</td>
                <td><textarea cols=80 rows=20 name="tdescription"></textarea></td>
            </tr>
            <tr>
                <td>Базовая цена</td>
                <td><input type="text" width=40 name="bprice"/></td>
            </tr>
            <tr>
                <td>Продолжительность</td>
                <td><input type="text" cols=40 name="blength"/></td>
            </tr>
            <tr>
                <td>Что входит</td>
                <td><textarea name="binclude" rows=5 cols=80/></textarea></td>
            </tr>
            <tr>
                <td>Что не входит</td>
                <td><textarea name="bexclude" rows=5 cols=80/></textarea></td>
            </tr>
            <tr>
                <td>Главное фото</td>
                <td><input type="file" name="foto"/></td>
            </tr>
        </table>
        <input type="submit"/>

    </form>
    <?
}


$mysqli = new mysqli("localhost", "zzeeee_tours", "cZ6AIhnV", "zzeeee_tours");

if ($mysqli->connect_errno) {
    printf("�� ������� ������������: %s\n", $mysqli->connect_error);
    exit();
}


echo ($_SERVER['REQUEST_METHOD']);

function AddtoBase
{
    $sql = "";
    if (isset($_POST['title'])) {
//echo($_POST['title']);
//echo($_POST['tdescription']);
//echo($_POST['bprice']);
        $sql = "insert into tours (title, description, blength, baseprice,include, exclude) values (" . "'" . $_POST['title'] . "','" . $_POST['tdescription'] . "'," . $_POST['blength'] . "," . $_POST['bprice'] ."'".$_POST['winclude']."','".$_POST['wexclude']."')";
        echo($sql);
        $res = $mysqli->query($sql);
    }

}

$sq="select * from tours";
$res2=$mysqli->query($sq);
echo ("<br />");
   while ($row = $res2->fetch_row()) {
        echo ($row[0]."&nbsp;".$row[1]."&nbsp;".$row[2]."&nbsp;".$row[3]."&nbsp;".$row[4]."<br />");
    }


    /* ������� �������������� ����� */
    $res2->close();


/* ��������� ����������� */
$mysqli->close();


?>