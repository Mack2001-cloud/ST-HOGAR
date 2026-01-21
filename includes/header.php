<?php
require_once __DIR__ . "/../config/app.php";
$name = trim($_SESSION["nombre"] ?? "");
$role = trim($_SESSION["rol"] ?? "");
$initial = $name !== "" ? strtoupper($name[0]) : "U";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ST-Hogar</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/includes/styles.css">
</head>
<body class="app-body">
<header class="topbar">
  <div class="topbar__left">
    <button class="icon-button" type="button" data-sidebar-toggle aria-label="Abrir menú">
      ☰
    </button>
    <a class="brand" href="<?php echo BASE_URL; ?>/dashboard.php">ST-Hogar</a>
  </div>
  <div class="topbar__right">
    <div class="user-menu" data-user-menu>
      <button class="user-menu__trigger" type="button" data-user-toggle aria-expanded="false">
        <span class="user-menu__avatar"><?php echo htmlspecialchars($initial); ?></span>
        <span class="user-menu__name">
          <?php echo htmlspecialchars($name !== "" ? $name : "Usuario"); ?>
          <span><?php echo htmlspecialchars($role !== "" ? $role : ""); ?></span>
        </span>
      </button>
      <div class="user-menu__dropdown" data-user-dropdown>
        <a href="<?php echo BASE_URL; ?>/dashboard.php">Panel</a>
        <a href="<?php echo BASE_URL; ?>/auth/logout.php">Salir</a>
      </div>
    </div>
  </div>
</header>
<div class="app-shell">
