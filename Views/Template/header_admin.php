<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">  
    <meta name="description" content="Oftalmo Tijuana">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Gerardo Arreola">
    <meta name="theme-color" content="#009688">
    <?= media(); ?>/images/uploads/avatar2.png"
    <link rel="shortcut icon" href="<?= media();?>/images/uploads/<?= $_SESSION['iEmp']; ?>"><fa-bar></fa-bar>
    <title><?= $data['page_tag']; ?></title>
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/js/datepicker/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">
    
    <!-- Font-icon css-->
    
  </head>
  <body class="app sidebar-mini">
    <!-- Para poner el loader (que muestra imagen cargando...) en un solo lugar y no en todas las vistas-->
    <div id="divLoading">
      <div>
        <img src="<?= media(); ?>/images/loading.svg" alt="Loading">
      </div>
    </div>

    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo"  href="<?= base_url(); ?>dashboard"><?= $_SESSION['nEmp']; ?></a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fas fa-bars"></i></a>
      <!-- Navbar Right Menu-->
      <img src="<?= media();?>/images/uploads/<?= $_SESSION['iEmp']; ?>" width="100" height="48">
      <ul class="app-nav">
        
      
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="<?= base_url(); ?>opciones"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
            <li><a class="dropdown-item" href="<?= base_url(); ?>usuarios/perfil"><i class="fa fa-user fa-lg"></i> Profile</a></li>
            <li><a class="dropdown-item" href="<?= base_url(); ?>logout"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
<?php require_once("nav_admin.php"); ?>