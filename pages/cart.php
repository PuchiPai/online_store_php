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

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="cart-container">
    <h1 class="cart-title">Корзина</h1>

    <?php if (empty($cart)): ?>
        <div class="cart-empty">
            <div class="cart-empty-icon">🛒</div>
            <div class="cart-empty-title">В корзине пока пусто</div>
            <div class="cart-empty-text">Добавьте товары из каталога, чтобы оформить заказ</div>
            <a href="catalog.php" class="auth-btn">Перейти в каталог</a>
        </div>
    <?php else: ?>

        <form action="../api/update_cart.php" method="post" class="cart-form">

            <?php foreach ($cart as $product_id => $quantity):
                $product_id = (int)$product_id;
                $quantity = (int)$quantity;

                if (!isset($products[$product_id])) continue;

                $product = $products[$product_id];
                $sum = (float)$product["price"] * $quantity;
                $total += $sum;
            ?>
                <div class="cart-item-card">

                    <div class="cart-item-img">
                        <?php if (!empty($product["image"]) && file_exists("../uploads/products/" . $product["image"])): ?>
                            <img src="../uploads/products/<?= h($product["image"]) ?>" alt="<?= h($product["name"]) ?>">
                        <?php else: ?>
                            <img src="/assets/images/no-image.png" alt="Нет фото">
                        <?php endif; ?>
                    </div>

                    <div class="cart-item-info">
                        <h3 class="cart-item-title"><?= h($product["name"]) ?></h3>
                        <p class="cart-item-price"><?= number_format($product["price"], 0, '', ' ') ?> ₽</p>
                    </div>

                    <div class="cart-item-quantity">
                        <button type="button" class="qty-btn minus">−</button>
                        <input type="number"
                               name="qty[<?= $product_id ?>]"
                               value="<?= $quantity ?>"
                               min="0"
                               class="qty-input">
                        <button type="button" class="qty-btn plus">+</button>
                    </div>

                    <div class="cart-item-total">
                        <?= number_format($sum, 0, '', ' ') ?> ₽
                    </div>

                    <div class="cart-item-remove">
                        <a href="../api/remove_from_cart.php?id=<?= $product_id ?>"
                           class="remove-link"
                           onclick="return confirm('Удалить товар?')">✕</a>
                    </div>

                </div>
            <?php endforeach; ?>

            <div class="cart-footer">
                <div class="cart-total">
                    <span>Итого:</span>
                    <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
                </div>

                <div class="cart-actions">
                
                    <a href="checkout.php" class="auth-btn cart-checkout">
                        Оформить заказ →
                    </a>
                </div>
            </div>

        </form>

    <?php endif; ?>
</div>

<script>
function formatPrice(value) {
    return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
}

function recalcCart() {
    let total = 0;

    document.querySelectorAll('.cart-item-card').forEach(card => {
        const input = card.querySelector('.qty-input');
        const priceEl = card.querySelector('.cart-item-price');
        const totalEl = card.querySelector('.cart-item-total');

        const qty = parseInt(input.value) || 0;
        const price = parseFloat(priceEl.textContent.replace(/[^\d]/g, '')) || 0;

        const sum = qty * price;

        totalEl.textContent = formatPrice(sum);
        total += sum;
    });

    document.querySelector('.cart-total strong').textContent = formatPrice(total);
}

// + / -
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('.qty-input');

        let val = parseInt(input.value) || 0;

        if (btn.classList.contains('plus')) val++;
        if (btn.classList.contains('minus') && val > 0) val--;

        input.value = val;
        recalcCart();
    });
});

// ручной ввод
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('input', recalcCart);
});

recalcCart();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

