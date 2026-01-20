<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$id = (int)($_GET["id"] ?? 0);
if ($id<=0) { header("Location: index.php?err=ID inválido"); exit; }

$stmt = $conexion->prepare("SELECT * FROM ventas WHERE id_venta=:id LIMIT 1");
$stmt->execute([":id"=>$id]);
$v = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$v) { header("Location: index.php?err=Venta no encontrada"); exit; }

$clientes = $conexion->query("SELECT id_cliente, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$vendedores = $conexion->query("SELECT id_usuario, nombre FROM usuarios WHERE activo=1 AND rol='ventas' ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_cliente  = (int)($_POST["id_cliente"] ?? 0);
  $concepto    = trim($_POST["concepto"] ?? "");
  $monto       = (float)($_POST["monto"] ?? 0);
  $fecha       = $_POST["fecha"] ?? "";
  $id_vendedor = (int)($_POST["id_vendedor"] ?? 0);

  if ($id_cliente<=0 || $concepto==="" || $fecha==="" || $id_vendedor<=0) {
    header("Location: editar.php?id=$id&err=Completa los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("UPDATE ventas SET
      id_cliente=:c, concepto=:con, monto=:m, fecha=:f, id_vendedor=:v
      WHERE id_venta=:id");
    $stmt->execute([
      ":c"=>$id_cliente, ":con"=>$concepto, ":m"=>$monto, ":f"=>$fecha, ":v"=>$id_vendedor, ":id"=>$id
    ]);

    header("Location: index.php?ok=Venta actualizada");
    exit;
  } catch (Throwable $e) {
    header("Location: editar.php?id=$id&err=Error al actualizar");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Editar venta</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Cliente *</label>
    <select name="id_cliente" required>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo (int)$c["id_cliente"]; ?>"
          <?php echo ((int)$v["id_cliente"]===(int)$c["id_cliente"])?"selected":""; ?>>
          <?php echo htmlspecialchars($c["nombre"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Concepto *</label>
    <input type="text" name="concepto" required value="<?php echo htmlspecialchars($v["concepto"]); ?>">

    <label>Monto</label>
    <input type="number" step="0.01" name="monto" value="<?php echo htmlspecialchars($v["monto"]); ?>">

    <label>Fecha *</label>
    <input type="date" name="fecha" required value="<?php echo htmlspecialchars($v["fecha"]); ?>">

    <label>Vendedor (rol ventas) *</label>
    <select name="id_vendedor" required>
      <?php foreach ($vendedores as $u): ?>
        <option value="<?php echo (int)$u["id_usuario"]; ?>"
          <?php echo ((int)$v["id_vendedor"]===(int)$u["id_usuario"])?"selected":""; ?>>
          <?php echo htmlspecialchars($u["nombre"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Regresar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
