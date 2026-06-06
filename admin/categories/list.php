<?php
session_start();
include "../../includes/db.php";

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$result = $conn->query("SELECT id, name FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Категории</title>
</head>
<body>
<h2>Категории</h2>

<p>
    <a href="../index.php">← Назад в админку</a> |
    <a href="add.php">Добавить категорию</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Действия</th>
    </tr>

    <?php while ($cat = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $cat["id"] ?></td>
            <td><?= htmlspecialchars($cat["name"]) ?></td>
            <td>
                <a href="edit.php?id=<?= $cat["id"] ?>">Редактировать</a> |
                <a href="delete.php?id=<?= $cat["id"] ?>" onclick="return confirm('Удалить категорию?')">Удалить</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>