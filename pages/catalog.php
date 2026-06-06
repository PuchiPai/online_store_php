<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$result = $conn->query("
    SELECT products.id, products.name, products.price, products.image, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    ORDER BY products.id DESC
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
</head>
<body>
<h2>Каталог товаров</h2>

<p>
    <?php if (isLoggedIn()): ?>
        <a href="profile.php">Профиль</a> |
        <a href="cart.php">Корзина (<?= cartCount() ?>)</a> |
        <?php if (isAdmin()): ?>
            <a href="../admin/index.php">Админка</a> |
        <?php endif; ?>
        <a href="logout.php">Выйти</a>
    <?php else: ?>
        <a href="login.php">Войти</a> |
        <a href="register.php">Регистрация</a>
    <?php endif; ?>
</p>

<?php while ($product = $result->fetch_assoc()): ?>
    <div style="margin-bottom: 20px;">
        <h3>
            <a href="product.php?id=<?= (int)$product['id'] ?>">
                <?= h($product['name']) ?>
            </a>
        </h3>

        <p>Категория: <?= h($product['category_name'] ?? 'Без категории') ?></p>
        <p>Цена: <?= h($product['price']) ?> ₽</p>

        <?php if (!empty($product['image'])): ?>
            <p>
                <img src="../uploads/products/<?= h($product['image']) ?>" width="150" alt="">
            </p>
        <?php endif; ?>

        <?php if (isLoggedIn()): ?>
            <form action="../api/add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Добавить в корзину</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Войдите, чтобы добавить в корзину</a></p>
        <?php endif; ?>
    </div>
    <hr>
<?php endwhile; ?>
</body>
</html>