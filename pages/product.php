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
    $pageTitle = 'Товар не найден';
    require_once __DIR__ . '/../includes/header.php';
    echo '<div class="catalog-container"><p>Товар не найден. <a href="catalog.php">Вернуться в каталог</a></p></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$product = $result->fetch_assoc();
$pageTitle = h($product["name"]);
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="product-detail-container">
    <!-- Хлебные крошки (заменяют навигацию) -->
    <div class="breadcrumbs">
        <a href="/">Главная</a> / 
        <a href="catalog.php">Каталог</a> / 
        <span><?= h($product["name"]) ?></span>
    </div>

    <div class="product-detail-grid">
        <!-- Левая колонка – картинка с белым фоном -->
        <div class="product-detail-image" style="background: #ffffff;">
            <?php if (!empty($product["image"]) && file_exists("../uploads/products/" . $product["image"])): ?>
                <img src="../uploads/products/<?= h($product["image"]) ?>" alt="<?= h($product["name"]) ?>">
            <?php else: ?>
                <img src="/assets/images/no-image.png" alt="Нет фото">
            <?php endif; ?>
        </div>

        <!-- Правая колонка – информация -->
        <div class="product-detail-info">
            <h1 class="product-detail-title"><?= h($product["name"]) ?></h1>
            <p class="product-detail-category">
                Категория: <?= h($product["category_name"] ?? 'Без категории') ?>
            </p>
            <p class="product-detail-price"><?= number_format($product["price"], 0, '', ' ') ?> ₽</p>
            <div class="product-detail-description">
                <?= nl2br(h($product["description"] ?? '')) ?>
            </div>

            <?php if (isLoggedIn()): ?>
                <form action="../api/add_to_cart.php" method="post" class="product-detail-form">
    <input type="hidden" name="product_id" value="<?= (int)$product["id"] ?>">
    <div class="quantity-wrapper">
        <label for="quantity">Количество:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1" class="quantity-input">
    </div>
    <button type="submit" class="btn-add-cart">Добавить в корзину</button>
</form>
            <?php else: ?>
                <p class="login-to-cart"><a href="login.php">Войдите, чтобы добавить в корзину</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>