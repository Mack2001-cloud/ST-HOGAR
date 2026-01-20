<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

// Equipos activos para no chocar con el trigger
$equipos = $conexion->query("
  SELECT e.id_equipo, e.num_serie, e.marca, e.modelo, e.estado, c.nombre AS cliente
  FROM equipos e
  INNER JOIN clientes c ON c.id_cliente = e.id_cliente
  WHERE e.estado='activo'
  ORDER BY c.nombre, e.id_equipo DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Técnicos
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
    header("Location: crear.php?err=Completa los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("INSERT INTO mantenimientos
      (id_equipo, fecha, tipo, descripcion, resultado, id_tecnico)
      VALUES (:eq,:f,:t,:d,:r,:tec)");
    $stmt->execute([
      ":eq"=>$id_equipo, ":f"=>$fecha, ":t"=>$tipo,
      ":d"=>$descripcion, ":r"=>$resultado, ":tec"=>$id_tecnico
    ]);

    header("Location: index.php?ok=Mantenimiento registrado");
    exit;
  } catch (Throwable $e) {
    // Aquí entra tu trigger si el equipo no está activo
    header("Location: crear.php?err=Error: el equipo debe estar ACTIVO o datos inválidos");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Nuevo mantenimiento</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Equipo (solo activos) *</label>
    <select name="id_equipo" required>
      <option value="">Selecciona...</option>
      <?php foreach ($equipos as $e): ?>
        <option value="<?php echo (int)$e["id_equipo"]; ?>">
          <?php echo htmlspecialchars($e["cliente"] . " | " . $e["marca"] . " " . $e["modelo"] . " | " . $e["num_serie"]); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Fecha *</label>
    <input type="date" name="fecha" required>

    <label>Tipo *</label>
    <select name="tipo" required>
      <option value="">Selecciona...</option>
      <?php foreach ($tipos as $t): ?>
        <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
      <?php endforeach; ?>
    </select>

    <label>Descripción *</label>
    <input type="text" name="descripcion" required>

    <label>Resultado</label>
    <select name="resultado">
      <?php foreach ($resultados as $r): ?>
        <option value="<?php echo $r; ?>" <?php echo ($r==="exitoso")?"selected":""; ?>>
          <?php echo $r; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Técnico *</label>
    <select name="id_tecnico" required>
      <option value="">Selecciona...</option>
      <?php foreach ($tecnicos as $t): ?>
        <option value="<?php echo (int)$t["id_usuario"]; ?>"><?php echo htmlspecialchars($t["nombre"]); ?></option>
      <?php endforeach; ?>
    </select>

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Cancelar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
