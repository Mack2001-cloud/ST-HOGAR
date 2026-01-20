<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$clientes = $conexion->query("SELECT id_cliente, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// SOLO técnicos (para no romper el trigger)
$tecnicos = $conexion->query("SELECT id_usuario, nombre FROM usuarios WHERE activo=1 AND rol='tecnico' ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

$tipos = ["CCTV","Automatizacion","POS","Redes","Otro"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_cliente = (int)($_POST["id_cliente"] ?? 0);
  $tipo       = trim($_POST["tipo"] ?? "");
  $desc       = trim($_POST["descripcion"] ?? "");
  $fecha      = $_POST["fecha"] ?? "";
  $id_tecnico = (int)($_POST["id_tecnico"] ?? 0);
  $costo      = (float)($_POST["costo"] ?? 0);

  if ($id_cliente<=0 || $tipo==="" || $desc==="" || $fecha==="" || $id_tecnico<=0) {
    header("Location: crear.php?err=Completa todos los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("INSERT INTO servicios
      (id_cliente,tipo,descripcion,fecha,estado,id_tecnico,costo)
      VALUES (:c,:t,:d,:f,'pendiente',:tec,:cos)");
    $stmt->execute([
      ":c"=>$id_cliente, ":t"=>$tipo, ":d"=>$desc, ":f"=>$fecha, ":tec"=>$id_tecnico, ":cos"=>$costo
    ]);

    header("Location: index.php?ok=Servicio creado");
    exit;
  } catch (Throwable $e) {
    header("Location: crear.php?err=Error al guardar (verifica técnico y datos)");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Nuevo servicio</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Cliente *</label>
    <select name="id_cliente" required>
      <option value="">Selecciona...</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo (int)$c["id_cliente"]; ?>"><?php echo htmlspecialchars($c["nombre"]); ?></option>
      <?php endforeach; ?>
    </select>

    <label>Tipo *</label>
    <select name="tipo" required>
      <option value="">Selecciona...</option>
      <?php foreach ($tipos as $t): ?>
        <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
      <?php endforeach; ?>
    </select>

    <label>Descripción *</label>
    <input type="text" name="descripcion" required>

    <label>Fecha *</label>
    <input type="date" name="fecha" required>

    <label>Técnico *</label>
    <select name="id_tecnico" required>
      <option value="">Selecciona...</option>
      <?php foreach ($tecnicos as $t): ?>
        <option value="<?php echo (int)$t["id_usuario"]; ?>"><?php echo htmlspecialchars($t["nombre"]); ?></option>
      <?php endforeach; ?>
    </select>

    <label>Costo</label>
    <input type="number" step="0.01" name="costo" value="0">

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Cancelar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
