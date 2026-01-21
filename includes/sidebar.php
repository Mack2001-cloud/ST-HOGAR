<?php
require_once __DIR__ . "/../config/app.php";
?>
<div class="sidebar-backdrop" data-sidebar-close></div>
<aside class="sidebar" data-sidebar>
  <div class="sidebar__title">Navegación</div>
  <nav class="sidebar__nav">
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/dashboard.php">
      <span class="sidebar__icon">🏠</span>
      Dashboard
    </a>
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/clientes/index.php">
      <span class="sidebar__icon">👥</span>
      Clientes
    </a>
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/servicios/index.php">
      <span class="sidebar__icon">🛠️</span>
      Servicios
    </a>
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/bitacora/index.php">
      <span class="sidebar__icon">📒</span>
      Bitácora
    </a>
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/equipos/index.php">
      <span class="sidebar__icon">📦</span>
      Equipos
    </a>
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/mantenimientos/index.php">
      <span class="sidebar__icon">🧰</span>
      Mantenimientos
    </a>
    <a class="sidebar__link" href="<?php echo BASE_URL; ?>/ventas/index.php">
      <span class="sidebar__icon">💰</span>
      Ventas
    </a>
  </nav>
</aside>
<main class="main">
  <div class="content">
