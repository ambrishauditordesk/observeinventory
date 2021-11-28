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
          

        <h4 class='text-center'>Observation Procedures</h4>
          <div class="table-responsive">
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
     -->
         <table class="table">
  <thead class="table-primary">
<tr>
    <th colspan="" style="text-align: left;">Name of the client:</td><br></th>
</tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
  <thead class="table-primary">
  <tr >
    <th colspan="" style="text-align: left;">Location where inventory is to be observed:</td><br></th>
  </tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
  <thead class="table-primary">
  <tr >
    <th colspan="" style="text-align: left;">Types of inventories and items to be counted:</td><br></th>
  </tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
  <tr >
  <thead class="table-primary">
    <th colspan="" style="text-align: left;">Does any item need special emphasis during the observation:</td><br></th>
  </tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
  <thead class="table-primary">
  <tr >
    <th colspan="" style="text-align: left;">Date and time of count:</td><br></th>
  </tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
  <thead class="table-primary">
  <tr >
    <th colspan="" style="text-align: left;">Names of client officials or point of contact for the inventory count for the location:</td><br></th>
  </tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
  <thead class="table-primary">
  <tr >
    <th colspan="" style="text-align: left;">Number of locations inventory count to be performed:</td><br></th>
  </tr>
</thead>
  <tr>
    <td colspan="" style="text-align: left;"><input type="text" name="" size="150"></td><br></th>
  </tr>
 </table>
<br>
<h4 class='text-center'> Inventory count observation checklist</h4>
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
  <td colspan="6" style="text-align: left;">  Are items arranged in a suitable manner for the count to be performed.</td>
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
  <td colspan="6" style="text-align: left;">Were you able to identify the items to be counted.were finished goods clearly seperated from work in progress and raw materials..</td>
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
  <td colspan="6" style="text-align: left;">Production was suspended during the count?</td>
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
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
  <td colspan="6" style="text-align: left;">If the plant is not shut down, how did you counted work in progress.</td>
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
  <td colspan="6" style="text-align: left;">Did you counted any items received during the day not included in the inventory or items shipped to be included in inventory.</td>
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
  <td colspan="6" style="text-align: left;">Obsolete,damaged and slow-moving items were identified and segregated.</td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
    </label></td>
  <td><label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
      
    </label></td><td>
    <span>&#10060;</span>

</td>
</tr>
<tr>
  <td>7</td>
  <td colspan="6" style="text-align: left;">The entity had proper physical safeguards over inventory items.</td>
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
  <td>8</td>
  <td colspan="6" style="text-align: left;">Tags/sheets were reviewed for reasonableness of quantities listed .</td>
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
  <td>9</td>
  <td colspan="6" style="text-align: left;">Tags/sheets were reviewed for reasonableness of quantities listed.</td>
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
  <td>10</td>
  <td colspan="6" style="text-align: left;">Tags/sheets clearly identified:</td>
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
  <td>11</td>
  <td colspan="6" style="text-align: left;">Location:</td>
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
  <td>12</td>
  <td colspan="6" style="text-align: left;">Quantity:</td>
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
  <td>13</td>
  <td colspan="6" style="text-align: left;">Description of item:</td>
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
  <td>14</td>
  <td colspan="6" style="text-align: left;">Unit of measurement:</td>
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
  <td>15</td>
  <td colspan="6" style="text-align: left;">Status of item(e.g, good condition,obsolete,damaged):</td>
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
  <td>16</td>
  <td colspan="6" style="text-align: left;">For significant inventory items held by the entity on a consignment basis,test counts were made and listed in the workpapers for tracing to the entity's consignment records:</td>
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
  <td>17</td>
  <td colspan="6" style="text-align: left;">All significant obsolete or slow-moving items noted during our observation have been listed in the workpapers:</td>
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
  <td>18</td>
  <td colspan="6" style="text-align: left;">For significant inventory items held at outside locations for which this location has accounting responsibility:</td>
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
    <span>&#10060;</span></td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD QUESTION</button><br>
<h4 class='text-center'> Inventory count observation checklist</h4>
<table class="table">
<thead class="table-primary">
<tr>
    <td>Professional:<input type="text" name="" size="10"></td><br></th>
    <td>Location/department(e.g., building,floor,section):<input type="text" name="" size="10"></td><br></th>
    <td>Date:<input type="text" name="" size="20"></td><br></th>
    <td>Time: From<input type="text" name="" size="4">To:<input type="text" name="" size="10"></td><br></th>
  </tr>
  
<tr>
  <td>Tag number or count sheet reference</td>
  <td>Part number or other identification information</td>
  <td>Description</td>
  <td>Unit of measure</td>
  <td>Quantity</td>
  <td>Stage of completion,condition, other commentary</td>
  <td>  </td>
</tr>
</thead>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" namee="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4  class='text-center'> Cutoff data Movements of inventory</h4><br>
<table class="table">
<thead class="table-primary">
<tr>
  <td>Name</td>
  <td>Date</td>
  <td>Reference number and description</td>
  <td>Has material been included in inventory?</td>
  <td>Amount of invoice(if avaliable)</td>
  <td>Transaction for goods recorded in proper period?</td>
  <td></td>
</tr>
</thead>
<tr>
  <td>Goods Inward(i.e.,Receipts)</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>

<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>

<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td>Goods Outward(i.e.,Shipments)</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button><br>
<h4 class='text-center'> Movements of inventory during count</h4><br>

<table class="table">
<thead class="table-primary">
<tr>
  <td>Description of goods</td>
  <td>Location or department moved to</td>
  <td>Location or department received from</td>
  <td>Quantity</td>
  <td>Tag or sheet reference</td>
  <td>Goods properly included in/excluded from inventory and WIP</td>
  <td> </td>
</tr>
</thead>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
<td> <span>&#10060;</span>
</td>
</tr>
<tr>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><input type="text" name="" size="10"></td>
  <td><span>&#10060;</span>

</td>
</tr>
</table>
<button type="button" class="btn btn-secondary">ADD ROW</button>
<div class="col-md-12 bg-light text-right">
<input class="btn btn-primary" type="reset" value="Reset">
<input class="btn btn-primary" type="submit" value="Submit">
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
</div>