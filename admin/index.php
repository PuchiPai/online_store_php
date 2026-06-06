<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
requireAdmin();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка</title>
</head>
<body>
<h2>Админ-панель</h2>

<p>
    <a href="../pages/catalog.php">Открыть сайт</a> |
    <a href="../pages/logout.php">Выйти</a>
</p>

<ul>
    <li><a href="products/list.php">Товары</a></li>
    <li><a href="orders/list.php">Заказы</a></li>
    <li><a href="categories/list.php">Категории</a></li>
</ul>
</body>
</html>