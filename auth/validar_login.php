<?php
session_start();
require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/conexion.php";

$usuario  = trim($_POST["usuario"] ?? "");
$password = $_POST["password"] ?? "";

if ($usuario === "" || $password === "") {
  header("Location: login.php?error=1");
  exit;
}

$stmt = $conexion->prepare("SELECT id_usuario, nombre, usuario, password_hash, rol, activo
                            FROM usuarios
                            WHERE usuario = :u
                            LIMIT 1");
$stmt->execute([":u" => $usuario]);
$u = $stmt->fetch();

if (!$u || (int)$u["activo"] !== 1) {
  header("Location: login.php?error=2");
  exit;
}

if (!password_verify($password, $u["password_hash"])) {
  header("Location: login.php?error=3");
  exit;
}

session_regenerate_id(true);
$_SESSION["id_usuario"] = (int)$u["id_usuario"];
$_SESSION["nombre"]    = $u["nombre"];
$_SESSION["rol"]       = $u["rol"];

header("Location: " . BASE_URL . "/dashboard.php");
exit;
