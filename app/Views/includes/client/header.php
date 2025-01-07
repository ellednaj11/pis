<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIS- Client</title>
    
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/logo-denr.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }
        .login-container {
            display: flex;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .login-form {
            padding: 40px;
            width: 400px;
        }
        .login-image {
            background: rgb(63,116,170);
            background: linear-gradient(0deg, rgba(63,116,170,1) 10%, rgba(85,237,99,1) 100%);
            padding: 40px;
            color: black;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 400px;
        }
        .btn-primary {
            background: green;
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #feb47b, #ff7e5f);
        }
    </style>
</head>
<body>
