<?php

include 'config/constant.php';
include 'common/common.php';
isUserLoggedIn();


// get requested url. remove .php, querystring and any other # things from URL. need only page name
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = explode('/', $request_uri);
$request_uri = end($request_uri);
$request_uri = explode('.', $request_uri);
$request_uri = $request_uri[0];
// echo $request_uri;
// exit();

if (!isUserHasRights($request_uri)) {
    header("Location: " . BASE_URL . "logout");
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>Circle List</title>



  <!-- Google Font: Source Sans Pro -->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="css/all.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="css/ionicons.min.css">

  <!-- Tempusdominus Bootstrap 4 -->

  <link rel="stylesheet" href="css/tempusdominus-bootstrap-4.min.css">

  <!-- iCheck -->

  <link rel="stylesheet" href="css/icheck-bootstrap.min.css">

  <!-- JQVMap -->

  <link rel="stylesheet" href="css/jqvmap.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="css/adminlte.min2167.css">

  <!-- overlayScrollbars -->

  <link rel="stylesheet" href="css/OverlayScrollbars.min.css">

  <!-- Daterange picker -->

  <link rel="stylesheet" href="css/daterangepicker.css">

  <!-- Summernote -->

  <link rel="stylesheet" href="css/summernote-bs4.min.css">



  <!-- DataTables CSS jQuery -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



  <!-- Optional custom styles -->

  <style>

    .content-wrapper {

      min-height: calc(100vh - 57px - 70px); /* navbar + footer */

    }

  </style>

</head>



<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">



  <!-- Navbar -->

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->

    <ul class="navbar-nav">

      <li class="nav-item">

        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>

      </li>

    </ul>

  </nav>



  <!-- Main Sidebar -->

<?php include 'sidebar.php'; ?>





  <div class="content-wrapper">

    <div class="content pt-3">

      <div class="container-fluid">

      

