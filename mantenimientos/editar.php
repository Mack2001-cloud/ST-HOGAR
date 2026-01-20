<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$id = (int)($_GET["id"] ?? 0);
if ($id<=0) { header("Location: index.php?err=ID inválido"); exit; }

$stmt = $conexion->prepare("SELECT * FROM mantenimientos WHERE id_mantenimiento=:id LIMIT 1");
$stmt->execute([":id"=>$id]);
$m = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$m) { header("Location: index.php?err=No encontrado"); exit; }

// Permitimos seleccionar el equipo actual aunque no esté activo
$equipos = $conexion->query("
  SELECT e.id_equipo, e.num_serie, e.marca, e.modelo, e.estado, c.nombre AS cliente
  FROM equipos e
  INNER JOIN clientes c ON c.id_cliente = e.id_cliente
  ORDER BY c.nombre, e.id_equipo DESC
")->fetchAll(PDO::FETCH_ASSOC);

$tecnicos = $conexion->query("
  SELECT id_usuario, nombre
  FROM usuarios
  WHERE activo=1 AND rol='tecnico'
  ORDER BY nombre
")->fetchAll(PDO::FETCH_ASSOC);

$tipos = ["preventivo","correctivo"];
$resultados = ["exitoso","pendiente","requiere_reemplazo"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_equipo   = (int)($_POST["id_equipo"] ?? 0);
  $fecha       = $_POST["fecha"] ?? "";
  $tipo        = trim($_POST["tipo"] ?? "");
  $descripcion = trim($_POST["descripcion"] ?? "");
  $resultado   = trim($_POST["resultado"] ?? "exitoso");
  $id_tecnico  = (int)($_POST["id_tecnico"] ?? 0);

  if ($id_equipo<=0 || $fecha==="" || $tipo==="" || $descripcion==="" || $id_tecnico<=0) {
    header("Location: editar.php?id=$id&err=Completa los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("UPDATE mantenimientos SET
      id_equipo=:eq, fecha=:f, tipo=:t, descripcion=:d, resultado=:r, id_tecnico=:tec
      WHERE id_mantenimiento=:id");
    $stmt->execute([
      ":eq"=>$id_equipo, ":f"=>$fecha, ":t"=>$tipo, ":d"=>$descripcion,
      ":r"=>$resultado, ":tec"=>$id_tecnico, ":id"=>$id
    ]);

    header("Location: index.php?ok=Mantenimiento actualizado");
    exit;
  } catch (Throwable $e) {
    header("Location: editar.php?id=$id&err=Error: el equipo debe estar ACTIVO o datos inválidos");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Editar mantenimiento</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Equipo *</label>
    <select name="id_equipo" required>
      <?php foreach ($equipos as $e): ?>
        <option value="<?php echo (int)$e["id_equipo"]; ?>"
          <?php echo ((int)$m["id_equipo"]===(int)$e["id_equipo"])?"selected":""; ?>>
          <?php echo htmlspecialchars($e["cliente"] . " | " . $e["marca"] . " " . $e["modelo"] . " | " . $e["num_serie"] . " (" . $e["estado"] . ")"); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Fecha *</label>
    <input type="date" name="fecha" required value="<?php echo htmlspecialchars($m["fecha"]); ?>">

    <label>Tipo *</label>
    <select name="tipo" required>
      <?php foreach ($tipos as $t): ?>
        <option value="<?php echo $t; ?>" <?php echo ($m["tipo"]===$t)?"selected":""; ?>>
          <?php echo $t; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Descripción *</label>
    <input type="text" name="descripcion" required value="<?php echo htmlspecialchars($m["descripcion"]); ?>">

    <label>Resultado</label>
    <select name="resultado">
      <?php foreach ($resultados as $r): ?>
        <option value="<?php echo $r; ?>" <?php echo ($m["resultado"]===$r)?"selected":""; ?>>
          <?php echo $r; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Técnico *</label>
    <select name="id_tecnico" required>
      <?php foreach ($tecnicos as $t): ?>
        <option value="<?php echo (int)$t["id_usuario"]; ?>"
          <?php echo ((int)$m["id_tecnico"]===(int)$t["id_usuario"])?"selected":""; ?>>
          <?php echo htmlspecialchars($t["nombre"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Regresar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
