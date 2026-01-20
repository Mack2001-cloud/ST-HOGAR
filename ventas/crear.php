<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$clientes = $conexion->query("SELECT id_cliente, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// vendedores = rol ventas (si no tienes usuarios ventas, no saldrán aquí)
$vendedores = $conexion->query("SELECT id_usuario, nombre FROM usuarios WHERE activo=1 AND rol='ventas' ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_cliente  = (int)($_POST["id_cliente"] ?? 0);
  $concepto    = trim($_POST["concepto"] ?? "");
  $monto       = (float)($_POST["monto"] ?? 0);
  $fecha       = $_POST["fecha"] ?? "";
  $id_vendedor = (int)($_POST["id_vendedor"] ?? 0);

  if ($id_cliente<=0 || $concepto==="" || $fecha==="" || $id_vendedor<=0) {
    header("Location: crear.php?err=Completa los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("INSERT INTO ventas
      (id_cliente, concepto, monto, fecha, estado, id_vendedor)
      VALUES (:c,:con,:m,:f,'cotizado',:v)");
    $stmt->execute([
      ":c"=>$id_cliente, ":con"=>$concepto, ":m"=>$monto, ":f"=>$fecha, ":v"=>$id_vendedor
    ]);

    header("Location: index.php?ok=Venta registrada");
    exit;
  } catch (Throwable $e) {
    header("Location: crear.php?err=Error al guardar venta");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Nueva venta</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <?php if (count($vendedores)===0): ?>
    <div class="msg err">
      No hay usuarios con rol <b>ventas</b>. Crea uno en la tabla usuarios para poder registrar ventas.
    </div>
  <?php endif; ?>

  <form method="POST">
    <label>Cliente *</label>
    <select name="id_cliente" required>
      <option value="">Selecciona...</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo (int)$c["id_cliente"]; ?>"><?php echo htmlspecialchars($c["nombre"]); ?></option>
      <?php endforeach; ?>
    </select>

    <label>Concepto *</label>
    <input type="text" name="concepto" required>

    <label>Monto</label>
    <input type="number" step="0.01" name="monto" value="0">

    <label>Fecha *</label>
    <input type="date" name="fecha" required>

    <label>Vendedor (rol ventas) *</label>
    <select name="id_vendedor" required>
      <option value="">Selecciona...</option>
      <?php foreach ($vendedores as $v): ?>
        <option value="<?php echo (int)$v["id_usuario"]; ?>"><?php echo htmlspecialchars($v["nombre"]); ?></option>
      <?php endforeach; ?>
    </select>

    <button class="btn" type="submit" <?php echo (count($vendedores)===0)?"disabled":""; ?>>Guardar</button>
    <a class="btn2" href="index.php">Cancelar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
