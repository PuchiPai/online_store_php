<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/functions.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интернет-магазин</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="navbar">
        <a href="/" class="logo">SHOP</a>
        <nav class="nav-desktop">
            <a href="/catalog.php" class="nav-link">Каталог</a>
            <a href="/cart.php" class="nav-link">Корзина</a>
        </nav>
        <div class="nav-actions">
            <?php if (isset($_SESSION['user_name'])): ?>
                <span class="nav-link">Привет, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="/logout.php" class="nav-link">Выйти</a>
            <?php else: ?>
                <a href="/login.php" class="nav-link">Вход</a>
                <a href="/register.php" class="nav-link">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main>