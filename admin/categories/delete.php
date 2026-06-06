<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    die("Некорректный ID");
}

$stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: list.php");
    exit;
}

die("Ошибка удаления категории");