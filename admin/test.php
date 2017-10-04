<?

define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";



class testA
{

    var   $a;

    function __construct()

    {

        $this->a=2412;

    }


    public function show()
    {

        echo ($this->a);
    }



}



$rt=new testA;
//$rt->init();

$rt->show();

showTop();

?>
</head>
<body>
<div id="carousel-example-generic" class="col-md-6 carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
         </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="http://nov-rus.ru/img/1i39_23187.jpg" width=500 height=200 alt="...">
            <div class="carousel-caption">
                Тест 1
            </div>
        </div>
        <div class="item">
            <img src="http://nov-rus.ru/img/39_112517.jpg" width=500  height=200  alt="...">
            <div class="carousel-caption">
                Тест 2
            </div>
        </div>
        ...
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

</body>