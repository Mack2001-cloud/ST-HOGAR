<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$id = (int)($_GET["id"] ?? 0);
if ($id<=0) { header("Location: index.php?err=ID inválido"); exit; }

$stmt = $conexion->prepare("SELECT * FROM servicios WHERE id_servicio=:id LIMIT 1");
$stmt->execute([":id"=>$id]);
$s = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$s) { header("Location: index.php?err=Servicio no encontrado"); exit; }

$clientes = $conexion->query("SELECT id_cliente, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
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
    header("Location: editar.php?id=$id&err=Completa todos los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("UPDATE servicios
      SET id_cliente=:c, tipo=:t, descripcion=:d, fecha=:f, id_tecnico=:tec, costo=:cos
      WHERE id_servicio=:id");
    $stmt->execute([
      ":c"=>$id_cliente, ":t"=>$tipo, ":d"=>$desc, ":f"=>$fecha,
      ":tec"=>$id_tecnico, ":cos"=>$costo, ":id"=>$id
    ]);

    header("Location: index.php?ok=Servicio actualizado");
    exit;
  } catch (Throwable $e) {
    header("Location: editar.php?id=$id&err=Error al actualizar (verifica técnico)");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Editar servicio</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Cliente *</label>
    <select name="id_cliente" required>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo (int)$c["id_cliente"]; ?>"
          <?php echo ((int)$s["id_cliente"]===(int)$c["id_cliente"])?"selected":""; ?>>
          <?php echo htmlspecialchars($c["nombre"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Tipo *</label>
    <select name="tipo" required>
      <?php foreach ($tipos as $t): ?>
        <option value="<?php echo $t; ?>" <?php echo ($s["tipo"]===$t)?"selected":""; ?>>
          <?php echo $t; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Descripción *</label>
    <input type="text" name="descripcion" required value="<?php echo htmlspecialchars($s["descripcion"]); ?>">

    <label>Fecha *</label>
    <input type="date" name="fecha" required value="<?php echo htmlspecialchars($s["fecha"]); ?>">

    <label>Técnico *</label>
    <select name="id_tecnico" required>
      <?php foreach ($tecnicos as $t): ?>
        <option value="<?php echo (int)$t["id_usuario"]; ?>"
          <?php echo ((int)$s["id_tecnico"]===(int)$t["id_usuario"])?"selected":""; ?>>
          <?php echo htmlspecialchars($t["nombre"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Costo</label>
    <input type="number" step="0.01" name="costo" value="<?php echo htmlspecialchars($s["costo"]); ?>">

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Regresar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
