<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"] ?? "");
  $tel    = trim($_POST["telefono"] ?? "");
  $email  = trim($_POST["email"] ?? "");
  $dir    = trim($_POST["direccion"] ?? "");

  if ($nombre === "" || $tel === "") {
    header("Location: crear.php?err=Nombre y teléfono son obligatorios");
    exit;
  }

  $stmt = $conexion->prepare("INSERT INTO clientes(nombre,telefono,email,direccion,activo)
                              VALUES(:n,:t,:e,:d,1)");
  $stmt->execute([":n"=>$nombre,":t"=>$tel,":e"=>$email,":d"=>$dir]);

  header("Location: index.php?ok=Cliente creado");
  exit;
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Nuevo cliente</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Nombre *</label>
    <input type="text" name="nombre" required>

    <label>Teléfono *</label>
    <input type="text" name="telefono" required>

    <label>Email</label>
    <input type="email" name="email">

    <label>Dirección</label>
    <input type="text" name="direccion">

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Cancelar</a>
  </form>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
