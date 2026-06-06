<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("
    SELECT products.id, products.name, products.description, products.price, products.image, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    WHERE products.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Товар не найден");
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= h($product["name"]) ?></title>
</head>
<body>
<p><a href="catalog.php">← Назад в каталог</a></p>

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

<h2><?= h($product["name"]) ?></h2>
<p><b>Категория:</b> <?= h($product["category_name"] ?? 'Без категории') ?></p>
<p><b>Цена:</b> <?= h($product["price"]) ?> ₽</p>
<p><b>Описание:</b> <?= nl2br(h($product["description"] ?? '')) ?></p>

<?php if (!empty($product["image"])): ?>
    <p>
        <img src="../uploads/products/<?= h($product["image"]) ?>" width="250" alt="">
    </p>
<?php endif; ?>

<?php if (isLoggedIn()): ?>
    <form action="../api/add_to_cart.php" method="post">
        <input type="hidden" name="product_id" value="<?= (int)$product["id"] ?>">
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit">Добавить в корзину</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Войдите, чтобы добавить в корзину</a></p>
<?php endif; ?>
</body>
</html>