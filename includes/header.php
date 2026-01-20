<?php
require_once __DIR__ . "/../config/app.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ST-Hogar</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/includes/styles.css">
</head>
<body>
<header class="topbar">
  <div class="brand">ST-Hogar</div>
  <div class="user">
    <?php echo htmlspecialchars($_SESSION["nombre"] ?? ""); ?>
    (<?php echo htmlspecialchars($_SESSION["rol"] ?? ""); ?>)
    | <a href="<?php echo BASE_URL; ?>/auth/logout.php">Salir</a>
  </div>
</header>
<div class="layout">
