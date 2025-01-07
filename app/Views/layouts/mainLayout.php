<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <title><?= $title ?></title> -->
    <title>PIS - <?= $title ?></title>
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/logo-denr.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('public/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('public/dist/css/adminlte.min.css') ?>">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('public/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

    <!-- SweetAlert 2 -->
    <link rel="stylesheet" href="<?= base_url('public/sweetalert2/sweetalert2.min.css') ?>">
    <style>
        .tooltip-inner {
            background-color: #343a40; /* Dark background */
            color: #fff; /* White text */
            font-size: 14px; /* Custom font size */
            border-radius: 5px; /* Rounded corners */
        }

        .tooltip.bs-tooltip-top .arrow::before, 
        .tooltip.bs-tooltip-bottom .arrow::before, 
        .tooltip.bs-tooltip-left .arrow::before, 
        .tooltip.bs-tooltip-right .arrow::before {
            border-color: #343a40; /* Dark background for arrow */
        }

        .status-approved { color: green; }
        .status-pending { color: blue; }
        .status-rejected { color: red; }
    </style>
</head>
    <script src="<?= base_url('public/sweetalert2/sweetalert2.min.js') ?>"></script>

    

    <!-- AdminLTE JS -->
    <script src="<?= base_url('public/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/dist/js/adminlte.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>

    <!-- DataTables  & Plugins -->
    <script src="<?= base_url('public/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jszip/jszip.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/pdfmake/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/pdfmake/vfs_fonts.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>


    <!-- Select2 -->
    <script src="<?= base_url('public/plugins/select2/js/select2.full.min.js') ?>"></script>

    
    
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?= $this->include('partials/navbar') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('partials/sidebar') ?>
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $title ?></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><?= $title ?></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div><!-- /.container-fluid -->
            </div><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?= $this->include('partials/footer') ?>
    </div><!-- ./wrapper -->
</body>
</html>
