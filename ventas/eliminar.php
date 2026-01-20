<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";

$id = (int)($_GET["id"] ?? 0);
if ($id<=0) { header("Location: index.php?err=ID inválido"); exit; }

$stmt = $conexion->prepare("UPDATE ventas SET estado='cancelado' WHERE id_venta=:id");
$stmt->execute([":id"=>$id]);

header("Location: index.php?ok=Venta cancelada");
exit;
