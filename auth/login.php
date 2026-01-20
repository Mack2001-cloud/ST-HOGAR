<?php
require_once __DIR__ . "/../config/app.php";
$err = (int)($_GET["error"] ?? 0);

$msg = "";
if ($err === 1) $msg = "Completa usuario y contraseña.";
if ($err === 2 || $err === 3) $msg = "Usuario o contraseña incorrectos.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - ST-Hogar</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/includes/styles.css">
</head>
<body class="center">

<div class="card login">
  <h2>Iniciar sesión</h2>

  <?php if ($msg): ?>
    <div class="msg err" role="alert" aria-live="polite"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <form method="POST" action="validar_login.php">
    <label for="usuario">Usuario</label>
    <input
      id="usuario"
      type="text"
      name="usuario"
      autocomplete="username"
      required
      aria-invalid="<?php echo $err ? "true" : "false"; ?>"
    >

    <label for="password">Contraseña</label>
    <div class="input-group">
      <input
        id="password"
        type="password"
        name="password"
        autocomplete="current-password"
        required
        aria-invalid="<?php echo $err ? "true" : "false"; ?>"
      >
      <button class="btn ghost" type="button" id="toggle-password" aria-label="Mostrar u ocultar contraseña">
        Mostrar
      </button>
    </div>

    <button class="btn" type="submit">Ingresar</button>
  </form>
</div>

<script>
  const toggleButton = document.getElementById("toggle-password");
  const passwordInput = document.getElementById("password");
  toggleButton.addEventListener("click", () => {
    const isPassword = passwordInput.type === "password";
    passwordInput.type = isPassword ? "text" : "password";
    toggleButton.textContent = isPassword ? "Ocultar" : "Mostrar";
  });
</script>

</body>
</html>

