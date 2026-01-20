<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

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
    header("Location: crear.php?err=Completa los campos obligatorios");
    exit;
  }

  try {
    $stmt = $conexion->prepare("INSERT INTO equipos
      (id_cliente,categoria,marca,modelo,num_serie,ubicacion,fecha_instalacion,estado)
      VALUES (:c,:cat,:m,:mo,:s,:u,:f,:e)");
    $stmt->execute([
      ":c"=>$id_cliente, ":cat"=>$categoria, ":m"=>$marca, ":mo"=>$modelo,
      ":s"=>$num_serie, ":u"=>$ubicacion, ":f"=>$fecha_inst, ":e"=>$estado
    ]);

    header("Location: index.php?ok=Equipo creado");
    exit;
  } catch (Throwable $e) {
    // num_serie duplicado = error de UNIQUE
    header("Location: crear.php?err=Error: número de serie duplicado o datos inválidos");
    exit;
  }
}

$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Nuevo equipo</h2>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="POST">
    <label>Cliente *</label>
    <select name="id_cliente" required>
      <option value="">Selecciona...</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?php echo (int)$c["id_cliente"]; ?>"><?php echo htmlspecialchars($c["nombre"]); ?></option>
      <?php endforeach; ?>
    </select>

    <label>Categoría *</label>
    <select name="categoria" required>
      <option value="">Selecciona...</option>
      <?php foreach ($categorias as $x): ?>
        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
      <?php endforeach; ?>
    </select>

    <label>Marca *</label>
    <input type="text" name="marca" required>

    <label>Modelo *</label>
    <input type="text" name="modelo" required>

    <label>Número de serie *</label>
    <input type="text" name="num_serie" required>

    <label>Ubicación</label>
    <input type="text" name="ubicacion">

    <label>Fecha de instalación *</label>
    <input type="date" name="fecha_instalacion" required>

    <label>Estado</label>
    <select name="estado">
      <?php foreach ($estados as $e): ?>
        <option value="<?php echo $e; ?>" <?php echo ($e==="activo")?"selected":""; ?>>
          <?php echo $e; ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button class="btn" type="submit">Guardar</button>
    <a class="btn2" href="index.php">Cancelar</a>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
