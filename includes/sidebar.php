<?php
require_once __DIR__ . "/../config/app.php";
$currentPath = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
?>
<aside class="sidebar" aria-label="Navegación principal">
  <nav>
    <ul>
      <li>
        <a class="<?php echo $currentPath === "dashboard.php" ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/dashboard.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <path d="M3 10.5 12 3l9 7.5"></path>
              <path d="M5 9.5V21a1 1 0 0 0 1 1h4v-7h4v7h4a1 1 0 0 0 1-1V9.5"></path>
            </svg>
          </span>
          Dashboard
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "clientes/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/clientes/index.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <circle cx="9" cy="8" r="3"></circle>
              <path d="M4 19a5 5 0 0 1 10 0"></path>
              <circle cx="17" cy="9" r="2.5"></circle>
              <path d="M14.5 19a4.5 4.5 0 0 1 5.5-3.5"></path>
            </svg>
          </span>
          Clientes
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "servicios/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/servicios/index.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <path d="M14.5 6.5a4 4 0 0 0-5 5l-6 6 2 2 6-6a4 4 0 0 0 5-5"></path>
              <path d="M13 7l4 4"></path>
            </svg>
          </span>
          Servicios
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "bitacora/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/bitacora/index.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <path d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 0-3 3V4z"></path>
              <path d="M8 4v16"></path>
              <path d="M11 8h6"></path>
              <path d="M11 12h6"></path>
            </svg>
          </span>
          Bitácora
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "equipos/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/equipos/index.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <path d="M3 7l9-4 9 4-9 4-9-4z"></path>
              <path d="M3 7v10l9 4 9-4V7"></path>
              <path d="M12 11v10"></path>
            </svg>
          </span>
          Equipos
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "mantenimientos/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/mantenimientos/index.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <path d="M8 6V4h8v2"></path>
              <rect x="3" y="6" width="18" height="14" rx="2"></rect>
              <path d="M3 12h18"></path>
              <path d="M10 12v3h4v-3"></path>
            </svg>
          </span>
          Mantenimientos
        </a>
      </li>
      <li>
        <a class="<?php echo str_starts_with($currentPath, "ventas/") ? "active" : ""; ?>" href="<?php echo BASE_URL; ?>/ventas/index.php">
          <span class="sidebar-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="presentation">
              <path d="M12 3v18"></path>
              <path d="M16 7.5a3.5 3.5 0 0 0-3.5-3h-1a3.5 3.5 0 0 0 0 7h1a3.5 3.5 0 0 1 0 7h-1A3.5 3.5 0 0 1 8 15.5"></path>
            </svg>
          </span>
          Ventas
        </a>
      </li>
    </ul>
  </nav>
</aside>
<main class="main">
