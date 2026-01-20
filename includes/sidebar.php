<?php
require_once __DIR__ . "/../config/app.php";
$currentPath = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
?>
<aside class="sidebar" aria-label="Navegación principal">
  <nav>
    <ul>
      <li>
        <a class="<?php echo $currentPath === "dashboard.php" ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/dashboard.php">
          🏠 Dashboard
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "clientes/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/clientes/index.php">
          👥 Clientes
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "servicios/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/servicios/index.php">
          🛠️ Servicios
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "bitacora/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/bitacora/index.php">
          📒 Bitácora
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "equipos/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/equipos/index.php">
          📦 Equipos
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "mantenimientos/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/mantenimientos/index.php">
          🧰 Mantenimientos
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "ventas/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/ventas/index.php">
          💰 Ventas
        </a>
      </li>
    </ul>
  </nav>
</aside>
<main class="main">
