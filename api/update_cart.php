<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('../pages/cart.php');
}

$qty = $_POST["qty"] ?? [];

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

foreach ($qty as $product_id => $quantity) {
    $product_id = (int)$product_id;
    $quantity = (int)$quantity;

    if ($product_id <= 0) {
        continue;
    }

    if ($quantity <= 0) {
        unset($_SESSION["cart"][$product_id]);
    } else {
        $_SESSION["cart"][$product_id] = $quantity;
    }
}

if (empty($_SESSION["cart"])) {
    unset($_SESSION["cart"]);
}

redirect('../pages/cart.php');