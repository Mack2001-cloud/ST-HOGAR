<?php
require_once __DIR__ . "/config/conexion.php";

$nombre  = "Super Admin";
$usuario = "superadmin@pdo.com";
$pass    = "123456";
$hash    = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $conexion->prepare("DELETE FROM usuarios WHERE usuario = :u");
$stmt->execute([":u" => $usuario]);

$stmt = $conexion->prepare("INSERT INTO usuarios (nombre, usuario, password_hash, rol, activo)
                            VALUES (:n, :u, :h, 'admin', 1)");
$stmt->execute([":n" => $nombre, ":u" => $usuario, ":h" => $hash]);

echo "✅ Admin creado<br>";
echo "Usuario: <b>$usuario</b><br>";
echo "Pass: <b>$pass</b><br>";
echo "Hash: <code>$hash</code><br>";
