<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die('Корзина пуста');
}

$ids = array_map('intval', array_keys($cart));
$ids_sql = implode(',', $ids);

$result = $conn->query("
    SELECT id, name, price
    FROM products
    WHERE id IN ($ids_sql)
");

$items = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $items[(int)$row['id']] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа — TechStore</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
<div class="profile-container">
    <h1 class="cart-title">Оформление заказа</h1>

    <div class="profile-card">
        <form id="checkoutForm" action="../api/create_order.php" method="post">
            <h3>Состав заказа</h3>

            <?php foreach ($cart as $product_id => $quantity): ?>
                <?php $product_id = (int)$product_id; ?>
                <?php if (!isset($items[$product_id])) continue; ?>
                <?php
                $sum = (float)$items[$product_id]['price'] * (int)$quantity;
                $total += $sum;
                ?>
                <p style="margin: 0.5rem 0;">
                    <?= h($items[$product_id]['name']) ?> —
                    <?= (int)$quantity ?> шт × <?= number_format((float)$items[$product_id]['price'], 0, '', ' ') ?> ₽ =
                    <?= number_format($sum, 0, '', ' ') ?> ₽
                </p>
            <?php endforeach; ?>

            <hr>

            <p class="cart-total" style="margin: 1rem 0;">
                <span>Итого:</span>
                <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
            </p>

            <button type="submit" class="auth-btn update-btn">Подтвердить заказ</button>
        </form>
    </div>

    <div class="cart-links">
        <a href="cart.php" class="link-back">← Назад в корзину</a>
        <a href="catalog.php" class="link-profile">Продолжить покупки</a>
        <a href="profile.php" class="link-profile">Профиль</a>
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('checkoutForm');

        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.ok) {
                    if (data.message) {
                        alert(data.message);
                    }

                    window.location.href = data.redirect || 'orders.php';
                } else {
                    alert(data.message || 'Не удалось оформить заказ');
                }
            } catch (error) {
                console.error(error);
                alert('Ошибка сети при оформлении заказа');
            }
        });
    })();
</script>
</body>
</html>