<?php

if(!isset($_SESSION)){
       session_start();
    }
$folderName = $_SESSION['upload_file_location'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/x-data-spreadsheet@1.1.5/dist/xspreadsheet.css">
    <script src="https://unpkg.com/x-data-spreadsheet@1.1.5/dist/xspreadsheet.js"></script>
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script></head>
<body>
    <input type="button" name="mybtn" id="myBtn" value="Save" onclick="showData()">
    <div id="xspreadsheet">
    </div>

    <script>

        var xs = x_spreadsheet('#xspreadsheet', { showToolbar: true, showGrid: true,
        showContextmenu: true,
        view: {
            height: () => document.documentElement.clientHeight,
            width: () => document.documentElement.clientWidth,
        },
        row : {len : 100000}
        });

        function showData() {
            var myData = xtos(xs.getData());
        }

        //read file
        function stox(wb) {
            var name = wb.SheetNames[0];
            var out = [];
            var o = { name: name, rows: {} };
            var ws = wb.Sheets[name];
            var aoa = XLSX.utils.sheet_to_json(ws, { raw: false, header: 1 });
            aoa.forEach(function (r, i) {
                var cells = {};
                r.forEach(function (c, j) { cells[j] = ({ text: c }); });
                o.rows[i] = { cells: cells };
            })
            out.push(o);
            return out;
        }

        //download file
        function xtos(sdata) {
            var out = XLSX.utils.book_new();
            var xws = sdata[0];
            var aoa = [[]];
            var rowobj = xws.rows;
            for (var ri = 0; ri < rowobj.len; ++ri) {
                var row = rowobj[ri];
                if (!row) continue;
                aoa[ri] = [];
                Object.keys(row.cells).forEach(function (k) {
                var idx = +k;
                if (isNaN(idx)) return;
                aoa[ri][idx] = row.cells[k].text;
                });
            }
        sendJson(aoa);
        }

        var $filename = '';
        function processExcel(filename) {
            $filename = filename;
            var req = new XMLHttpRequest();
            let url = "<?php echo $folderName; ?>" + filename;
            req.open("GET", url, true);
            req.responseType = "arraybuffer";

            req.onload = function (e) {

            var data = new Uint8Array(req.response);
            var workbook = XLSX.read(data, { type: "array" });
            xs.loadData(stox(workbook));
            }

            req.send()
        }

        function sendJson(data) {
        //var element = document.getElementById("excelSpinner");
        // element.classList.add("loader");
        $.post("afterSaveEditFile.php",
        {
            myjson: JSON.stringify(data),
            filename: JSON.stringify($filename)
        })
        .done(function (result, status, xhr) {
            //element.classList.remove("loader");
            console.log("success");
        })
        .fail(function (xhr, status, error) {
            //element.classList.remove("loader");
            console.log("fail");
        });
        }

        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        processExcel(params.file);
    </script>
</body>
</html>