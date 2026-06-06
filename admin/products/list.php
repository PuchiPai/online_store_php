<?php
session_start();
require_once __DIR__ . '/../../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

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
    <title>Товары</title>
</head>
<body>
<h2>Товары</h2>

<p>
    <a href="../index.php">← Назад в админку</a> |
    <a href="add.php">Добавить товар</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Фото</th>
        <th>Название</th>
        <th>Категория</th>
        <th>Цена</th>
        <th>Действия</th>
    </tr>

    <?php while ($product = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $product["id"] ?></td>
            <td>
                <?php if (!empty($product["image"])): ?>
                    <img src="../../uploads/products/<?= htmlspecialchars($product["image"]) ?>" width="80" alt="">
                <?php else: ?>
                    Нет фото
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($product["name"]) ?></td>
            <td><?= htmlspecialchars($product["category_name"] ?? 'Без категории') ?></td>
            <td><?= htmlspecialchars($product["price"]) ?> ₽</td>
            <td>
                <a href="edit.php?id=<?= $product["id"] ?>">Редактировать</a> |
                <a href="delete.php?id=<?= $product["id"] ?>" onclick="return confirm('Удалить товар?')">Удалить</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>