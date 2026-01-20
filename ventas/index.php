<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$q      = trim($_GET["q"] ?? "");
$estado = trim($_GET["estado"] ?? "");
$desde  = trim($_GET["desde"] ?? "");
$hasta  = trim($_GET["hasta"] ?? "");

$sql = "SELECT v.*, c.nombre AS cliente, u.nombre AS vendedor
        FROM ventas v
        INNER JOIN clientes c ON c.id_cliente = v.id_cliente
        INNER JOIN usuarios u ON u.id_usuario = v.id_vendedor
        WHERE 1=1 ";
$params = [];

if ($q !== "") {
  $sql .= " AND (c.nombre LIKE :q OR v.concepto LIKE :q) ";
  $params[":q"] = "%$q%";
}
if ($estado !== "") {
  $sql .= " AND v.estado = :e ";
  $params[":e"] = $estado;
}
if ($desde !== "") {
  $sql .= " AND v.fecha >= :d ";
  $params[":d"] = $desde;
}
if ($hasta !== "") {
  $sql .= " AND v.fecha <= :h ";
  $params[":h"] = $hasta;
}

$sql .= " ORDER BY v.id_venta DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ok  = $_GET["ok"] ?? "";
$err = $_GET["err"] ?? "";

$estados = ["cotizado","vendido","entregado","cancelado"];
?>
<div class="card">
  <h2>Ventas</h2>

  <?php if ($ok): ?><div class="msg ok"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="GET" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap;">
    <div style="flex:1;min-width:260px;">
      <label>Buscar (cliente o concepto)</label>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
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
    <a class="btn2" href="crear.php">+ Nueva</a>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Concepto</th>
      <th>Monto</th>
      <th>Fecha</th>
      <th>Estado</th>
      <th>Vendedor</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $v): ?>
      <tr>
        <td><?php echo (int)$v["id_venta"]; ?></td>
        <td><?php echo htmlspecialchars($v["cliente"]); ?></td>
        <td><?php echo htmlspecialchars($v["concepto"]); ?></td>
        <td>$<?php echo htmlspecialchars($v["monto"]); ?></td>
        <td><?php echo htmlspecialchars($v["fecha"]); ?></td>
        <td><?php echo htmlspecialchars($v["estado"]); ?></td>
        <td><?php echo htmlspecialchars($v["vendedor"]); ?></td>
        <td>
          <a href="editar.php?id=<?php echo (int)$v["id_venta"]; ?>">Editar</a>
          |
          <a href="cambiar_estado.php?id=<?php echo (int)$v["id_venta"]; ?>&estado=vendido">Marcar vendido</a>
          |
          <a href="cambiar_estado.php?id=<?php echo (int)$v["id_venta"]; ?>&estado=entregado">Entregado</a>
          |
          <a href="eliminar.php?id=<?php echo (int)$v["id_venta"]; ?>"
             onclick="return confirm('¿Cancelar esta venta?');">Cancelar</a>
        </td>
      </tr>
    <?php endforeach; ?>

    <?php if (count($rows)===0): ?>
      <tr><td colspan="8">Sin registros.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
