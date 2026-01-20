<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$q     = trim($_GET["q"] ?? "");
$tabla = trim($_GET["tabla"] ?? "");
$desde = trim($_GET["desde"] ?? "");
$hasta = trim($_GET["hasta"] ?? "");

$sql = "SELECT * FROM bitacora WHERE 1=1 ";
$params = [];

if ($q !== "") {
  $sql .= " AND (accion LIKE :q OR descripcion LIKE :q) ";
  $params[":q"] = "%$q%";
}

if ($tabla !== "") {
  $sql .= " AND tabla_afectada = :t ";
  $params[":t"] = $tabla;
}

if ($desde !== "") {
  $sql .= " AND fecha >= :d ";
  $params[":d"] = $desde . " 00:00:00";
}

if ($hasta !== "") {
  $sql .= " AND fecha <= :h ";
  $params[":h"] = $hasta . " 23:59:59";
}

$sql .= " ORDER BY id_bitacora DESC LIMIT 200";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tablas = ["servicios","equipos","mantenimientos","ventas","clientes"];
?>
<div class="card">
  <h2>Bitácora</h2>
  <p>Registros automáticos de cambios (por ejemplo, cambios de estado en servicios).</p>

  <form method="GET" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">
    <div style="flex:1;min-width:260px;">
      <label>Buscar (acción o descripción)</label>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
    </div>

    <div style="min-width:220px;">
      <label>Tabla</label>
      <select name="tabla">
        <option value="">(Todas)</option>
        <?php foreach ($tablas as $t): ?>
          <option value="<?php echo $t; ?>" <?php echo ($tabla===$t)?"selected":""; ?>>
            <?php echo $t; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="min-width:180px;">
      <label>Desde</label>
      <input type="date" name="desde" value="<?php echo htmlspecialchars($desde); ?>">
    </div>

    <div style="min-width:180px;">
      <label>Hasta</label>
      <input type="date" name="hasta" value="<?php echo htmlspecialchars($hasta); ?>">
    </div>

    <button class="btn" type="submit">Filtrar</button>
    <a class="btn2" href="index.php">Limpiar</a>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Tabla</th>
      <th>Acción</th>
      <th>Descripción</th>
      <th>Fecha</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php echo (int)$r["id_bitacora"]; ?></td>
        <td><?php echo htmlspecialchars($r["tabla_afectada"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($r["accion"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($r["descripcion"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($r["fecha"] ?? ""); ?></td>
      </tr>
    <?php endforeach; ?>

    <?php if (count($rows) === 0): ?>
      <tr><td colspan="5">Sin registros.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
