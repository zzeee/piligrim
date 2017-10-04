<?php
/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 01.12.2016
 * Time: 16:19
 */

class saints
{
    var $sList;

    function __construct()
    {
        $sq='select * from saints';
        // echo($sq);
        $rt=db::query2($sq);
        //var_dump($rt);
        $res=[];
        foreach($rt as $rm){
            array_push($res, $rm);
            //echo($rm["title"]);
        }
        $this->sList=$res;
    }

    function getList()
    {
        return $this->sList;

    }

    function getOne($id)
    {
        $key = array_search($id, array_column($this->sList, 'id'));
        //echo($key."!!!!!!!---!!!");
        return $this->sList[$key];
    }

}