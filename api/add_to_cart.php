<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('../pages/catalog.php');
}

$product_id = (int)($_POST["product_id"] ?? 0);
$quantity = (int)($_POST["quantity"] ?? 1);

if ($product_id <= 0 || $quantity <= 0) {
    die("Некорректные данные");
}

$stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Товар не найден");
}

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (isset($_SESSION["cart"][$product_id])) {
    $_SESSION["cart"][$product_id] += $quantity;
} else {
    $_SESSION["cart"][$product_id] = $quantity;
}

redirect('../pages/cart.php');