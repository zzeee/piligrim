<?php
//phpinfo();
define("IN_ADMIN", TRUE);
require "sqli.php";


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//echo('11');

if ($_SERVER['REQUEST_METHOD'] == "GET"){
    ?>
    <form method=post action="uploader.php" enctype="multipart/form-data">
        <input type="file" name="image[]" />
        <input type="file" name="image[]" />
        <input type="file" name="image[]" />

        <input type="submit" />
    </form>
    <?
}
//echo('222');

//echo('333');

    function getExtension($str)
{
$i = strrpos($str,".");
if (!$i) { return ""; }
$l = strlen($str) - $i;
$ext = substr($str,$i+1,$l);
return $ext;
}




function addOneFoto($name, $nnewname)
{
    global $mysqli;
    if (is_uploaded_file($_FILES[$name]["tmp_name"])) {

        if (move_uploaded_file($_FILES[$name]['tmp_name'], $nnewname)) {
            echo "Файл корректен и был успешно загружен.\n".$name." ".$nnewname;
            $mysqli->query("INSERT INTO photos(name) VALUES('$nnewname')");
            return $mysqli->lastInsertId();
        } else {
            echo "Возможная атака с помощью файловой загрузки!\n";
        }
    }
}

function gtype($typ)
{

if (strpos($typ,'jpg')>0) return 1;
if (strpos($typ,'jpeg')>0) return 1;
    if (strpos($typ,'png')>0) return 2;


}


function savetoGal($pnnewname,$udir)
{
    echo ($pnnewname.$udir."\n");


    global $uploaddir;
    $res = $udir . $pnnewname;
    $res2 = $udir . 'mini/' . $pnnewname;
    $res3 = $udir . 'gal/' . $pnnewname;

    $galwidth = 1200;
    $galheight = 600;
    $galratio = $galwidth / $galheight;

    $newwidth = 500;
    $g = gtype($res);
    //echo ($g);
    $img = "";
    try {
        if ($g == 1) {
            $img = imagecreatefromjpeg($res);
            $res2 = $res2;
        }
        if ($g == 2) {
            $img = imagecreatefrompng($res);
            $res2 = $res2;
        }
        list($width, $height) = getimagesize($res);
      //  echo (" $width $height");
//echo (get_class($img)+"$pnnewname");
        if ($width*$height>0 && is_resource($img))
        {

        $ratio = $width / $height;
        $newheight = $newwidth * $height / $width;

        // echo("$width $height 300 $newheight $res2");

                $thumb = imagecreatetruecolor($newwidth, $newheight);
            $rdata = imagecopyresampled($thumb, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            //$rdata=imagecopyresized($thumb, $img, 0, 0, 0, 0, $width, $height, 600, 2*$newheight);

                        if ($g == 1) imagejpeg($thumb, $res2, 90);
                        if ($g == 2) imagepng($thumb, $res2, 5);

//Обработка галереи
//echo("gal");

        if ($width > $galwidth || $height > $galheight)

            $thumb2 = imagecreatetruecolor($galwidth, $galheight);
            if (!isset($thumb2)) throw new Exception("не создался thumb2+ $pnnewname");
            $background = imagecolorallocate($thumb2, 255, 255, 255);
            imagefill($thumb2,0,0,$background);

            if ($width>$galwidth ||(($width>$galwidth) && ($height>$galheight)) && ($ratio > $galratio)) {
                //    1.
                $newheight=$galwidth / $width * $height;
                $newwidth=$galwidth;
                $alx=0;
                $aly = abs($galheight - $newheight) / 2;

            }
            else
                {
                    $newheight=$galheight;
                    $newwidth=$galheight/$height*$width;
                    $alx=abs($galwidth-$newwidth)/2;
                    $aly=0;

                }

if ($newwidth>0){
    echo("\n!$width x $height! =>!$galwidth x $newheight +  $alx * $aly , ratio:$ratio : $res3!!!!!");
    //($galwidth, $height * galratio;)
    $rdata = imagecopyresampled($thumb2, $img, $alx,$aly, 0, 0, $galwidth, $galheight, $width, $height);

    $sq="update photos set gal=1 where name='$pnnewname'";
    global $mysqli;
    $mysqli->query($sq);
    echo ($sq);

            if ($g==1) imagejpeg($thumb2, $res3,90);
            if ($g==2) imagepng($thumb2, $res3,5);
}

        /*
                $rdata = imagecopyresampled($thumb2, $img, 0, 0, 0, 0, 2 * $newwidth, 2 * $newheight, $width, $height);

        */
        }
    }
    catch(Exception $e){echo($e->getMessage());}
}

function addArrayFoto($name, $aname, $udir, $pnnewname)
{
    global $mysqli;
    $nnewname=$udir.$pnnewname;
    if (is_uploaded_file($_FILES[$name]["tmp_name"][$aname])) {
        //var_dump($_FILES);
        if (move_uploaded_file($_FILES[$name]['tmp_name'][$aname], $nnewname)) {
       //     echo "Файл корректен и был успешно загружен.\n".$name." ".$nnewname;
            $rt=(getImageSize($nnewname));
           // var_dump($rt);
            $sq="INSERT INTO photos(name) VALUES('$pnnewname')";
            $mysqli->exec($sq);
            //echo($sq);
           // savetoGal($pnnewname,$udir);
            $resid=$mysqli->lastInsertId();
            return $resid;

        } else {
            return -1;
        }
    }
}




function upload()
{
    global $uploaddir;
    global $mysqli;
    $resarr = array();
    $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");
    //var_dump($_FILES);
//echo(count($_FILES["image"]));
$newname1="";
    $count=0;

   /* if (isset($_GET["testmode"]) )    $testmode=$_GET["testmode"];
   // echo ($testmode."!!!!!!!!");
    if (isset($testmode)) {

        $files = scandir($uploaddir);
        foreach ($files as $file):
            echo $file .'<br>';


        //echo ($uploaddir);
        //echo('test'.$uploaddir);
        //$pnnewname="139_837472.jpg";
         //   if (isset($file))         savetoGal($file,$uploaddir);
        endforeach;
    die();
    }*/
    foreach($_FILES["image"]["name"] as $name=>$value ){
    if (is_uploaded_file($_FILES["image"]["tmp_name"][$name]) ) {
        $newname1 = "1i" . "_" . rand(1, 1000000) . strrchr($_FILES['image']['name'][$name], ".");
        $id = addArrayFoto("image", $name, $uploaddir , $newname1);
        array_push($resarr, $id);
  //      echo($id);
        $count++;
    }

    }

    echo(json_encode($resarr));

}

if (isset($_POST) &&  $_SERVER['REQUEST_METHOD'] == "POST")
{
upload();
} else echo('wefef');

//phpinfo();
?>