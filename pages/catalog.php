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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог — TechStore</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="catalog-page">
    <div class="catalog-container">
        <div class="catalog-header">
            <h1 class="catalog-title">Каталог товаров</h1>
            <div class="catalog-nav">
                <?php if (isLoggedIn()): ?>
                    <a href="profile.php" class="catalog-nav-link">Профиль</a>
                    <a href="cart.php" class="catalog-nav-link">Корзина <span class="cart-count">(<?= cartCount() ?>)</span></a>
                    <?php if (isAdmin()): ?>
                        <a href="../admin/index.php" class="catalog-nav-link">Админка</a>
                    <?php endif; ?>
                    <a href="logout.php" class="catalog-nav-link logout">Выйти</a>
                <?php else: ?>
                    <a href="login.php" class="catalog-nav-link">Войти</a>
                    <a href="register.php" class="catalog-nav-link">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="products-catalog-grid">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <?php if (!empty($product['image'])): ?>
                            <img src="/assets/images/<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>" class="product-image">
                        <?php else: ?>
                            <div class="no-img">📷</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-details">
                        <a href="product.php?id=<?= (int)$product['id'] ?>" class="product-name-link">
                            <h3 class="product-name"><?= h($product['name']) ?></h3>
                        </a>
                        <p class="product-category"><?= h($product['category_name'] ?? 'Без категории') ?></p>
                        <p class="product-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
                        
                        <?php if (isLoggedIn()): ?>
                            <div class="product-actions">
                                <form action="../api/add_to_cart.php" method="post" style="display: flex; gap: 10px; align-items: center; flex: 1;">
                                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="quantity-input" style="width: 70px; padding: 8px; text-align: center;">
                                    <button type="submit" class="btn-add-cart">В корзину</button>
                                </form>
                                <button class="btn-wishlist" onclick="alert('Избранное в разработке')">♥</button>
                            </div>
                        <?php else: ?>
                            <p class="login-to-cart"><a href="login.php">Войдите, чтобы добавить в корзину</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>