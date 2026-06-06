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
    <title>Корзина</title>
</head>
<body>
<h2>Корзина</h2>

<p>
    <a href="catalog.php">← Продолжить покупки</a> |
    <a href="profile.php">Профиль</a> |
    <a href="logout.php">Выйти</a>
</p>

<?php if (empty($cart)): ?>
    <p>Корзина пуста</p>
<?php else: ?>
    <form action="../api/update_cart.php" method="post">
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

            <div style="margin-bottom: 20px;">
                <h3><?= h($product["name"]) ?></h3>
                <p>Цена: <?= h($product["price"]) ?> ₽</p>

                <?php if (!empty($product["image"])): ?>
                    <p>
                        <img src="../uploads/products/<?= h($product["image"]) ?>" width="120" alt="">
                    </p>
                <?php endif; ?>

                <p>
                    Количество:
                    <input type="number" name="qty[<?= $product_id ?>]" value="<?= $quantity ?>" min="0">
                </p>

                <p>Сумма: <?= $sum ?> ₽</p>

                <p>
                    <a href="../api/remove_from_cart.php?id=<?= $product_id ?>">Удалить</a>
                </p>
            </div>
            <hr>
        <?php endforeach; ?>

        <p><b>Итого: <?= $total ?> ₽</b></p>

        <button type="submit">Обновить корзину</button>
    </form>

    <p>
        <a href="checkout.php">Оформить заказ</a>
    </p>
<?php endif; ?>
</body>
</html>