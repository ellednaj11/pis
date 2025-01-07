<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PIS</title>
    <link rel="icon" href="<?= base_url('assets/images/logo-denr.png') ?>">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('public/dist/css/adminlte.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('public/plugins/fontawesome-free/css/all.min.css') ?>">
    <style>
        body {
            background: linear-gradient(0deg, rgba(63,116,170,1) 10%, rgba(85,237,99,1) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 360px;
        }
        .card {
            width: 400px;
            color: black;
            border-radius: 10px;
            padding: 20px;
        }
        .card-body {
            border-radius: 10px;
            color: #fff;
        }
        .form-control {
            background-color: light-dark(rgb(232, 240, 254), rgba(70, 90, 126, 0.4)) !important;
            color: black;
            border: none;
        }
        .input-group-text {
            background: #424242;
            border: none;
            color: #fff;
        }
        .btn-primary {
            background-color: #4caf50;
            border: none;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .login-box-msg {
            color: #d2d2d2;
        }
    </style>
</head>
<body class="">
