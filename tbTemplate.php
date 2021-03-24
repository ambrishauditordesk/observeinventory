<?php
include 'dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: index");
}
if(isset($_GET['wid']) && !empty($_GET['wid'])){
    $wid = trim($_GET['wid']);
}
?>
<!DOCTYPE html>
<html lang="en">
    <title> <?php echo strtoupper($_SESSION['name'] . " Dashboard"); ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom Fav icon -->
    <!-- <link rel="icon" href="img/atllogo.png" type="image/gif" sizes="16x16"> -->

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
     <style type="text/css">
        body
        {
            font-family: Arial;
            font-size: 10pt;
        }
        table
        {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }
        table th
        {
            background-color: #F7F7F7;
            color: #333;
            font-weight: bold;
        }
        table th, table td
        {
            padding: 5px;
            border: 1px solid #ccc;
        }
        #instructions{
            background-color: red !important;
        }
    </style>
</head>
<body style="overflow-y: scroll">
    <table hidden class="table table-stripped">
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>CY Beg Bal (PY)</th>
                <th>CY Final Bal</th>
                <th>Account Type</th>
                <th>Account Class</th>
                <th>Financial Statement</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7">
                    <tbody>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3" width="256">
                        <p><span style="color: #ff6600;"><strong>Trial Balance Import instructions</strong></span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">&nbsp;</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">1) Insure trial balance total is Zero&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">2) Remove the Instructions tab before uploading the trial balance&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">3) Please ensure all Columns are updated from&nbsp;<strong>A to G</strong>&nbsp;based on instructions before uploading the trial balance&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">4)&nbsp;<strong>Account Number</strong>-&nbsp; If you do not have an account number in the Client the trial balance make account number as&nbsp; "000" for all lines</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">5)&nbsp;<strong>Account Type</strong>&nbsp;- Please select one of the following options&nbsp;<strong>(Asset, Liability, Expenses , Equity, Retained Earning , Revenue)</strong></span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">6)&nbsp;<strong>Account Class</strong>&nbsp;<strong>- Please select one of the following options, or you can add a account class based on your client requirements&nbsp;</strong></span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Cost of Sales</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Current Asset</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Current Liability</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Depreciation and amortization</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">Equity</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Income Tax</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Interest Expenses, net</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Long-Term Asset</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Long-Term Liability</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Other (income)/expense</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Other operating expenses</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Provision for doubtful accounts</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Retained Earnings</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">Revenue</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Selling, general and administrative expenses</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">7)&nbsp;<strong>Financial Statement - Please select one of the following options or you can add a financial statement based on your client requirements</strong>&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">&nbsp;Income tax receivable, long-term</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Accrued expenses and other liabilities&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Accumulated Deficit</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Assets held for sale, short-term</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Benefit for income taxes</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Bonds Payable, less current maturities&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Borrowings</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">Cash&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Cost of Sales</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Current maturities of other long-term debt&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Deferred Finance Costs</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Deferred income taxes&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Deferred revenue&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Fixed Assets&nbsp;</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">Goodwill</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Income taxes payable</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Intangible assets, net</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Interest Expenses, net</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">Inventory</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Investment in Subsidiaries</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Other (income)/expense</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Other depreciation and amortization</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Other Long Term Liabilities, Net</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Other operating expenses, net</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Payables to related parties</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Prepaid expenses and other current assets&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Property, plant, and equipment, net</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Provision for doubtful accounts and notes</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Receivables from related parties&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Related Part Debt</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;">
                        <p><span style="color: #ff6600;">Revenue</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Selling, general and administrative expenses</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Total Long Term Liabilities</span></p>
                        </td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="2">
                        <p><span style="color: #ff6600;">Trade payables&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                        </td>
                        <td style="height: 35px;"><span style="color: #ff6600;">&nbsp;</span></td>
                        </tr>
                        <tr style="height: 35px;">
                        <td style="height: 35px;" colspan="3">
                        <p><span style="color: #ff6600;">Trade receivables, net&nbsp;</span></p>
                        </td>
                        </tr>
                    </tbody>
                </td>
            </tr>
        </tbody>
    </table>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- For export -->
    <script src="http://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> 
    <script src="js/custom.js"></script>
    <script>
    $(document).ready(function() {
        $("table").table2excel({ 
            filename: "TB_Template.xls"
        });
        setTimeout(() => {
            window.close();
        }, 500);
    });
    </script>
</body>