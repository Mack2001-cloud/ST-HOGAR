<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$id = (int)($_GET["id"] ?? 0);
if ($id<=0) { header("Location: index.php?err=ID inválido"); exit; }

$stmt = $conexion->prepare("SELECT * FROM equipos WHERE id_equipo=:id LIMIT 1");
$stmt->execute([":id"=>$id]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) { header("Location: index.php?err=Equipo no encontrado"); exit; }

$clientes = $conexion->query("SELECT id_cliente, nombre FROM clientes WHERE activo=1 ORDER BY nombre")->fetchAll();

$categorias = ["Camara","DVR_NVR","Sensor","Panel","Control","Otro"];
$estados = ["activo","inactivo","retirado"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_cliente = (int)($_POST["id_cliente"] ?? 0);
  $categoria  = trim($_POST["categoria"] ?? "");
  $marca      = trim($_POST["marca"] ?? "");
  $modelo     = trim($_POST["modelo"] ?? "");
  $num_serie  = trim($_POST["num_serie"] ?? "");
  $ubicacion  = trim($_POST["ubicacion"] ?? "");
  $fecha_inst = $_POST["fecha_instalacion"] ?? "";
  $estado     = trim($_POST["estado"] ?? "activo");

  if ($id_cliente<=0 || $categoria==="" || $marca==="" || $modelo==="" || $num_serie==="" || $fecha_inst==="") {
    header("Location: editar.php?id=$id&err=Completa los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("UPDATE equipos SET
      id_cliente=:c, categoria=:cat, marca=:m, modelo=:mo, num_serie=:s,
      ubicacion=:u, fecha_instalacion=:f, estado=:e
      WHERE id_equipo=:id");
    $stmt->execute([
      ":c"=>$id_cliente, ":cat"=>$categoria, ":m"=>$marca, ":mo"=>$modelo,
      ":s"=>$num_serie, ":u"=>$ubicacion, ":f"=>$fecha_inst, ":e"=>$estado,
      ":id"=>$id
    ]);

    header("Location: index.php?ok=Equipo actualizado");
    exit;
  } catch (Throwable $ex) {
    header("Location: editar.php?id=$id&err=Error: serie duplicada o datos inválidos");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Editar equipo</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Cliente *</label>
    <select name="id_cliente" required>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo (int)$c["id_cliente"]; ?>"
          <?php echo ((int)$e["id_cliente"]===(int)$c["id_cliente"])?"selected":""; ?>>
          <?php echo htmlspecialchars($c["nombre"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Categoría *</label>
    <select name="categoria" required>
      <?php foreach ($categorias as $x): ?>
        <option value="<?php echo $x; ?>" <?php echo ($e["categoria"]===$x)?"selected":""; ?>>
          <?php echo $x; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Marca *</label>
    <input type="text" name="marca" required value="<?php echo htmlspecialchars($e["marca"]); ?>">

    <label>Modelo *</label>
    <input type="text" name="modelo" required value="<?php echo htmlspecialchars($e["modelo"]); ?>">

    <label>Número de serie *</label>
    <input type="text" name="num_serie" required value="<?php echo htmlspecialchars($e["num_serie"]); ?>">

    <label>Ubicación</label>
    <input type="text" name="ubicacion" value="<?php echo htmlspecialchars($e["ubicacion"] ?? ""); ?>">

    <label>Fecha de instalación *</label>
    <input type="date" name="fecha_instalacion" required value="<?php echo htmlspecialchars($e["fecha_instalacion"]); ?>">

    <label>Estado</label>
    <select name="estado">
      <?php foreach ($estados as $x): ?>
        <option value="<?php echo $x; ?>" <?php echo ($e["estado"]===$x)?"selected":""; ?>>
          <?php echo $x; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Regresar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
