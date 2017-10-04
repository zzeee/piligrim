<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 06.12.2016
 * Time: 15:24
 */
class products
{
    var $prodList;

    function __construct()
    {
        $sq='select * from add_services';
        // echo($sq);
        $rt=db::query2($sq);
        //var_dump($rt);
        $res=[];
        foreach($rt as $rm){
            array_push($res, $rm);
            //echo($rm["title"]);
        }
        $this->prodList=$res;
    }

    function getList()
    {
        return $this->prodList;

    }

    function getOne($id)
    {
        $key = array_search($id, array_column($this->prodList, 'id'));
        //echo($key."!!!!!!!---!!!");
        return $this->prodList[$key];
    }



}