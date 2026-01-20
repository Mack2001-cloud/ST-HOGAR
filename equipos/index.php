<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$q = trim($_GET["q"] ?? "");

$sql = "SELECT e.*, c.nombre AS cliente
        FROM equipos e
        INNER JOIN clientes c ON c.id_cliente = e.id_cliente
        WHERE 1=1 ";
$params = [];

if ($q !== "") {
  $sql .= " AND (c.nombre LIKE :q OR e.marca LIKE :q OR e.modelo LIKE :q OR e.num_serie LIKE :q) ";
  $params[":q"] = "%$q%";
}

$sql .= " ORDER BY e.id_equipo DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ok  = $_GET["ok"] ?? "";
$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Equipos</h2>

  <?php if ($ok): ?><div class="msg ok"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="GET" style="display:flex;gap:10px;align-items:end;">
    <div style="flex:1;">
      <label>Buscar (cliente, marca, modelo, serie)</label>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
    </div>
    <button class="btn" type="submit">Buscar</button>
    <a class="btn2" href="crear.php">+ Nuevo</a>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Categoría</th>
      <th>Marca</th>
      <th>Modelo</th>
      <th>Serie</th>
      <th>Ubicación</th>
      <th>Instalación</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($equipos as $e): ?>
    <tr>
      <td><?php echo (int)$e["id_equipo"]; ?></td>
      <td><?php echo htmlspecialchars($e["cliente"]); ?></td>
      <td><?php echo htmlspecialchars($e["categoria"]); ?></td>
      <td><?php echo htmlspecialchars($e["marca"]); ?></td>
      <td><?php echo htmlspecialchars($e["modelo"]); ?></td>
      <td><?php echo htmlspecialchars($e["num_serie"]); ?></td>
      <td><?php echo htmlspecialchars($e["ubicacion"] ?? ""); ?></td>
      <td><?php echo htmlspecialchars($e["fecha_instalacion"]); ?></td>
      <td><?php echo htmlspecialchars($e["estado"]); ?></td>
      <td>
        <a href="editar.php?id=<?php echo (int)$e["id_equipo"]; ?>">Editar</a>
        | <a href="eliminar.php?id=<?php echo (int)$e["id_equipo"]; ?>"
             onclick="return confirm('¿Eliminar equipo?');">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>

  <?php if (count($equipos)===0): ?>
    <tr><td colspan="10">Sin registros.</td></tr>
  <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
