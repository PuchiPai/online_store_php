<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$cart = $_SESSION["cart"] ?? [];

if (empty($cart)) {
    die("Корзина пуста");
}

$items = [];
$total = 0;

$ids = array_map('intval', array_keys($cart));
$ids_sql = implode(',', $ids);

$result = $conn->query("
    SELECT id, name, price
    FROM products
    WHERE id IN ($ids_sql)
");

while ($row = $result->fetch_assoc()) {
    $items[(int)$row["id"]] = $row;
}

foreach ($cart as $product_id => $quantity) {
    $product_id = (int)$product_id;
    $quantity = (int)$quantity;

    if (isset($items[$product_id])) {
        $total += ((float)$items[$product_id]["price"] * $quantity);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = currentUserId();

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, status) VALUES (?, 'new')");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $order_id = $conn->insert_id;

        $stmt_product = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt_item = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($cart as $product_id => $quantity) {
            $product_id = (int)$product_id;
            $quantity = (int)$quantity;

            $stmt_product->bind_param("i", $product_id);
            $stmt_product->execute();
            $product_result = $stmt_product->get_result();

            if ($product_result->num_rows === 0) {
                throw new Exception("Товар не найден");
            }

            $product = $product_result->fetch_assoc();
            $price = (float)$product["price"];

            $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt_item->execute();
        }

        $conn->commit();
        unset($_SESSION["cart"]);

        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Заказ оформлен</title>
        </head>
        <body>
        <h2>Заказ успешно оформлен</h2>
        <p>Номер заказа: <?= (int)$order_id ?></p>
        <p><a href="catalog.php">Вернуться в каталог</a></p>
        </body>
        </html>
        <?php
        exit;
    } catch (Throwable $e) {
        $conn->rollback();
        die("Ошибка оформления заказа: " . h($e->getMessage()));
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление заказа</title>
</head>
<body>
<h2>Оформление заказа</h2>

<p><a href="cart.php">← Назад в корзину</a></p>

<h3>Состав заказа</h3>
<ul>
    <?php foreach ($cart as $product_id => $quantity): ?>
        <?php $product_id = (int)$product_id; ?>
        <?php if (!isset($items[$product_id])) continue; ?>
        <li>
            <?= h($items[$product_id]["name"]) ?> —
            <?= (int)$quantity ?> шт.
        </li>
    <?php endforeach; ?>
</ul>

<p><b>Итого: <?= $total ?> ₽</b></p>

<form method="post">
    <button type="submit">Подтвердить заказ</button>
</form>
</body>
</html>