<?php
require_once __DIR__ . "/../config/app.php";
$err = (int)($_GET["error"] ?? 0);

$msg = "";
if ($err === 1) $msg = "Completa usuario y contraseña.";
if ($err === 2) $msg = "Usuario no existe o está inactivo.";
if ($err === 3) $msg = "Contraseña incorrecta.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - ST-Hogar</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/includes/styles.css">
</head>
<body class="center">

<div class="card login">
  <div class="login-header">
    <h2>Bienvenido a <?php echo htmlspecialchars(APP_NAME); ?></h2>
    <p class="login-company">
      <strong><?php echo htmlspecialchars(COMPANY_NAME); ?></strong><br>
      <?php echo htmlspecialchars(COMPANY_ADDRESS); ?><br>
      <?php echo htmlspecialchars(COMPANY_PHONE); ?> · <?php echo htmlspecialchars(COMPANY_EMAIL); ?>
    </p>
  </div>

  <h3>Iniciar sesión</h3>

  <?php if ($msg): ?>
    <div class="msg err"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <form method="POST" action="validar_login.php">
    <div class="form-field">
      <label>Usuario</label>
      <input type="text" name="usuario" required>
    </div>

    <div class="form-field">
      <label>Contraseña</label>
      <input type="password" name="password" required>
    </div>

    <button class="btn" type="submit">Ingresar</button>
  </form>
</div>

</body>
</html>
