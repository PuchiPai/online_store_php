<?php
session_start();
require_once __DIR__ . '/../../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: list.php");
    exit;
}

$id = (int)($_POST["id"] ?? 0);
$name = trim($_POST["name"] ?? '');

if ($id <= 0 || $name === '') {
    die("Ошибка данных");
}

$stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
$stmt->bind_param("si", $name, $id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit;
}

die("Ошибка обновления категории");