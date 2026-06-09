<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$product_id = (int)($_GET["id"] ?? 0);

if ($product_id > 0 && isset($_SESSION["cart"][$product_id])) {
    unset($_SESSION["cart"][$product_id]);
}

if (!empty($_SESSION["cart"]) && count($_SESSION["cart"]) === 0) {
    unset($_SESSION["cart"]);
}

redirect('../pages/cart.php');