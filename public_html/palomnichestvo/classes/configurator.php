<?php
namespace Suprematik;


/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 07.12.2016
 * Time: 16:40
 *
 * configurator (id) - запустить
 *
 * -showNewConfigurator
 * -loadOrder
 * -getOrderJSON
 * -showSavedOrder
 * -saveOrder
 * -sendtoProduction
 * -getProductionLine
 *
 * coordinator (id) - запустить, id - номер заказа
 * -showOrder ; showOrderJSON
 * -putToProduction
 * -getOperations
 * -getOperation
 * -changeOperation
 * -getComponents
 * -changeOrderStatus
 *
 *
 */
class configurator
{
    var $protodata="";
    var $result="";
    var $view_client_code="";
    var $view_server_code="";
    var $view_client_data="";
    var $view_client_press="";

    function __construct($protodata)
    {
        $protodata=$this->demoMakeCode();//демо режим
        $this->protodata=json_decode($protodata, $assoc=true);
        if (isset($this->protodata["view_client_data"])) $this->view_client_data=$this->protodata["view_client_data"];
        if (isset($this->protodata["view_client_code"])) $this->view_client_code=$this->protodata["view_client_code"];
        if (isset($this->protodata["view_server_code"])) $this->view_server_code=$this->protodata["view_server_code"];
        if (isset($this->protodata["view_client_press"]))$this->view_client_press=$this->protodata["view_client_press"];
    }


    function sendtoProduction()
    {
        $request=$this->result;
        $result=[];
        if ($request["type"]==1)
        {
            $rt=$request["user_width"];
            $components[0]=array("128567",1,"");
            $components[1]=array("128567qpr",1,"{\"size\":\"128\"}");
            $components[1]=array("128567qpr",1,"{\"size\":\"$rt\"}");
            $operations[0]=array("35643",1);
            $operations[1]=array("q35643",1);
            $operations[2]=array("q35643",1);
            $result["components"]=$components;
        }
    }

    function demoMakeCode()
    {

        $arrline2[0]=["type" => "checkbox", "paramText"=>"!Хочу окунуться в источнике", "name"=>"datatest"];
        $arrline2[1]=["type"=>"input", "paramText" =>"Что интересно", "name"=>"testval"];
        $arrline2[2]=["type"=>"compound345", "paramText" =>"Еду в первый раз", "name"=>"ftime"];
        $arrline2[3]=["type" => "checkbox", "paramText"=>"!Хочу жить один в номере (+500)", "name"=>"datatestq"];
        $arrline2[4]=["commentType"=>"no"];
        $arrline2[5]=["type" => "checkbox", "paramText"=>"!Хочу жить один в номере 1(+500)", "name"=>"datatestq1"];
        $arrline2[6]=["type" => "checkbox", "paramText"=>"!Хочу жить один в номере 2(+500)", "name"=>"datatestq2"];
        $arrline2[7]=["type" => "checkbox", "paramText"=>"!Хочу жить один в номере 3(+500)", "name"=>"datatestq3"];
        $arrline2[8]=["type" => "checkbox", "paramText"=>"!Хочу жить один в номере 4(+500)", "name"=>"datatestq4"];
        $arrline["view_client_data"]=$arrline2;
        $arrline["view_server_code"]=array("type"=>"php", "code"=>'$price_retail=128;');
        $arrline["view_client_code"]=array("type"=>"text//javascript","code"=>'price_retail=<? echo($price_retail);?>;alert(price_retail);');
        $arrline["base_price"]=4846;
        $arrline["product_code"]="config1.nov-rus.ru/";
        $arrline["view_client_press"]=array("type"=>"text//javascript","code"=>'
       //   alert(this.id);
   console.log("!!!");        
       rt1=($("#suprematik_1").val());
         '
 /*
  * Внешнее API SUPREMATIK
  *
  * Получить конкретный конфигуратор (? запустить ? )
  *

/newOrder/configId+data
 /openOrder/configId
 /saveOrder/configId+data

 /getCCode/configId

 /getConfList


 /getProduceList

 /getOrderOperationList

/get


  *service.suprematik.com/user/
  *
  * service.suprematik.com/user/getConfigurator/getConfigId
  *
  * configurator.suprematik.com/getOrder
  * producer.suprematik.com/getOrder
  *
  * service.suprematik.com/getOrder
  *
  * service.suprematik.com/user/configid/puttoproduction
  *
  * service.suprematik.com/user/configid/getProductionOrder/32423
  *
  *
  *
  *
  *saveData
  * getData by
  *
  *
  * */
 
 );

        //echo(json_encode($arrline));

        return json_encode($arrline);


    }

    function makeFace()
    {
        $result=["testval"=>"буквально все", "datatest"=>"конечно!" ];
        $this->result=$result;
        return $result;
    }


    function showHTMLFace()
    {
        $totres="";



if (isset($this->view_server_code["code"])) {
    $rm = $this->view_server_code["code"];
    //echo('<pre>');var_dump($rm); echo('</pre>');
    try
    {
    eval($rm);
    }catch(Exception $e){echo($e->getMessage());echo('<pre>');var_dump($rm); echo('</pre>');}
//var_dump($rm);
    //var_dump($price_retail);
}
        if (isset($this->view_client_code["code"])) {
            $rt = $this->view_client_code["code"];
            try {
               eval("?><script type='text/javascript'>".$rt."</script>");
            } catch (Exception $e) {
                echo($e->getMessage());
                echo('<pre>');
                var_dump($rt);
                echo('</pre>');
            }
        }

        $i=0;
      $ccode="";
        if (isset($this->view_client_press['code'])) $ccode="".$this->view_client_press['code'];
 //      var_dump($ccode);
        $totres="<script type='text/javascript'>$( document ).ready(function() {
    $(\".suprematik_button\").click(function(){
        
$ccode});
});</script><table>";
       // $totres="<table>";
        $id=0;
        if (isset($this->protodata["view_client_data"]))
        foreach($this->protodata["view_client_data"] as $line)
        {
            //var_dump($line);
           // echo($i."<br />");
            $i++;
           // echo(is_object($line)."!!!");
           // if (isset($line["name"]))            echo('<tr><td>'.$line["name"]."</td></tr>");
            $result="";

            if (isset($line["type"]) && isset($line["name"]))
            {
             $typ=$line["type"];
             $nam=$line["name"];
                $paramText="";
                $price="";
                if (isset($line["paramText"])) $paramText=$line["paramText"];
                if (isset($line["price"])) $price=$line["price"];

                $id++;


                switch ($typ)
                {
                    case "input" :  $result="<tr><Td>$paramText</Td><td><input type='text'  id='suprematik_$id' name='$nam' />".($price!=""?"<script type='text/javascript'>$price</script>":"")."</td></tr>"; break;
                    case "checkbox": $result="<tr><Td>$paramText</Td><td><input  type='checkbox' class='suprematik_button'  id='suprematik_$id' name='$nam' />".($price!=""?"<script type='text/javascript'>$price</script>":"")."</td></tr>"; break;
                    //case "var":
                    //case "server": $result=eval($paramText);break;
                }

            }
            $totres=$totres.$result;

        }
$totres=$totres."</table>";
        echo($totres);

    }

    function makeDataReport($res)
    {
        if (is_array($res)) $result=$res;
        if (is_string($res))  $result=json_decode($res);

        $keys=array_keys($result);
        foreach($keys as $line)
        {
            $key=array_search($line, array_column($this->protodata,"name"));
            echo(($this->protodata["view_client_data"][$key]["paramText"]).":".$result[$line]."<br />");
        }

    }


}