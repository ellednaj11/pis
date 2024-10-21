<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENR | EMB-PIS</title>
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/logo-denr.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <script src="<?php echo base_url(); ?>public/plugins/jquery/jquery.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <script src="<?php echo base_url(); ?>public/plugins/jquery-validation/jquery.validate.min.js"></script>
    <style>
        body {
            background: linear-gradient(0deg, rgba(63,116,170,1) 10%, rgba(85,237,99,1) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 400px;
            /* background-color: #333; */
            color: black;
            border-radius: 10px;
            padding: 20px;
        }
        .form-control {
            /* background-color: #555; */
            background-color: light-dark(rgb(232, 240, 254), rgba(70, 90, 126, 0.4)) !important;
            color: black;
            border: none;
        }
        .btn-primary {
            background-color: green;
            border: none;
        }
        .btn-primary:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
