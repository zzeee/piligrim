<?php

/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 30.01.2017
 * Time: 21:04
 */
class databuilder
{

    static function doMain($siteid)
    {
        $month['1'] = 'январь';
        $month['2'] = 'февраль';
        $month['3'] = 'март';
        $month['4'] = 'апрель';
        $month['5'] = 'май';
        $month['6'] = 'июнь';
        $month['7'] = 'июль';
        $month['8'] = 'август';
        $month['9'] = 'сентябрь';
        $month['10'] = 'октябрь';
        $month['11'] = 'ноябрь';
        $month['12'] = 'декабрь';
        $qr = new tours();
        $seos = new seo($siteid);
        $sro1 = $seos->getTexts("/");
        $resarr = $qr->showList();
        $tlist = $qr->getLocations($siteid);
        $toparr2 = [];
        $i = 0;
        foreach ($tlist as $tline) {
            $resdata = $qr->showLocList($tline["id"], $siteid);
            if ($resdata != 0 && count($resdata) > 0) {
                $toparr2[$i] = $resdata;
                $toparr2[$i]["title"] = $tline["title"];
                $i++;
            }
        }
        //$qrr = new toptours();
        $toplist = $qr->getList(1);//Надо переделать, тут 1 - тип тура - ПАЛОМНИЧЕСТВО
        //$pohodlist=$qr->getList(5);
        $mons = new monastery();
        $mainlist = $mons->showList(1);
        $qr = new pointsList();
        $pointsarr = $qr->getTopPoint();
        $cit=$qr->getTopMainPoint();//var_dump($cit);
        $tophotel=$qr->getTopHotels();
//var_dump($toplist);

        $params = array('testdata' => "mainok", 'toparr2' => $toparr2, 'month' => $month, 'seo' => $sro1, 'arr' => $resarr,
            'toplist' => $toplist,'tophotels'=>$tophotel, 'mainsaints' => $mainlist, 'topcities'=>$cit,'points' => $pointsarr,  'mainmenu'=>main::getTopMenu());
//var_dump($params);
        return $params;
    }

    static function getTour($siteid, $tourid, $dateid)
    {

     
    }

    static function getQuery($siteid, $index, $argument1 = 0, $argument2 = 0)
    {

        $params = [];
        switch ($index):
            case "main":
                $params=self::doMain($siteid); break;
            case "tour":
                $params=self::getTour($siteid, $argument1, $argument2);
                break;

        endswitch;
        return $params;

    }

}