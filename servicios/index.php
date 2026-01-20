<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$q      = trim($_GET["q"] ?? "");
$tipo   = trim($_GET["tipo"] ?? "");
$estado = trim($_GET["estado"] ?? "");
$desde  = trim($_GET["desde"] ?? "");
$hasta  = trim($_GET["hasta"] ?? "");

$sql = "SELECT s.*, c.nombre AS cliente, u.nombre AS tecnico
        FROM servicios s
        INNER JOIN clientes c ON c.id_cliente = s.id_cliente
        INNER JOIN usuarios u ON u.id_usuario = s.id_tecnico
        WHERE 1=1 ";
$params = [];

if ($q !== "") {
  $sql .= " AND (c.nombre LIKE :q OR s.descripcion LIKE :q) ";
  $params[":q"] = "%$q%";
}
if ($tipo !== "") {
  $sql .= " AND s.tipo = :t ";
  $params[":t"] = $tipo;
}
if ($estado !== "") {
  $sql .= " AND s.estado = :e ";
  $params[":e"] = $estado;
}
if ($desde !== "") {
  $sql .= " AND s.fecha >= :d ";
  $params[":d"] = $desde;
}
if ($hasta !== "") {
  $sql .= " AND s.fecha <= :h ";
  $params[":h"] = $hasta;
}

$sql .= " ORDER BY s.id_servicio DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ok  = $_GET["ok"] ?? "";
$err = $_GET["err"] ?? "";

$tipos = ["CCTV","Automatizacion","POS","Redes","Otro"];
$estados = ["pendiente","en_proceso","finalizado","cancelado"];
?>
<div class="card">
  <h2>Servicios</h2>

  <?php if ($ok): ?><div class="msg ok"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="GET" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">
    <div style="flex:1;min-width:260px;">
      <label>Buscar (cliente o descripción)</label>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
    </div>

    <div style="min-width:200px;">
      <label>Tipo</label>
      <select name="tipo">
        <option value="">(Todos)</option>
        <?php foreach ($tipos as $t): ?>
          <option value="<?php echo $t; ?>" <?php echo ($tipo===$t)?"selected":""; ?>>
            <?php echo $t; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="min-width:200px;">
      <label>Estado</label>
      <select name="estado">
        <option value="">(Todos)</option>
        <?php foreach ($estados as $e): ?>
          <option value="<?php echo $e; ?>" <?php echo ($estado===$e)?"selected":""; ?>>
            <?php echo $e; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="min-width:170px;">
      <label>Desde</label>
      <input type="date" name="desde" value="<?php echo htmlspecialchars($desde); ?>">
    </div>

    <div style="min-width:170px;">
      <label>Hasta</label>
      <input type="date" name="hasta" value="<?php echo htmlspecialchars($hasta); ?>">
    </div>

    <button class="btn" type="submit">Filtrar</button>
    <a class="btn2" href="crear.php">+ Nuevo</a>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Tipo</th>
      <th>Fecha</th>
      <th>Estado</th>
      <th>Técnico</th>
      <th>Costo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $s): ?>
      <tr>
        <td><?php echo (int)$s["id_servicio"]; ?></td>
        <td><?php echo htmlspecialchars($s["cliente"]); ?></td>
        <td><?php echo htmlspecialchars($s["tipo"]); ?></td>
        <td><?php echo htmlspecialchars($s["fecha"]); ?></td>
        <td><?php echo htmlspecialchars($s["estado"]); ?></td>
        <td><?php echo htmlspecialchars($s["tecnico"]); ?></td>
        <td>$<?php echo htmlspecialchars($s["costo"]); ?></td>
        <td>
          <a href="editar.php?id=<?php echo (int)$s["id_servicio"]; ?>">Editar</a>
          |
          <a href="cambiar_estado.php?id=<?php echo (int)$s["id_servicio"]; ?>&estado=en_proceso">En proceso</a>
          |
          <a href="cambiar_estado.php?id=<?php echo (int)$s["id_servicio"]; ?>&estado=finalizado">Finalizar</a>
          |
          <a href="eliminar.php?id=<?php echo (int)$s["id_servicio"]; ?>"
             onclick="return confirm('¿Cancelar este servicio?');">Cancelar</a>
        </td>
      </tr>
    <?php endforeach; ?>

    <?php if (count($rows)===0): ?>
      <tr><td colspan="8">Sin registros.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
