<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    if (isAjaxRequest()) {
        jsonResponse(['ok' => false, 'message' => 'Корзина пуста'], 400);
    }
    die('Корзина пуста');
}

$user_id = currentUserId();
$items = [];
$total = 0;

foreach ($cart as $product_id => $quantity) {
    $product_id = (int)$product_id;
    $quantity = (int)$quantity;

    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        continue;
    }

    $product = $result->fetch_assoc();
    $price = (float)$product['price'];
    $sum = $price * $quantity;
    $total += $sum;

    $items[] = [
        'product_id' => (int)$product['id'],
        'name' => $product['name'],
        'quantity' => $quantity,
        'price' => $price
    ];
}

if (empty($items)) {
    if (isAjaxRequest()) {
        jsonResponse(['ok' => false, 'message' => 'Нет товаров для заказа'], 400);
    }
    die('Нет товаров для заказа');
}

$itemsJson = json_encode($items, JSON_UNESCAPED_UNICODE);

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, status, items_json, total_amount) VALUES (?, 'new', ?, ?)");
    $stmt->bind_param('isd', $user_id, $itemsJson, $total);
    $stmt->execute();

    $order_id = $conn->insert_id;

    $conn->commit();
    unset($_SESSION['cart']);

    if (isAjaxRequest()) {
        jsonResponse([
            'ok' => true,
            'message' => 'Заказ оформлен',
            'order_id' => $order_id,
            'redirect' => '/pages/orders.php'
        ]);
    }

    redirect('../pages/orders.php');
} catch (Exception $e) {
    $conn->rollback();

    if (isAjaxRequest()) {
        jsonResponse(['ok' => false, 'message' => 'Ошибка оформления заказа'], 500);
    }

    die('Ошибка оформления заказа');
}