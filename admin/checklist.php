<?php 
include '../dbconnection.php';
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("Location: ../login");
}

if (isset($_SESSION['logged_in_date']) && !empty($_SESSION['logged_in_date'])){
    $currentDate = date_create(date("Y-m-d H:i:s",strtotime(date_format(date_create("now", new DateTimeZone('Asia/Kolkata')), "Y-m-d H:i:s"))));
    $loggedInDate = date_create(date("Y-m-d H:i:s",strtotime($_SESSION['logged_in_date'])));
    $diff=date_diff($currentDate,$loggedInDate);
    if($diff->format("%a") > 1 || $diff->format("%m") > 1 || $diff->format("%y") > 1){
        header('Location: ../logout');
    }
}
if(isset($_GET['process_id'])){
    $process_id=$_GET['process_id'];
}

?>
<!DOCTYPE html>
<html lang="en" style="visibility: visible;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="icon" href="https://ksacademy.co.in/images/chartered_accountants/ca.png">
<title> <?php echo strtoupper($_SESSION['name'] . " Dashboard"); ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<!-- Custom fonts for this template-->
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
<link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.css">

<!-- Custom styles for this template-->
<link href="../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../css/custom.css" rel="stylesheet">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/uiux.css" rel="stylesheet" type="text/css">
<style>
    .modal {
        overflow-y: auto !important;
    }
    </style>
    <!-- JQuery CDN -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    
    <!-- Datatable CDN -->
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.24/features/searchHighlight/dataTables.searchHighlight.min.js"></script>
    <script src="https://bartaz.github.io/sandbox.js/jquery.highlight.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
    <style>
        ::placeholder {
                color: grey;
        }
    </style>

<body style="overflow-y: scroll ;overflow-x: hidden" oncontextmenu="return true">
    <nav class="navbar sticky-top navbar-expand-lg navbar-mainbg border-bottom">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
       
            
           
            <li class="nav-item d-flex" style="background-color: rgba(232,240,255,1); border-radius: 15px;">
                <label class="d-flex justify-content-center align-items-center mt-2"></label>
                <span class="nav-icon d-flex align-items-center" style="padding: 0 0 0 10px !important;">
                    <?php
                        $img_query = $con->query("SELECT * FROM user WHERE id = ".$_SESSION['id']." and img != ''");
                        if($img_query->num_rows > 0){
                            $row = $img_query->fetch_assoc();
                            ?>
                            <img class = "profilePhoto" src="../images/<?php echo $row['img']; ?>">
                            <?php
                        }
                        else{
                            ?>
                            <i class="fas fa-user-circle fa-2x" aria-hidden="true"></i>
                            <?php
                        }
                        
                    ?>
                </span>
                <a class="nav-link d-flex align-items-center" href="#" id="userDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>
                        <?php echo $_SESSION['name']; ?>
                        <img class="nav-icon" src="../Icons/Group 6.svg" style="width:15px !important;"/>
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="font-size:small;">
                
                            <a class="dropdown-item" href="loginLog"><i class="fas fa-list"></i>Login Log</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                       
                            <a class="dropdown-item" href="#"><i class="fas fa-user-tie hue" style="color:blue;"></i><?php echo $_SESSION['name']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-signature hue" style="color:blue;"></i><?php echo $_SESSION['signoff']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-at hue" style="color:blue;"></i><?php echo $_SESSION['email']; ?></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-briefcase hue" style="color:blue;"></i>Firm Name -<?php echo $_SESSION['firm_details']['firm_name']; ?></a>
                          
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#photoModal"><i class="fas fa-user-circle hue" style="color:blue;"></i>Update Profile Photo</a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="sidenav">
        <div class="side-header">
            <!-- <div style="border-bottom:1px solid;"> -->
            <div>
                <img class="sidenav-icon" src="../Icons/Group-1.jpeg"/> &nbsp;
               
            </div>
        </div>
        <div class="side-footer"style="visibility: visible;">
            <div class="side-body">
                <div class="dash">
                    <img class="sidenav-icon" src="../Icons/pie-chart.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                    Dashboard
                    </svg>
                </div>
            </div>
            <div class="settings">
                <div class="settings-items-top-div">
                    <div class="settings-items d-flex justify-content-between align-items-center">
                        <a href="../settings" class="text-decoration-none">
                            <img class="sidenav-icon" src="../Icons/settings.svg" style="width:24px !important; height:24px !important;"/> &nbsp;Settings
                        </a>
                        <!-- <label class="d-flex justify-content-center align-items-center mt-2"><span class="helpDesign help_7">7</span></label> -->
                    </div>
                    <div id="helpButton" class="settings-items">
                        <a href="#" class="text-decoration-none"><img class="sidenav-icon" src="../Icons/help-circle.svg" style="width:24px !important; height:24px !important;"/> &nbsp;
                        Help</a>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <a href="../logout"><button type="button" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
                </div>
            </div>
        </div>
    </div>

<div class="mar">
<html>
<head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<style>
table, th, td {
  border:1px solid black!important;
}

</style>
<body>
    <div class="form_box">
        <div class="container">
          

        <h4 class='text-center'>BELOW IS THE LIST OF PROCEDURES TO BE REFLECTED UPON BEFORE CONCLUDING THE AUDIT</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">should the audit team reassess the amounts used for materiality</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">All misstatements corrected or uncorrected were communicated  to the client</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td>
    <td>
    <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">Any issues related to going concern noted</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td>
    <td>
    <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">Any issues noted during the audit that require reassessing the control environment</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td>
    <td>
    <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">Based on field work performed should audit team reassess significant risks,including risks of material misstatements due to fraud</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td>
    <td>
    <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">Were any misstatements noted that may be indicative of fraud</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td>
    <td>
    <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">Did the audit team perform overall analytical review</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td>
    <td>
    <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>INTERNAL CONTROL VERIFICATION</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Did the audit team checked processing pattern of company / firm from purchase order placed by company to actual delivery to its customer</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Were there too much reversal of accounting entries, then companies internal controls in book keeping are weak, higher chances of accounting error</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">procedure followed by company to confirm balances due to / from parties</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">Were any issues noted related to complying with other statutes such as Labour laws, MSME Act etc,wherever it required to</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>DID THE AUDIT TEAM PERFORMED VOUCHING FOR BELOW</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">cash vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">bank vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">cash verification</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">bank reconciliation statement</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">purchase vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">sale vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">journal vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>BALANCE SHEET</h4>
<h5>SHARE CAPITAL</h5>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">was any resoulution pass for increase in share capital how many no. of share issued</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">verify whether the share capital changes are there and whether the changes are authorized under proper resolution</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">In case of secured loan whether the loans have been issued under proper sanction and written representation from banks and confirmation of balances from banks</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">In case of balance sheet proper disclosure between the secured  and unsecured loans should be done and document evidencing the receipt of the loan should be taken</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">Did the team verified deprecation on assets as per company act and income tax act after considering additions & deletions of assets</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">Whether fixed asset has been acquired from outside india and the rate of exchange  changes after acquisition,the increase/decrease in the liability of the company for</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">In case of companies other than investment companies or banking companies, whether any of the shares,debentures or securities were sold at a price less than their</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>DID THE AUDIT TEAM PERFORMED VOUCHING FOR BELOW</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">cash vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">bank vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">cash verification</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">bank reconciliation statement</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">purchase vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">sale vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">journal vouching</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>

<h4 class='text-center'>PROFIT AND LOSS</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Was classification between sales and services  properly recorded</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Verify the various statutory dues such as GST,VAT,TDS,TCS which as amore connection with sales and services and various periodic returns showing the payment due</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">Concentrate more on delivery dates and also on deliveries exceeding more than one month. That results delay in delivery</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">In the case of purchases was verification done whether the GST has been accounted separately GST input tax credit account </td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">In the case of preliminary expenses did the team ensured treatment showing whether it is capitalized within five years</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">Minutes of the meeting should be verified  showing the any resolutions for capitalization of expenses, managerial remuneration, loans, approving donations(Especially 50,000.00)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">In case of foreign agency commission expense change in foreign exchange fluctuation was accounted properly</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>8</td>
  <td colspan="6" style="text-align: left;">Any income from investment i.e. interest dividend should be check bank account was properly recorded</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>9</td>
  <td colspan="6" style="text-align: left;">Audit team verified valuation of closing stock whether closing stock valuation as per accounting standard-2</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>STATUTORY COMPLIANCES</h4>
<h5>GST COMPLIANCES</h5>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>OUTPUT-GST</td>
    <td colspan="6" style="text-align: left;"></td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Was invoice series for new financial year started from new series</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Team checked whether the nature of tax levied is as per destination of goods</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">HSN /SAC wise recording of items is required for efficient book keeping. To report in ICFR,if not maintained</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">To ascertain whether tax deposited on liability arise under reverse charge mechanism (RCM)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">Reviewing E-way bills in relation to invoices issued,wherever required</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">Monitor the credit notes issued and there effect in GST returns. it is important to know the reason for issuing of credit note</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">In case of zero rated supply, GST has been paid under letter of undertaking, payment should be booked by debting GST refundable</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
    <td>INPUT-GST</td>
    <td colspan="6" style="text-align: left;"></td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Check whether invoice received in B2C or B2B</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Check whether expenses claimed eligible for input tax credit</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">Ensure that the invoice against which ITC claimed have been paid within 180 days from the date of invoice (aging clause)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">If non-GST invoice received then check the number and volume of invoices received from the same vendor during the year, if volume is high then confirm the applicability</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">Reconcile monthly balance in E-ledger with books</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>

<tr>
    <td>GENERAL VERIFICATION</td>
    <td colspan="6" style="text-align: left;"></td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>

<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Reconcile taxable outward supplies and tax liability thereon with GSTR 3B and GSTR-1</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Reconcile input tax credit availed as per books with GSTR-3B. Also to reconcile ITC withj GSTR-2A, This exercise should be done quarterly, otherwise to be reported in</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
</table>
<h4 class='text-center'>TDS COMPLIANCES</h4>
<div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>TAX PAYABLE</td>
    <td colspan="6" style="text-align: left;"></td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Check whether tax deducted at source under respective head</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Tax should be deducted on payment where advance paid to vendor</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">To reconcile the books with challans and returns</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
    <td>TAX RECEIVABLE</td>
    <td colspan="6" style="text-align: left;"></td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">form 26AS to be match with Form 16A, Mandatory</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Reconcile income and TDS thereon with 26AS</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>ROC COMPLIANCES</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Form ADT-1(For appointment of auditor)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Form AOC-4(Filling of statement of accounts)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">Form MGT-7(Filling of annual returns)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">Form MGT-14(Filling of resolution and agreement of ROC)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">Form CRA-4(Filling of cost auditor report, wherever applicable)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">INC-22(Director KYC)</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">DPT-3</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>8</td>
  <td colspan="6" style="text-align: left;">MSME compliance form</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>OTHER COMPLIANCES</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">If the company pays dividend to its shareholder than liability of dividend distribution tax arise. check payment as per prescribed rate and also to ascertain interest paid</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Deduction and Remittance of provident FUND & ESI</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>

<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">Deduction and Remittance of professional tax</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">Deduction/collection and Remittance of other taxes</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>OTHER VERIFICATION</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Company / Firm has not paid cash in excess of Rs.10,000.00</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Company / Firm has not received cash in excess of Rs.2,00,000.00 in violation of section 269ST of income tax act,1961</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>

<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">If the company / firm received cash in excess of Rs50,000.00 in certain cases, it have to receive PAN number of payer</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'>LOAN / ADVANCES GRANTED OR TAKEN</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     
         <table class="table">
  <thead class="table-primary">
  <tr>
    <td>A</td>
    <td colspan="6" style="text-align: left;">Description</td>
    <td>Yes</td>
    <td>No</td>
    <td>N/A</td>
    <td></td>
</tr>
</thead>
<tr>
  <td>1</td>
  <td colspan="6" style="text-align: left;">Loan / Advances are to be checked with due care,whether the same are permitted by companies act, 2013 and income tax act,1961</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>2</td>
  <td colspan="6" style="text-align: left;">Section 185,186 and 73 to 76 of companies act,2013 are necessarily to keep in mind while verifying the loans and advances</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>

<tr>
  <td>3</td>
  <td colspan="6" style="text-align: left;">Reporting under tax audit for loan / advances under section 269SS must not be forget</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>4</td>
  <td colspan="6" style="text-align: left;">Did the audit team perform subsequent events procedures</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>5</td>
  <td colspan="6" style="text-align: left;">Determining remaining procedures to complete the audit none noted, listed below</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>6</td>
  <td colspan="6" style="text-align: left;">Completed and signing the review and approval on audit documents</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">Approval of the final conclusions by the partner in charge is obtained</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>8</td>
  <td colspan="6" style="text-align: left;">Performing other wrap-up procedures</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>9</td>
  <td colspan="6" style="text-align: left;">Assessing all open items</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>10</td>
  <td colspan="6" style="text-align: left;">Clear all review notes</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>11</td>
  <td colspan="6" style="text-align: left;">Complete documentations and conclusions on workpapers</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>12</td>
  <td colspan="6" style="text-align: left;">Evaluating engagement economics</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>13</td>
  <td colspan="6" style="text-align: left;">Any difficulties in conducting the audit</td>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td>
  <td>
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label>
  </td><td>
  <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button>
<div class="col-md-12 bg-light text-right">
<input class="btn btn-primary" type="reset" value="Reset">
<input class="btn btn-primary" type="submit" value="Submit">
</body>
</html>
</div>
