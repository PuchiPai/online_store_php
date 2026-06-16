```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'TechStore' ?></title>

    <link rel="stylesheet" href="/assets/css/style.css">

    
</head>

<body>

<header class="header">
    <div class="navbar">

        <a href="/" class="logo">TechStore</a>

        <!-- checkbox -->
        <input type="checkbox" id="menu-toggle">

        <!-- burger -->
        <div class="burger-wrapper">
            <label for="menu-toggle" class="menu-icon">
                <span></span>
                <span></span>
                <span></span>
            </label>
        </div>

        <!-- center nav -->
        <nav class="nav-center">
            <a href="/pages/catalog.php" class="nav-link">Каталог</a>
        </nav>

        <!-- right nav -->
        <div class="nav-right">

            <?php if (isLoggedIn()): ?>

                <?php if (isAdmin()): ?>

                    <a href="/admin/index.php" class="nav-link">Админка</a>
                    <a href="/pages/logout.php" class="nav-link">Выйти</a>

                <?php else: ?>

                    <a href="/pages/profile.php" class="nav-link">Профиль</a>

                    <a href="/pages/cart.php" class="nav-link">
                        Корзина (<span class="cart-count"><?= cartCount() ?></span>)
                    </a>

                    <a href="/pages/logout.php" class="nav-link">Выйти</a>

                <?php endif; ?>

            <?php else: ?>

                <a href="/pages/login.php" class="nav-link">Войти</a>
                <a href="/pages/register.php" class="nav-link">Регистрация</a>

            <?php endif; ?>

        </div>

    </div>
</header>

<main class="main-content">
