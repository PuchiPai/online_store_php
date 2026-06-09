<?php

?>

<div class="hero">
    <div class="hero-grid">
        <div class="hero-label">Новая коллекция</div>
        <h1 class="hero-title">Минимализм <span>в каждой детали</span></h1>
        <div class="hero-meta">2025</div>
    </div>
    <p class="hero-description">Одежда, обувь, аксессуары для тех, кто ценит форму и функциональность.</p>
</div>

<div class="categories">
    <div class="section-header">
        <span class="section-title">Категории</span>
        <span class="section-count">3</span>
    </div>
    <div class="categories-grid">
        <?php
        // Здесь будет вывод категорий из БД. Пока статика:
        $categories = [
            ['name' => 'Ноутбуки', 'count' => 5],
            ['name' => 'Смартфоны', 'count' => 8],
            ['name' => 'Наушники', 'count' => 3]
        ];
        foreach ($categories as $cat): ?>
            <div class="category-item">
                <div>
                    <div class="category-name"><?= htmlspecialchars($cat['name']) ?></div>
                    <div class="category-count"><?= $cat['count'] ?> товаров</div>
                </div>
                <!-- картинка пока заглушка -->
                <img class="category-image" src="/assets/images/category-placeholder.jpg" alt="<?= htmlspecialchars($cat['name']) ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="products">
    <div class="products-header">
        <h2 class="section-title">Популярные товары</h2>
    </div>
    <div class="products-grid">
        <?php
        // Здесь будут товары из БД. Пример статики:
        $products = [
            ['name' => 'iPhone 15', 'price' => 99999, 'old_price' => 109999, 'image' => 'iphone15.jpg', 'category' => 'Смартфоны'],
            ['name' => 'AirPods Pro', 'price' => 19999, 'old_price' => 24999, 'image' => 'airpods.jpg', 'category' => 'Наушники']
        ];
        foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
<img class="product-image" src="/assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">                </div>
                <div class="product-info">
                    <div class="product-category"><?= htmlspecialchars($product['category']) ?></div>
                    <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="product-price">
                        <?= number_format($product['price'], 0, '', ' ') ?> ₽
                        <?php if ($product['old_price']): ?>
                            <span class="product-price-old"><?= number_format($product['old_price'], 0, '', ' ') ?> ₽</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="product-actions">
                    <button class="btn-add">В корзину</button>
                    <button class="btn-wishlist">♥</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>