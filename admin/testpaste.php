<html>

<head><script type="text/javascript">
        function parsePaste(str) {
            rt = document.getElementById("dataarray");
            qrrr=document.getElementById("edit-box");
            qrrr.setAttribute("style","visibility:hidden");
            qrrr.setAttribute("content-editable","false");
            //qrrr.removeAttribute("style");
            //qrrr.setAttributeremoveAttribute("border");
//alert(qrrr.outerHTML);
            //console.log(rt);
            pq = rt.getElementsByTagName("tr");
            elems = Array.prototype.slice.call(pq); // ������ elems - ������
            totalarr2 = new Array();
            tarr = new Array();
            totallength=0;
            rt = '<table border=3>';
            lincount=0;
            elems.forEach(function (elem) {
                colnum=elem.children.length;

                if (lincount==0)
                {
                    rtline='{"length":'+colnum+"}";//пишем число столбцов в ответе            //console.log(rtline);
                    totalarr2.push(JSON.parse(rtline));
                }
                if (totallength==0) totallength=colnum;
                line=(lincount>0?"<tr bgcolor=white>":"<thead bgcolor=#989898>");
                tarr2 = new Array();
                aline=new Array();
                for (ii=0;ii<colnum;ii++)
                {
                    vv=(elem.children[ii].innerHTML);
                    aline=JSON.parse('{"colon'+ii+'":"'+vv+'"}');
                    tarr2.push(aline);
                    line=line+"<td>"+vv+"</td>";
                }
                lincount++;
                line=line+(lincount>0?"</tr>":"</thead>");
                rt = rt + line;
                totalarr2.push(tarr2);
            });
            console.log(JSON.stringify(totalarr2));
            rt = rt + '</table>';

            var data = new FormData();
            data.append("json", JSON.stringify(totalarr2));
            // console.log(data);
            alert(1);
            try {
                fetch("https://192.168.0.10/andreytest/loadankdata.php",
                    {
                        method: "POST",
                        body: JSON.stringify(totalarr2)
                    })
                    .then(function (res) {
                        alert("Q");

                        return res.text();
                    })
                    .then(function (data) {
                        console.log("!+!" + data + "!+!")
                        //                      alert(3);
                    });
            }
            catch (e) {
                alert(e.message);
                console.log(e.message);
            }
//alert(2);

            rty = document.getElementById("myres");
            rty.innerHTML = rt;
        }
        window.addEventListener('load', function (e) {
            var node = document.getElementById('edit-box');
            node.onpaste = function (e) {
                //log('paste');
                if (e.clipboardData) {
                    log('event.clipboardData');
                    if (e.clipboardData.types) {
                        log('event.clipboardData.types');
                        // Look for a types property that is undefined
                        if (!isArray(e.clipboardData.types)) {
                            log('event.clipboardData.types is undefined');
                        }
                        // Loop the data store in type and display it
                        var i = 0;
                        while (i < e.clipboardData.types.length) {
                            //alert(key);
                            var key = e.clipboardData.types[i];
                            var val = e.clipboardData.getData(key);
                            if (key == 'text/html') {
                                // console.log('HTML!!');
                                console.log(key);
                                 console.log(val);
                                rt = document.getElementById("dataarray");
                                rt.innerHTML = val;
                             //   parsePaste();
                            }
                            log((i + 1) + ': ' + key + ' - ' + val);
                            i++;
                        }

                    } else {
                        // Look for access to data if types array is missing
                        var text = e.clipboardData.getData('text/plain');
                        var url = e.clipboardData.getData('text/uri-list');
                        var html = e.clipboardData.getData('text/html');
                        log('text/plain - ' + text);
                        if (url !== undefined) {
                            log('text/uri-list - ' + url);
                        }
                        if (html !== undefined) {
                            log('text/html - ' + html);
                        }
                    }
                }
                // IE event is attached to the window object
                if (window.clipboardData) {
                    log('window.clipboardData');
                    // The schema is fixed
                    var text = window.clipboardData.getData('Text');
                    var url = window.clipboardData.getData('URL');
                    log('Text - ' + text);
                    if (url !== null) {
                        log('URL - ' + url);
                    }
                }
                // Needs a few msec to excute paste
                window.setTimeout(logContents, 50, true);
            };
            // Button events

        });

        function logContents() {
            var node = document.getElementById('edit-box');
            log('Current contents - ' + node.innerHTML);
        }

        function log(str) {
//console.log(str);
            var node = document.getElementById('log-box');
            var li = document.createElement('li');
            li.appendChild(document.createTextNode(str));
            node.appendChild(li);
        }

        function clearLog() {
            var node = document.getElementById('log-box');
            while (node.firstChild) {
                node.removeChild(node.firstChild);
            }
        }
        function isArray(obj) {
            return obj && !(obj.propertyIsEnumerable('length')) &&
                typeof obj === 'object' && typeof obj.length === 'number';
        }
        ;


        function pasteIntercept(evt) {
            log("Pasting!");
        }

        //document.getElementById("editor").addEventListener("paste", pasteIntercept, false);
    </script>
</head>
<body>
<h2>Lossg</h2>
<div id="edit-box" contentEditable=true
     style="z-index:1;border:thin solid red;width:100px;height:100px"></div>
<div id="log-box" style="display:none" contentEditable=false style="border:thin solid red">---</div>
<br/>
<div id="resultbox"></div>
<div id="dataarray" style="display:none"></div>
<div id="myres" style="z-index:100"></div>

</body>