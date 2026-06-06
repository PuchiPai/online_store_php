<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: add.php");
    exit;
}

$name = trim($_POST["name"] ?? '');

if ($name === '') {
    die("Введите название категории");
}

$stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    header("Location: list.php");
    exit;
}

die("Ошибка добавления категории");