<?php

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 24.07.2017
 * Time: 8:39
 */
class Photo
{
    function __construct()
    {

    }

    function getTourPhotoName($type, $id)
    {
        $sq = "select name as source, supername as super, thumbname as thumb, galname as gallery,id from photos where id=$id";
        $rs = db::query2($sq);
        $res = "";
        if ($rs) {
            $q2 = $rs->fetch(PDO::FETCH_ASSOC);
            if ($q2 && $q2[$type]) $res = $q2[$type];
        }
        return $res;
    }

    function getTourPhotoAllName($type, $id)
    {

    }

    function getBase64Photo($name)
    {
        $data = file_get_contents(main::picDirectoryRoot() . $name);
        $res = "data:image/png;base64," . base64_encode($data);
        return $res;
    }

    function generateFileName($id, $type)
    {
        main::logVar(json_encode($type));
        $res = "pic__file_" . $type . "_" . rand(0, 9) . rand(0, 9) . rand(0, 9) . "_" . $id;
        main::logVar($res);
        return $res;

    }

    function updateFileName($id, $type, $filename)
    {
        main::logVar("SAVEFILE:" . $id . " " . $type . " " . $filename);
        $sq = "";
        if ($type == "thumb") $sq = "update photos set thumbname='$filename' where id=$id";
        if ($type == "gallery") $sq = "update photos set galname='$filename' where id=$id";
        if ($type == "source") $sq = "update photos set name='$filename' where id=$id";
        if ($type == "super") $sq = "update photos set supername='$filename' where id=$id";
        if ($sq != "") {
            $res = db::query2($sq);
            main::logVar($sq);
            main::logVar(json_encode($res));
        } else $res = -100;
        return $res;
    }


    function addUpdateBase64Photo($req)
    {
        $res = -100;

        main::logVar(json_encode($req));
        if (isset($req["file"]) && isset($req["type"])) {
            $file = $req["file"];
            $type = $req["type"];
            $tourid=0;
            $placeid=0;
            if (isset($req["tourid"])) $tourid = $req["tourid"];
            //if (isset($req["id"])) $id = $req["id"];
            if (isset($req["placeid"])) $placeid = $req["placeid"];
            //main::logVar(json_encode($file));
            main::logVar(json_encode($type));
            $filename = "";
            try {
                if (!isset($req["id"]) || intVal($req["id"]) == 0)//НОВОЕ ИЗОБРАЖЕНИЕ
                {

                    if (isset($req["tourid"]) && intVal($req["tourid"])>0)//если привязка в туру
                    {
                        $tourid = $req["tourid"];
                        $sq = "insert into photos (tid) values($tourid)";
                        //echo($sq);
                        main::logVar($sq);
                        db::query2($sq);
                        $id = db::lastInsertId();
                        main::logVar("NEWID" . $id);
                    } else {
                        //вариант с Placeid;
                       // $placeid = $req["placeid"];
                      //  var_dump($req);
                        $sq = "insert into photos (pid) values($placeid)";
                       // echo($sq);
                        main::logVar($sq);
                        db::query2($sq);
                        $id = db::lastInsertId();
                        main::logVar("NEWIDPLACE" . $id);
                    }
                    $filename = $this->generateFileName($id, $type);

                } else /*if (isset($req["id"]) && intVal($req["id"])>0)//UPDATE(!). В БД у картинки уже есть идентификатор. Узнаем в БД имя файла и перезаписываем. Если файла вдруг нет - создаем новый. -*/ {
                    $id = $req["id"];
                    $sq = "select name as source, supername as super, thumbname as thumb, galname as gallery,id from photos where id=$id;
";
                    main::logVar($sq);
                    $req = db::query2($sq);
                    if ($req) $rm = $req->fetch(PDO::FETCH_ASSOC);
                    if (isset($rm[$type]))//Добываем запись об имени файла конкретного типа
                    {
                        $filename = $rm[$type];
                    } else {
                        $filename = $this->generateFileName($id, $type);
                    }
                }
                main::logVar("filename:" . $filename);
                $file = main::readInFile($file);
                main::writeToFile($filename, $file);
                $this->updateFileName($id, $type, $filename);
                $res = ["id" => $id, "type" => $type, "tourid"=>$tourid, "placeid"=>$placeid,"status" => "ok"];

            } catch (Exception $e) {
                main::logVar($e->getMessage());
                $res = ["status" => "nok"];//json_encode($e->getMessage());
            }

        }
        if ($res == -100) $res = ["status" => "nok", "source" => $req];
        return $res;
    }

    function delPhoto($id)
    {
        $sq = "delete from photos where id=$id";
        return db::query2($sq);
    }

    function savePhotoComment($req)
    {
        $qres=["status"=>"nok"];
        //var_dump($req);

        if (isset($req["photoid"]) && intVal($req["photoid"]>0)) {
            $comment = "";
            $sorder = 0;
            $sq = "";
            $res="";

            if (isset($req["comment"])) $res = $res . ",comment='" . $req["comment"] . "'";
            if (isset($req["sorder"])) $res = $res . ",sorder=" . $req["sorder"];

            if (strlen($res) > 0) {
                $sq = "update photos set " . substr($res, 1) . " where id=" . $req["photoid"];
                //echo($sq);
                $rs=db::query2($sq);
               $qres=["status"=>$rs];
            }

            //$sq=.($comment!=""?$comment:"").($order!=""?($comment!=""))

        }
        return $qres;
    }

}


/*
 * static function addNewPointPicture($placeid, $file)
  {
    $newfilename = "pic__file_" . rand(0, 9) . rand(0, 9) . rand(0, 9) . $placeid;
    main::writeToFile($newfilename, $file);
    $sq = "insert into photos(name, pid) values('$newfilename', $placeid)";
    $rt = db::query2($sq);
    $res = 0;
    if ($rt) $res = db::lastInsertId();
    return $res;
  }

  static function addNewTourPicture($id, $file)
  {
    $newfilename = "pic__file_" . rand(0, 9) . rand(0, 9) . rand(0, 9) . $id;
    main::writeToFile($newfilename, $file);
    $sq = "insert into photos(name, tid) values('$newfilename', $id)";
    $rt = db::query2($sq);
    $res = 0;
    if ($rt) $res = db::lastInsertId();
    return $res;
  }


 *
 * */


