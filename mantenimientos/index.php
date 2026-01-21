<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$q = trim($_GET["q"] ?? "");

$sql = "SELECT m.*, 
               e.num_serie, e.marca, e.modelo, e.estado AS estado_equipo,
               c.nombre AS cliente,
               u.nombre AS tecnico
        FROM mantenimientos m
        INNER JOIN equipos e ON e.id_equipo = m.id_equipo
        INNER JOIN clientes c ON c.id_cliente = e.id_cliente
        INNER JOIN usuarios u ON u.id_usuario = m.id_tecnico
        WHERE 1=1 ";
$params = [];

if ($q !== "") {
  $sql .= " AND (c.nombre LIKE :q OR e.num_serie LIKE :q OR e.marca LIKE :q OR e.modelo LIKE :q OR u.nombre LIKE :q) ";
  $params[":q"] = "%$q%";
}

$sql .= " ORDER BY m.id_mantenimiento DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ok  = $_GET["ok"] ?? "";
$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Mantenimientos</h2>

  <?php if ($ok): ?><div class="msg ok"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="GET" class="form form--filters">
    <div class="form-field grow">
      <label>Buscar (cliente, serie, técnico)</label>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
    </div>
    <div class="form-actions">
      <button class="btn" type="submit">Buscar</button>
      <a class="btn2" href="crear.php">+ Nuevo</a>
    </div>
  </form>
</div>

<div class="table-card">
  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Equipo</th>
          <th>Estado equipo</th>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Resultado</th>
          <th>Técnico</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?php echo (int)$r["id_mantenimiento"]; ?></td>
            <td><?php echo htmlspecialchars($r["cliente"]); ?></td>
            <td><?php echo htmlspecialchars($r["marca"]." ".$r["modelo"]." / ".$r["num_serie"]); ?></td>
            <td><?php echo htmlspecialchars($r["estado_equipo"]); ?></td>
            <td><?php echo htmlspecialchars($r["fecha"]); ?></td>
            <td><?php echo htmlspecialchars($r["tipo"]); ?></td>
            <td><?php echo htmlspecialchars($r["resultado"]); ?></td>
            <td><?php echo htmlspecialchars($r["tecnico"]); ?></td>
            <td>
              <div class="table-actions">
                <a href="editar.php?id=<?php echo (int)$r["id_mantenimiento"]; ?>">Editar</a>
                <a href="eliminar.php?id=<?php echo (int)$r["id_mantenimiento"]; ?>"
                   onclick="return confirm('¿Eliminar mantenimiento?');">Eliminar</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (count($rows)===0): ?>
          <tr><td colspan="9">Sin registros.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
