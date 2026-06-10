<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
requireAdmin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: list.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

$allowed = ['new', 'processing', 'done', 'cancelled'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    die("Некорректные данные");
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

header("Location: view.php?id=" . $id);
exit;