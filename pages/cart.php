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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                <div class="cart-item">
                    <?php foreach ($cart as $product_id => $quantity): ?>
                        <?php
                        $product_id = (int)$product_id;
                        $quantity = (int)$quantity;

                        if (!isset($products[$product_id])) {
                            continue;
                        }

                        $product = $products[$product_id];
                        $sum = (float)$product["price"] * $quantity;
                        $total += $sum;
                        ?>

                        <div class="cart-item">
                            <div class="cart-item-img">
                                <?php if (!empty($product["image"])): ?>
                                    <img src="../uploads/products/<?= h($product["image"]) ?>" alt="<?= h($product["name"]) ?>">
                                <?php else: ?>
                                    <div class="no-img">📷</div>
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-info">
                                <h3 class="cart-item-title"><?= h($product["name"]) ?></h3>
                                <p class="cart-item-price"><?= number_format($product["price"], 0, '', ' ') ?> ₽</p>
                            </div>
                            <div class="cart-item-quantity">
                                <button type="button" class="qty-btn minus" data-id="<?= $product_id ?>">−</button>
                                <input type="number" name="qty[<?= $product_id ?>]" value="<?= $quantity ?>" min="0" class="qty-input" data-id="<?= $product_id ?>">
                                <button type="button" class="qty-btn plus" data-id="<?= $product_id ?>">+</button>
                            </div>
                            <div class="cart-item-total">
                                <?= number_format($sum, 0, '', ' ') ?> ₽
                            </div>
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
        // Простой скрипт для кнопок +/-
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const input = this.parentElement.querySelector('.qty-input');
                let val = parseInt(input.value) || 0;
                if (this.classList.contains('plus')) {
                    val++;
                } else if (this.classList.contains('minus') && val > 0) {
                    val--;
                }
                input.value = val;
                // Не отправляем форму автоматически — пользователь сам нажмёт «Обновить корзину»
            });
        });
    </script>

    <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem;">
    <a href="catalog.php" class="profile-link">← Продолжить покупки</a>
    <a href="profile.php" class="profile-link">Профиль</a>
    <a href="logout.php" class="profile-link logout">Выйти</a>
</div>

</body>
</html>