<?php
require_once __DIR__ . "/auth/auth_guard.php";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/sidebar.php";
?>
<div class="card">
  <h2>Dashboard</h2>
  <p>✅ Sistema funcionando.</p>
  <p><b>Usuario:</b> <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>
  <p><b>Rol:</b> <?php echo htmlspecialchars($_SESSION["rol"]); ?></p>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
