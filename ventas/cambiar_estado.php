<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";

$id = (int)($_GET["id"] ?? 0);
$estado = trim($_GET["estado"] ?? "");

$permitidos = ["cotizado","vendido","entregado","cancelado"];

if ($id<=0 || !in_array($estado, $permitidos, true)) {
  header("Location: index.php?err=Parámetros inválidos");
  exit;
}

$stmt = $conexion->prepare("UPDATE ventas SET estado=:e WHERE id_venta=:id");
$stmt->execute([":e"=>$estado, ":id"=>$id]);

header("Location: index.php?ok=Estado actualizado");
exit;
