<?php
session_start();
require_once __DIR__ . '/../../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: add.php");
    exit;
}

$name = trim($_POST["name"] ?? '');
$description = trim($_POST["description"] ?? '');
$price = (float)($_POST["price"] ?? 0);
$category_id = !empty($_POST["category_id"]) ? (int)$_POST["category_id"] : null;
$image_name = null;

if ($name === '' || $price <= 0) {
    die("Заполните обязательные поля");
}

if (!empty($_FILES["image"]["name"])) {
    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $image_name = time() . "_" . rand(1000, 9999) . "." . $ext;
    move_uploaded_file($_FILES["image"]["tmp_name"], "../../uploads/products/" . $image_name);
}

if ($category_id === null) {
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, NULL)");
    $stmt->bind_param("ssds", $name, $description, $price, $image_name);
} else {
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_name, $category_id);
}

if ($stmt->execute()) {
    header("Location: list.php");
    exit;
}

die("Ошибка добавления товара");