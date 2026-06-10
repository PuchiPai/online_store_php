<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$cart = $_SESSION["cart"] ?? [];
$products = [];
$total = 0;

if (!empty($cart)) {
    $ids = array_map('intval', array_keys($cart));
    $ids_sql = implode(',', $ids);

    $result = $conn->query("
        SELECT id, name, price, image
        FROM products
        WHERE id IN ($ids_sql)
    ");

    while ($row = $result->fetch_assoc()) {
        $products[(int)$row["id"]] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина — TechStore</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
<div class="profile-container">
    <h1 class="cart-title">Корзина</h1>

    <?php if (empty($cart)): ?>
        <div class="profile-card">
            <p>🛒 В корзине пока пусто</p>
            <a href="catalog.php" class="auth-btn">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <form action="../api/update_cart.php" method="post" class="cart-form">
            <div class="cart-list">
                <?php foreach ($cart as $product_id => $quantity): ?>
                    <?php
                    $product_id = (int)$product_id;
                    $quantity = (int)$quantity;

                    if (!isset($products[$product_id])) continue;

                    $product = $products[$product_id];
                    $sum = (float)$product["price"] * $quantity;
                    $total += $sum;
                    ?>
                    <div class="cart-row">
                        <div class="cart-item-img">
                            <?php if (!empty($product["image"])): ?>
                                <img src="../uploads/products/<?= h($product["image"]) ?>" alt="<?= h($product["name"]) ?>">
                            <?php else: ?>
                                <div class="no-img">📷</div>
                            <?php endif; ?>
                        </div>
                        <div class="cart-item-info">
                            <h3 class="cart-item-title"><?= h($product["name"]) ?></h3>
                            <p class="cart-item-price" data-price="<?= (float)$product["price"] ?>"><?= number_format($product["price"], 0, '', ' ') ?> ₽</p>
                        </div>
                        <div class="cart-item-quantity">
                            <button type="button" class="qty-btn minus">−</button>
                            <input type="number" name="qty[<?= $product_id ?>]" value="<?= $quantity ?>" min="0" class="qty-input">
                            <button type="button" class="qty-btn plus">+</button>
                        </div>
                        <div class="cart-item-total"><?= number_format($sum, 0, '', ' ') ?> ₽</div>
                        <div class="cart-item-remove">
                            <a href="../api/remove_from_cart.php?id=<?= $product_id ?>" class="remove-link" onclick="return confirm('Удалить товар?')">✕</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-footer">
                <div class="cart-total">
                    <span>Итого:</span>
                    <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
                </div>
                <div class="cart-actions">
                    <button type="submit" class="auth-btn update-btn">Обновить корзину</button>
                    <a href="checkout.php" class="cart-btn auth-btn">Оформить заказ →</a>
                </div>
            </div>
        </form>

        <div class="cart-links">
            <a href="catalog.php" class="link-back">← Продолжить покупки</a>
            <a href="profile.php" class="link-profile">Профиль</a>
            <a href="logout.php" class="link-logout">Выйти</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function formatPrice(value) {
        return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
    }

    function recalcCart() {
        let total = 0;
        document.querySelectorAll('.cart-row').forEach(row => {
            const input = row.querySelector('.qty-input');
            const priceEl = row.querySelector('.cart-item-price');
            const itemTotalEl = row.querySelector('.cart-item-total');

            if (!input || !priceEl || !itemTotalEl) return;

            const qty = parseInt(input.value) || 0;
            const price = parseFloat(priceEl.dataset.price) || 0;
            const sum = qty * price;

            itemTotalEl.textContent = formatPrice(sum);
            total += sum;
        });

        const totalEl = document.querySelector('.cart-total strong');
        if (totalEl) totalEl.textContent = formatPrice(total);
    }

    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            let val = parseInt(input.value) || 0;

            if (this.classList.contains('plus')) val++;
            else if (this.classList.contains('minus') && val > 0) val--;

            input.value = val;
            recalcCart();
        });
    });

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', recalcCart);
    });

    recalcCart();
</script>
</body>
</html>