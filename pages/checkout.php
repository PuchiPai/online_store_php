<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$cart = $_SESSION["cart"] ?? [];

if (empty($cart)) {
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <div class="checkout-container">
        <div class="checkout-empty">
            <p>🛒 Корзина пуста</p>
            <a href="catalog.php" class="auth-btn">Перейти в каталог</a>
        </div>
    </div>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
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

$success = false;
$error = null;

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
        $success = true;
        $order_id_success = $order_id;
    } catch (Throwable $e) {
        $conn->rollback();
        $error = "Ошибка оформления заказа: " . h($e->getMessage());
    }
}

// Подключаем хедер
require_once __DIR__ . '/../includes/header.php';
?>

<div class="checkout-container">
    <?php if ($success): ?>
        <div class="checkout-success">
            <h2>✅ Заказ успешно оформлен</h2>
            <p>Номер заказа: <strong><?= (int)$order_id_success ?></strong></p>
            <p><a href="catalog.php" class="auth-btn">Вернуться в каталог</a></p>
        </div>
    <?php elseif ($error): ?>
        <div class="checkout-error">
            <p>❌ <?= $error ?></p>
            <p><a href="cart.php">← Назад в корзину</a></p>
        </div>
    <?php else: ?>
        <h1 class="checkout-title">Оформление заказа</h1>
        <p class="checkout-back"><a href="cart.php">← Назад в корзину</a></p>

        <h3 class="checkout-subtitle">Состав заказа</h3>
        <div class="checkout-items-list">
            <?php foreach ($cart as $product_id => $quantity): ?>
                <?php $product_id = (int)$product_id; ?>
                <?php if (!isset($items[$product_id])) continue; ?>
                <div class="checkout-item">
                    <span class="checkout-item-name"><?= h($items[$product_id]["name"]) ?></span>
                    <span class="checkout-item-qty"><?= (int)$quantity ?> шт.</span>
                    <span class="checkout-item-price"><?= number_format($items[$product_id]["price"] * $quantity, 0, '', ' ') ?> ₽</span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-total">
            <span>Итого:</span>
            <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
        </div>

        <form method="post" class="checkout-form">
            <button type="submit" class="auth-btn checkout-submit">Подтвердить заказ</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>