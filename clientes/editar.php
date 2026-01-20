<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) { header("Location: index.php?err=ID inválido"); exit; }

$stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente=:id AND activo=1");
$stmt->execute([":id"=>$id]);
$c = $stmt->fetch();

if (!$c) { header("Location: index.php?err=Cliente no encontrado"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"] ?? "");
  $tel    = trim($_POST["telefono"] ?? "");
  $email  = trim($_POST["email"] ?? "");
  $dir    = trim($_POST["direccion"] ?? "");

  if ($nombre === "" || $tel === "") {
    header("Location: editar.php?id=$id&err=Nombre y teléfono obligatorios");
    exit;
  }

  $stmt = $conexion->prepare("UPDATE clientes
                              SET nombre=:n, telefono=:t, email=:e, direccion=:d
                              WHERE id_cliente=:id");
  $stmt->execute([":n"=>$nombre,":t"=>$tel,":e"=>$email,":d"=>$dir,":id"=>$id]);

  header("Location: index.php?ok=Cliente actualizado");
  exit;
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Editar cliente</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Nombre *</label>
    <input type="text" name="nombre" required value="<?php echo htmlspecialchars($c["nombre"]); ?>">

    <label>Teléfono *</label>
    <input type="text" name="telefono" required value="<?php echo htmlspecialchars($c["telefono"]); ?>">

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($c["email"] ?? ""); ?>">

    <label>Dirección</label>
    <input type="text" name="direccion" value="<?php echo htmlspecialchars($c["direccion"] ?? ""); ?>">

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Regresar</a>
  </form>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>
