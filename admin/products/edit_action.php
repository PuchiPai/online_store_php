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
$description = trim($_POST["description"] ?? '');
$price = (float)($_POST["price"] ?? 0);
$category_id = !empty($_POST["category_id"]) ? (int)$_POST["category_id"] : null;

if ($id <= 0 || $name === '' || $price <= 0) {
    die("Ошибка данных");
}

$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Товар не найден");
}

$product = $result->fetch_assoc();
$image_name = $product["image"];

if (!empty($_FILES["image"]["name"])) {
    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $image_name = time() . "_" . rand(1000, 9999) . "." . $ext;
    move_uploaded_file($_FILES["image"]["tmp_name"], "../../uploads/products/" . $image_name);
}

if ($category_id === null) {
    if (!empty($_FILES["image"]["name"])) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category_id = NULL WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $image_name, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = NULL WHERE id = ?");
        $stmt->bind_param("ssdi", $name, $description, $price, $id);
    }
} else {
    if (!empty($_FILES["image"]["name"])) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdsii", $name, $description, $price, $image_name, $category_id, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdii", $name, $description, $price, $category_id, $id);
    }
}

if ($stmt->execute()) {
    header("Location: list.php");
    exit;
}

die("Ошибка обновления товара");