<?php
require_once __DIR__ . "/../auth/auth_guard.php";
require_once __DIR__ . "/../config/conexion.php";

$id = (int)($_GET["id"] ?? 0);
$estado = trim($_GET["estado"] ?? "");

$permitidos = ["pendiente","en_proceso","finalizado","cancelado"];

if ($id<=0 || !in_array($estado, $permitidos, true)) {
  header("Location: index.php?err=Parámetros inválidos");
  exit;
}

$stmt = $conexion->prepare("UPDATE servicios SET estado=:e WHERE id_servicio=:id");
$stmt->execute([":e"=>$estado, ":id"=>$id]);

// Trigger trg_bitacora_estado_servicio se ejecuta aquí ✅
header("Location: index.php?ok=Estado actualizado");
exit;
