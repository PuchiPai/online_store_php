<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить товар</title>
</head>
<body>
<h2>Добавить товар</h2>

<form action="add_action.php" method="post" enctype="multipart/form-data">
    <p>
        <input type="text" name="name" placeholder="Название товара" required>
    </p>

    <p>
        <textarea name="description" placeholder="Описание"></textarea>
    </p>

    <p>
        <input type="number" step="0.01" name="price" placeholder="Цена" required>
    </p>

    <p>
        <select name="category_id">
            <option value="">Без категории</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat["id"] ?>"><?= htmlspecialchars($cat["name"]) ?></option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        <input type="file" name="image">
    </p>

    <button type="submit">Сохранить</button>
</form>

<p><a href="list.php">← Назад</a></p>
</body>
</html>