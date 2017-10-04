<!doctype html>
<html>
<head>
    <style>
        a.test {
            font-weight: bold;
        }
    </style>
    <meta charset="utf-8">
    <title>Demo</title>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script>


        $( document ).ready(function() {

            $.getJSON( "http://nov-rus.ru/export.php?uid=a61ee3ae409eda4604208362d3f1d3f1&action=getJactualtours&tid=4", function( data ) {
                var items = [];
                $.each( data, function( key1, val1 ) {
               //     alert(val1);
//                    items.push( "<li id='" + key + "'>" + key+":"+val + "</li>" );




            $.getJSON( "http://nov-rus.ru/export.php?uid=a61ee3ae409eda4604208362d3f1d3f1&action=getJtour&tid="+val1, function( data ) {
                var items = [];
                $.each( data, function( key, val ) {
                    items.push( "<li id='" + key + "'>" + key+":"+val + "</li>" );
                });

                $( "<ul/>", {
                    "class": "my-new-list",
                    html: items.join( "" )
                }).appendTo( "body" );
            });


                });

            });


        });

        $( "a" ).click(function( event ) {

            event.preventDefault();
            console.log( "wefrew" );
            $( this ).hide( "slow" );

        });

        // Your code goes here.

    </script>

</head>
<body>

</body>
</html>