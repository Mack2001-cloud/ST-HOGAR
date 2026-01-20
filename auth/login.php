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
  <h2>Iniciar sesión</h2>

  <?php if ($msg): ?>
    <div class="msg err"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <form method="POST" action="validar_login.php">
    <label>Usuario</label>
    <input type="text" name="usuario" required>

    <label>Contraseña</label>
    <input type="password" name="password" required>

    <button class="btn" type="submit">Ingresar</button>
  </form>
</div>

</body>
</html>

