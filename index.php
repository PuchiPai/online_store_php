<?php
require_once "includes/bootstrap.php";
session_start();

/*КАТЕГОРИИ*/
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

/*ТОВАРЫ (последние 4)*/
$products = $conn->query("
    SELECT * FROM products
    ORDER BY id DESC
    LIMIT 4
")->fetch_all(MYSQLI_ASSOC);

/* КОРЗИНА (заглушка)*/
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}

// Получаем товары для ленты
$marquee_products_raw = $conn->query("
    SELECT id, name, price, image 
    FROM products 
    ORDER BY id DESC 
    LIMIT 6
")->fetch_all(MYSQLI_ASSOC);

while (count($marquee_products_raw) < 6) {
    $marquee_products_raw = array_merge($marquee_products_raw, $marquee_products_raw);
}
$marquee_products = array_merge($marquee_products_raw, $marquee_products_raw, $marquee_products_raw);
?>

<?php include "includes/header.php"; ?>

<!-- HERO -->
<section class="hero">
    <div class="hero-grid">
        <div class="hero-label">Новая коллекция 2026</div>
        <h1 class="hero-title">
            Технологии
            <span>переосмыслены</span>
        </h1>
        <div class="hero-meta">
            <p class="hero-description">
                Премиум-гаджеты для современного минималиста.
            </p>
        </div>
    </div>
</section>

<!-- ДВИЖУЩАЯСЯ ЛЕНТА -->
<section class="marquee-section">
    <div class="marquee">
        <div class="marquee__track">
            <?php foreach ($marquee_products as $item): ?>
                <div class="marquee-item">
                    <a href="pages/product.php?id=<?= $item['id'] ?>">
                        <?php if (!empty($item['image']) && file_exists("uploads/products/" . $item['image'])): ?>
                            <img src="uploads/products/<?= h($item['image']) ?>" alt="<?= h($item['name']) ?>">
                        <?php else: ?>
                            <img src="assets/images/no-image.png" alt="Нет фото">
                        <?php endif; ?>
                        <span class="marquee-item-name"><?= h($item['name']) ?></span>
                        <span class="marquee-item-price"><?= number_format($item['price'], 0, ',', ' ') ?> ₽</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- КАТЕГОРИИ -->
<section class="categories">
    <div class="section-header">
        <span class="section-title">Категории</span>
        <span class="section-count"><?= count($categories) ?> категорий</span>
    </div>
    <div class="categories-grid">
        <?php foreach ($categories as $cat): ?>
            <a href="pages/catalog.php?category=<?= $cat['id'] ?>" class="category-item">
                <div class="category-info">
                    <h3><?= $cat['name'] ?></h3>
                </div>
                <img src="assets/images/categories/<?= !empty($cat['image']) ? $cat['image'] : 'no-image.png' ?>" alt="<?= $cat['name'] ?>" class="category-image">
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ТОВАРЫ (Недавно добавленные) -->
<section class="products">
    <div class="products-header">
        <span class="section-title">Недавно добавленные</span>
        <span class="section-count"><?= count($products) ?> товара</span>
    </div>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <a href="pages/product.php?id=<?= $product['id'] ?>">
                        <?php if (!empty($product['image']) && file_exists("uploads/products/" . $product['image'])): ?>
                            <img src="uploads/products/<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>" class="product-image">
                        <?php else: ?>
                            <img src="assets/images/no-image.png" alt="Нет фото" class="product-image">
                        <?php endif; ?>
                    </a>
                </div>
                <div class="product-info">
                    <a href="pages/product.php?id=<?= $product['id'] ?>" class="product-name-link">
                        <h3 class="product-name"><?= $product['name'] ?></h3>
                    </a>
                    <div class="product-category">Техника</div>
                    <div class="product-price"><?= number_format($product['price'], 0, ',', ' ') ?> ₽</div>
                </div>
                <div class="product-actions">
                    <form action="api/add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-add-cart">В корзину</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>