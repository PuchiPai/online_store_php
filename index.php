<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore — техника для минималистов</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Шапка -->
    <header class="header">
        <nav class="navbar">
            <a href="index.php" class="logo">TechStore</a>
            
            <div class="nav-center">
                <a href="pages/catalog.php" class="nav-link">Каталог</a>
                <a href="pages/about.php" class="nav-link">О нас</a>
                <a href="contacts.php" class="nav-link">Контакты</a>
            </div>
            
            <div class="nav-right">
                <a href="pages/cart.php" class="cart-link">
                    Корзина <span class="cart-count">(3)</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- Герой -->
    <section class="hero">
        <div class="hero-grid">
            <div class="hero-label">Новая коллекция 2026</div>
            <h1 class="hero-title">
                Технологии
                <span>переосмыслены</span>
            </h1>
            <div class="hero-meta">
                <p class="hero-description">
                    Избранные премиум-гаджеты для современного минималиста.
                </p>
            </div>
        </div>
    </section>

    <!-- Категории -->
    <section class="categories">
        <div class="section-header">
            <span class="section-title">Категории</span>
            <span class="section-count">4 категории</span>
        </div>
        
        <div class="categories-grid">
            <div class="category-item">
                <div class="category-info">
                    <h3>Смартфоны</h3>
                    <p class="category-count">24 модели</p>
                </div>
                
                <img src="assets/images/smartphone.png" alt="Смартфоны" class="category-image"
                 style="object-fit: contain; max-height: 600px;">
            </div>
            
            <div class="category-item">
                <div class="category-info">
                    <h3>Ноутбуки</h3>
                    <p class="category-count">18 моделей</p>
                </div>
                <img src="assets/images/laptop.png" alt="Ноутбуки" class="category-image">
            </div>
            
            <div class="category-item">
                <div class="category-info">
                    <h3>Гарнитура</h3>
                    <p class="category-count">32 модели</p>
                </div>
                <img src="assets/images/garnityra.png" alt="Гарнитура" class="category-image"
                style="object-fit: contain; max-height: 500px;">
            </div>
        </div>
    </section>

    <!-- Товары -->
    <section class="products">
        <div class="products-header">
            <span class="section-title">Недавно просмотренные</span>
            <span class="section-count">8 из 74</span>
        </div>
        
        <div class="products-grid">
            <div class="product-card">
                <div class="product-image-wrapper">
                    <span class="product-badge">Новинка</span>
                    <img src="assets/images/iphone15.jpg" alt="iPhone 15" class="product-image">
                </div>
                <div class="product-info">
                    <div class="product-category">Смартфоны</div>
                    <h3 class="product-name">iPhone 15</h3>
                    <div class="product-price">
                        99 990 ₽
                        <span class="product-price-old">124 990 ₽</span>
                    </div>
                </div>
                <div class="product-actions">
                    <button class="btn-add-cart">В корзину</button>
                    <button class="btn-wishlist">♡</button>
                </div>
            </div>
            
            <!-- Дублируем для второго товара (AirPods) -->
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="assets/images/airpods.jpg" alt="AirPods Pro" class="product-image">
                </div>
                <div class="product-info">
                    <div class="product-category">Гарнитура</div>
                    <h3 class="product-name">AirPods Pro</h3>
                    <div class="product-price">
                        19 990 ₽
                        <span class="product-price-old">24 990 ₽</span>
                    </div>
                </div>
                <div class="product-actions">
                    <button class="btn-add-cart">В корзину</button>
                    <button class="btn-wishlist">♡</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Футер -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">TechStore</div>
                <p class="footer-text">
                    Премиум-техника для современного минималиста. Отобрано с заботой.
                </p>
            </div>
            
            <div class="footer-column">
                <h4>Магазин</h4>
                <ul>
                    <li><a href="#">Смартфоны</a></li>
                    <li><a href="#">Ноутбуки</a></li>
                    <li><a href="#">Гарнитура</a></li>
                    <li><a href="#">Аксессуары</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Поддержка</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Доставка</a></li>
                    <li><a href="#">Возврат</a></li>
                    <li><a href="#">Контакты</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Компания</h4>
                <ul>
                    <li><a href="#">О нас</a></li>
                    <li><a href="#">Вакансии</a></li>
                    <li><a href="#">Политика</a></li>
                    <li><a href="#">Условия</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <span>© 2025 TechStore</span>
            <span>Все права защищены</span>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>