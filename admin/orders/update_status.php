<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: list.php");
    exit;
}

$id = (int)($_POST["id"] ?? 0);
$status = trim($_POST["status"] ?? '');

$allowed = ["new", "in_progress", "done", "cancelled"];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    die("Некорректные данные");
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    header("Location: view.php?id=" . $id);
    exit;
}

die("Ошибка изменения статуса");