<?php
    include 'dbconnection.php';
    session_start();
    if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
        header("Location: ../login");
    }
    $clientName = $_SESSION['cname'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
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

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/pace-theme.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body style="overflow-y: scroll">

    <div id="wrapper" class="">



        <div id="content-wrapper" class="d-flex flex-column">
            <div class="content">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-mainbg">
                    <!-- <a class="navbar-brand navbar-logo" href="admin/dashboard">Audit-EDG</a> -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars text-white"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <div class="hori-selector">
                                <div class="left"></div>
                                <div class="right"></div>
                            </div>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-clipboard"></i>Doodle/Notes</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="#"><i class="far fa-address-book"></i>Support/Tickets</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#addClientModal"><i
                                        class="fas fa-user-plus"></i>Add Clients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/clientList"><i class="fas fa-list"></i>List Clients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- HEADER -->
                <div id="header">
                    <div class="container-fluid shadow border border-bottom" stickylevel="0" style="z-index:1200;">
                        <div class="row pt-1">
                            <div class="row text-center cdrow" href="#">
                                <h2><?php echo strtoupper($clientName . " - Dashboard"); ?></h2>
                            </div>
                        </div>
                    </div><br>
                    <!-- Body Starts -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="alert alert-dark font-2 font-weight-bold text-center">Work steps and Assertions</div>

                            </div>
                            <div class="col-12 col-md-9" id="myDIV">
                                <div class="container-fluid">
                                    <div class="alert alert-secondary font-weight-bold text-center">
                                        <h4>FA 01 Existence &amp; Occurrence</h4>
                                        To verify physical existence of the assets mentioned in the balance sheet
                                        and vouching any additions / disposals made during the year
                                    </div>
                                    <ol class="list-group procedures">
                                        <li class="list-group-item">
                                            <h5>Testing of Opening Balance</b>
                                            <ol class="tasks">
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark" href="#1"
                                                                target="" direct-link="yeah" disabled="">
                                                                <h5>Audited Fixed Asset schedule of Previous
                                                                    Year.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Fixed Asset schedule of Current Year.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain Summary of changes to Fixed Assets.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                        <li class="list-group-item">
                                            <h5>Physical Inspection of Asset Additions</b>

                                            <ol class="tasks">
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark" href="#1"
                                                                direct-link="yeah" disabled="">
                                                                <h5>Fixed Asset Count Register</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-edit ml-2" data-toggle="tooltip" title="click to work-on this"></i> -->
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i>                                     -->
                                                            </a>
                                                        </div>

                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Fixed Assets Counts on the locations identified above
                                                                    scope.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>

                                    </ol>
                                </div>
                            </div>
                            <div class="col-12 col-md-9" id="myDIV2">
                                <div class="container-fluid">
                                    <div class="alert alert-secondary font-weight-bold text-center">
                                        <h4>FA 02 Completeness</h4>
                                        To verify correctness of capitalization of asset and the expense debited to
                                        Profit & Loss Account
                                    </div>
                                    <ol class="list-group procedures">
                                        <li class="list-group-item">
                                            <h5>Testing of Assets purchased during the year</b>
                                            <ol class="tasks">
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Identify the purchases of assets above scope.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain and test Vendor Invoices for Assets
                                                                    Purchased.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Review the Memorandum on Selection of Vendor for
                                                                    Purchase of Asset for the selected transactions.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain and test the Freight Bills for Items/Asset
                                                                    Received for the selected transactions.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <h5>Testing of Assets acquired on/ or as government grants.</b>

                                                    <ol class="tasks">
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <a class="text-decoration-none text-dark" href="#1"
                                                                        direct-link="yeah" disabled="">
                                                                        <h5>Memorandum on Cost of Acquisition of the
                                                                            Asset.</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-edit ml-2" data-toggle="tooltip" title="click to work-on this"></i> -->
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i>                                     -->
                                                                    </a>
                                                                </div>

                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Memorandum on Treatment of Government Grant
                                                                            Received disclosed Balance Sheet.</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <h5>Testing of Capital Work in Progress</b>

                                                            <ol class="tasks">
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <a class="text-decoration-none text-dark"
                                                                                href="#1" direct-link="yeah"
                                                                                disabled="">
                                                                                <h5>Ledger of Material Requisitions</b>
                                                                                <a href="" data-toggle="modal"
                                                                                    data-target="#spOpenModal">
                                                                                    <i
                                                                                        class="fas fa-external-link-alt"></i>
                                                                                </a>
                                                                                <i
                                                                                    class="fas fa-times-circle incomplete"></i>
                                                                                <!-- <i class="fas fa-edit ml-2" data-toggle="tooltip" title="click to work-on this"></i> -->
                                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i>                                     -->
                                                                            </a>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-7">
                                                                            <a class="text-decoration-none text-dark getContent"
                                                                                href="#" target="#contentFinalChild"
                                                                                direct-link="yeah"
                                                                                showhide="#contentChild1,#contentFinalChild">
                                                                                <h5>Test the "Labour time tickets".</b>
                                                                                <a href="" data-toggle="modal"
                                                                                    data-target="#spOpenModal">
                                                                                    <i
                                                                                        class="fas fa-external-link-alt"></i>
                                                                                </a>
                                                                                <i
                                                                                    class="fas fa-times-circle incomplete"></i>
                                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-7">
                                                                            <a class="text-decoration-none text-dark getContent"
                                                                                href="#" target="#contentFinalChild"
                                                                                direct-link="yeah"
                                                                                showhide="#contentChild1,#contentFinalChild">
                                                                                <h5>Ledger of "Overheads".</b>
                                                                                <a href="" data-toggle="modal"
                                                                                    data-target="#spOpenModal">
                                                                                    <i
                                                                                        class="fas fa-external-link-alt"></i>
                                                                                </a>
                                                                                <i
                                                                                    class="fas fa-times-circle incomplete"></i>
                                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-7">
                                                                            <a class="text-decoration-none text-dark getContent"
                                                                                href="#" target="#contentFinalChild"
                                                                                direct-link="yeah"
                                                                                showhide="#contentChild1,#contentFinalChild">
                                                                                <h5>Verify the Interest Certificate of
                                                                                    Borrowing
                                                                                    Costs Capitalized.</b>
                                                                                <a href="" data-toggle="modal"
                                                                                    data-target="#spOpenModal">
                                                                                    <i
                                                                                        class="fas fa-external-link-alt"></i>
                                                                                </a>
                                                                                <i
                                                                                    class="fas fa-times-circle incomplete"></i>
                                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ol>
                                                        </li>
                                                    </ol>
                                                </li>
                                                <li class="list-group-item">
                                                    <h5>Testing of Disposals Made during the year</b>

                                                    <ol class="tasks">
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <a class="text-decoration-none text-dark" href="#1"
                                                                        direct-link="yeah" disabled="">
                                                                        <h5>Obtain the listing of all the assets sold during the year and identify the items for testing</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-edit ml-2" data-toggle="tooltip" title="click to work-on this"></i> -->
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i>                                     -->
                                                                    </a>
                                                                </div>

                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Obtain the Board Resolution prepared to sell the asset for the selected items</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Obtain and examine the agreements prepared to sell the asset for the items selected</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Obtain the receipts of the assets sold and validate the same.</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Recalculate the amount outstanding to be received, if any.</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Recalculate and verify the Age of receivable for the sale of asset.</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <a class="text-decoration-none text-dark getContent"
                                                                        href="#" target="#contentFinalChild"
                                                                        direct-link="yeah"
                                                                        showhide="#contentChild1,#contentFinalChild">
                                                                        <h5>Recalculate the capital gains made on the asset and inspect whether the amounts are properly disclosed in the Balance Sheet.</b>
                                                                        <a href="" data-toggle="modal"
                                                                            data-target="#spOpenModal">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                        <i class="fas fa-times-circle incomplete"></i>
                                                                        <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ol>
                                                </li>
                                            </ol>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-12 col-md-9" id="myDIV3">
                                <div class="container-fluid">
                                    <div class="alert alert-secondary font-weight-bold text-center">
                                        <h4>FA 03 Rights & Ownership</h4>
                                        To verify that the company has legal title or equivalent ownership rights for the Assets appearing in the Balance Sheet
                                    </div>
                                    <ol class="list-group procedures">
                                        <li class="list-group-item">
                                            <h5>Examine evidence of legal ownership of PPE</b>
                                            <ol class="tasks">
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Identify the assets for testing above scope.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain Lease Deeds for Leased Assets for the items selected</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain Latest Insurance policy for the Asset for the selected items.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain Insurance Confirmations for the assets from the Insurance Companies.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Obtain RC (Registration Certificate) for Vehicles owned & showed as asset.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <a class="text-decoration-none text-dark getContent"
                                                                href="#" target="#contentFinalChild" direct-link="yeah"
                                                                showhide="#contentChild1,#contentFinalChild">
                                                                <h5>Recalculate and verify the computation of Minimum Lease Payments on a Financial Lease.</b>
                                                                <a href="" data-toggle="modal"
                                                                    data-target="#spOpenModal">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                                <i class="fas fa-times-circle incomplete"></i>
                                                                <!-- <i class="fas fa-check-circle text-success ml-2" data-toggle="tooltip" title="task completed"></i> -->
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <!--Add Client Form -->
    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fill in the Client details<h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                </div>
                <form action="admin/addClient" method="post" id="addClientForm" enctype="multipart/form-data"
                    autocomplete="off">
                    <div class="modal-body">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Basic Details<h5>
                        </div>
                        <div class="form-group ">
                            <label for="name">Client Name</label>
                            <input type="text" class="form-control" name="clientname" required>
                        </div>
                        <div class="form-group ">
                            <label for="name">Nick Name</label>
                            <input type="text" class="form-control" name="nickname">
                        </div>
                        <div class="form-group ">
                            <label for="name">Date of Incorporation/ Birth</label>
                            <input type="date" class="form-control" name="dob" required>
                        </div>
                        <div class="form-group ">
                            <label for="country">Constitution</label>
                            <select class="form-control" name="constitution" required>
                                <option>Select Constitution !</option>
                                <?php
                                                $consQuery = $con->query("select * from constitution");
                                                while ($consResult = $consQuery->fetch_assoc()) {
                                            ?>
                                <option value="<?php echo $consResult['id']; ?>">
                                    <?php echo $consResult['const']; ?></option>
                                <?php
                                            }
                                            ?>
                            </select>
                        </div>
                        <div class="form-group ">
                            <label for="country">Industry</label>
                            <select class="form-control" name="industry" required>
                                <option>Select Industry !</option>
                                <?php
                                                $indusQuery = $con->query("select * from industry");
                                                while ($indusResult = $indusQuery->fetch_assoc()) {
                                            ?>
                                <option value="<?php echo $indusResult['id']; ?>">
                                    <?php echo $indusResult['industry']; ?></option>
                                <?php
                                            }
                                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Address</label>
                            <input type="text" class="form-control" name="add">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" name="country">
                        </div>
                        <div class="form-group" id="stateEntryIdDiv">
                            <label for="state">State</label>
                            <input type="text" class="form-control" name="state">
                        </div>
                        <div class="form-group" id="citiesEntryIdDiv">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        <div class="form-group ">
                            <label for="name">Pincode</label>
                            <input type="text" class="form-control" name="pincode" required>
                        </div>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Satuatory Information<h5>
                        </div>
                        <div class="form-group ">
                            <label for="name">Pan No.</label>
                            <input type="text" class="form-control" name="pan" required>
                        </div>
                        <div class="form-group ">
                            <label for="name">GST No.</label>
                            <input type="text" class="form-control" name="gst" required>
                        </div>
                        <div class="form-group ">
                            <label for="name">TAN No.</label>
                            <input type="text" class="form-control" name="tan" required>
                        </div>
                        <div class="form-group ">
                            <label for="name">CIN No.</label>
                            <input type="text" class="form-control" name="cin" required>
                        </div>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Contact Person<h5>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered table-hover" id="tab_logic">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> Name</th>
                                            <th class="text-center"> Email</th>
                                            <th class="text-center"> Phone</th>
                                            <th class="text-center"> Designation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id='addr0'>
                                            <td><input type="text" class="form-control" name="pname[]" required>
                                            </td>
                                            <td><input type="email" class="form-control" name="email[]" required>
                                            </td>
                                            <td><input type="text" class="form-control" name="phone[]" required>
                                            </td>
                                            <td><input type="text" name='designation[]' class="form-control" required />
                                            </td>
                                        </tr>
                                        <tr id='addr1'></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="row">
                            <div class="col d-flex justify-content-between">
                                <a href="#" id="add_row" class="btn btn-outline-primary pull-left">Add
                                    Row</a>
                                <a href="#" id='delete_row' class="btn btn-outline-danger">Delete
                                    Row</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                        <input class="btn btn-warning" type="reset" value="Reset">
                        <input class="btn btn-primary" type="submit" id="dataEntrySubmit" value="Done">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Subprogram Open Modal-->
    <div class="modal fade" id="spOpenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container card bg-light font-2 py-2">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Obtain Client Acceptance Engagement Letter</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                    Sign-Off
                                </a>
                                <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                    Review
                                </a>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label>Documents</label>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a class="btn btn-outline-dark btn-sm py-0 menu-02">
                                            <i class="fas fa-upload upload"></i>
                                        </a>
                                    </div>
                                </div>
                                <ul class="list-group h5">
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item"></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <label>Comments</label>
                                <textarea class="form-control" style="height:200px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/custom.js"></script>
    <script>
    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function myFunction2() {
        var x = document.getElementById("myDIV2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    function myFunction3() {
        var x = document.getElementById("myDIV3");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    </script>
    <script>
    $(document).ready(function() {
        var i = 1;
        b = i - 1;
        $("#add_row").click(function() {
            $('#addr' + i).html($('#addr' + b).html()).find('td:first-child');
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });
        //Delete Row Function for sales add form
        $("#delete_row").click(function() {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });
    });
    </script>
</body>