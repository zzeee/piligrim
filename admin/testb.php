<?php
/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 30.11.2016
 * Time: 16:46
 */

class a
{
public static $a;
public $tst;

///
//$rt="234";

public function __construct()
{
    $this->tst=&self::$a;

}

public function dso()
    {
        $this->tst='23423';
        echo('class-a');
        $rt=new b();
        return $rt;
    }


}

class b
{
public $tmt='swfwe';

//echo a::tst;
    public function __construct()
    {
        $this->tmt=&a::$a;
    }

function dod()
{
    echo('class-b-do');
    echo ($a);

}

}

$rt=new a();

$crt=$rt->dso();
$crt->dod();



