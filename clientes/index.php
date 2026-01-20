<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";

$q = trim($_GET["q"] ?? "");

if ($q !== "") {
  $stmt = $conexion->prepare("SELECT * FROM clientes
    WHERE activo=1 AND (nombre LIKE :q OR telefono LIKE :q OR email LIKE :q)
    ORDER BY id_cliente DESC");
  $stmt->execute([":q" => "%$q%"]);
} else {
  $stmt = $conexion->query("SELECT * FROM clientes WHERE activo=1 ORDER BY id_cliente DESC");
}
$clientes = $stmt->fetchAll();

$ok  = $_GET["ok"] ?? "";
$err = $_GET["err"] ?? "";
?>
<div class="card">
  <h2>Clientes</h2>

  <?php if ($ok): ?><div class="msg ok"><?php echo htmlspecialchars($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <form method="GET" style="display:flex;gap:10px;align-items:end;">
    <div style="flex:1;">
      <label>Buscar</label>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
    </div>
    <button class="btn" type="submit">Buscar</button>
    <a class="btn2" href="crear.php">+ Nuevo</a>
  </form>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th><th>Nombre</th><th>Teléfono</th><th>Email</th><th>Dirección</th><th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clientes as $c): ?>
      <tr>
        <td><?php echo (int)$c["id_cliente"]; ?></td>
        <td><?php echo htmlspecialchars($c["nombre"]); ?></td>
        <td><?php echo htmlspecialchars($c["telefono"]); ?></td>
        <td><?php echo htmlspecialchars($c["email"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($c["direccion"] ?? ""); ?></td>
        <td>
          <a href="editar.php?id=<?php echo (int)$c["id_cliente"]; ?>">Editar</a> |
          <a href="eliminar.php?id=<?php echo (int)$c["id_cliente"]; ?>"
             onclick="return confirm('¿Dar de baja este cliente?');">Baja</a>
        </td>
      </tr>
    <?php endforeach; ?>

    <?php if (count($clientes) === 0): ?>
      <tr><td colspan="6">Sin registros.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
