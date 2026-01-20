<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../config/app.php";

if (!isset($_SESSION["id_usuario"])) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}
