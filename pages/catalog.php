<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$category_id = $_GET['category'] ?? null;

if ($category_id) {
    $stmt = $conn->prepare("
        SELECT products.id, products.name, products.price, products.image, categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id
        WHERE products.category_id = ?
        ORDER BY products.id DESC
    ");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT products.id, products.name, products.price, products.image, categories.name AS category_name
        FROM products
        LEFT JOIN categories ON products.category_id = categories.id
        ORDER BY products.id DESC
    ");
}

?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="catalog-container">
    <div class="catalog-header">
        <h1 class="catalog-title">Каталог товаров</h1>
    </div>

    <div class="products-catalog-grid">
        <?php while ($product = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <?php if (!empty($product['image']) && file_exists("../uploads/products/" . $product['image'])): ?>
                        <img src="../uploads/products/<?= h($product['image']) ?>" alt="<?= h($product['name']) ?>" class="product-image">
                    <?php else: ?>
                        <img src="/assets/images/no-image.png" alt="Нет фото" class="product-image">
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
                            <!-- Кнопка избранного (сердечко) удалена -->
                        </div>
                    <?php else: ?>
                        <p class="login-to-cart"><a href="login.php">Войдите, чтобы добавить в корзину</a></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>