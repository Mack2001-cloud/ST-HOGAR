<?php
require_once __DIR__ . "/auth/auth_guard.php";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/sidebar.php";
?>
<div class="dashboard">
  <section class="card card-hero">
    <div>
      <h2>Dashboard</h2>
      <p class="muted">✅ Sistema funcionando y listo para operar.</p>
      <p class="meta">
        <span><b>Usuario:</b> <?php echo htmlspecialchars($_SESSION["nombre"]); ?></span>
        <span><b>Rol:</b> <?php echo htmlspecialchars($_SESSION["rol"]); ?></span>
      </p>
    </div>
    <div class="hero-actions">
      <a class="btn" href="<?php echo BASE_URL; ?>/clientes/index.php">Nuevo cliente</a>
      <a class="btn2" href="<?php echo BASE_URL; ?>/servicios/index.php">Nuevo servicio</a>
    </div>
  </section>

  <section class="stats-grid">
    <article class="stat-card">
      <div class="stat-icon">👥</div>
      <div>
        <p class="stat-label">Clientes</p>
        <p class="stat-value">Gestión activa</p>
      </div>
      <a class="stat-link" href="<?php echo BASE_URL; ?>/clientes/index.php">Ver lista →</a>
    </article>
    <article class="stat-card">
      <div class="stat-icon">🛠️</div>
      <div>
        <p class="stat-label">Servicios</p>
        <p class="stat-value">Órdenes y tareas</p>
      </div>
      <a class="stat-link" href="<?php echo BASE_URL; ?>/servicios/index.php">Gestionar →</a>
    </article>
    <article class="stat-card">
      <div class="stat-icon">📒</div>
      <div>
        <p class="stat-label">Bitácora</p>
        <p class="stat-value">Seguimiento diario</p>
      </div>
      <a class="stat-link" href="<?php echo BASE_URL; ?>/bitacora/index.php">Revisar →</a>
    </article>
    <article class="stat-card">
      <div class="stat-icon">💰</div>
      <div>
        <p class="stat-label">Ventas</p>
        <p class="stat-value">Ingresos al día</p>
      </div>
      <a class="stat-link" href="<?php echo BASE_URL; ?>/ventas/index.php">Ver ventas →</a>
    </article>
    <article class="stat-card">
      <div class="stat-icon">📦</div>
      <div>
        <p class="stat-label">Equipos</p>
        <p class="stat-value">Inventario actualizado</p>
      </div>
      <a class="stat-link" href="<?php echo BASE_URL; ?>/equipos/index.php">Inventario →</a>
    </article>
    <article class="stat-card">
      <div class="stat-icon">🧰</div>
      <div>
        <p class="stat-label">Mantenimientos</p>
        <p class="stat-value">Planificación</p>
      </div>
      <a class="stat-link" href="<?php echo BASE_URL; ?>/mantenimientos/index.php">Programar →</a>
    </article>
  </section>

  <section class="card card-grid">
    <div class="card-block">
      <h3>Acciones rápidas</h3>
      <p class="muted">Accesos directos para iniciar tareas en segundos.</p>
      <div class="chip-group">
        <a class="chip" href="<?php echo BASE_URL; ?>/clientes/index.php">+ Cliente</a>
        <a class="chip" href="<?php echo BASE_URL; ?>/servicios/index.php">+ Servicio</a>
        <a class="chip" href="<?php echo BASE_URL; ?>/ventas/index.php">+ Venta</a>
      </div>
    </div>
    <div class="card-block">
      <h3>Estado del sistema</h3>
      <ul class="status-list">
        <li><span class="status-dot ok"></span> Respaldo diario activo</li>
        <li><span class="status-dot ok"></span> Conexión segura</li>
        <li><span class="status-dot warn"></span> Revisión de inventario pendiente</li>
      </ul>
    </div>
  </section>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
