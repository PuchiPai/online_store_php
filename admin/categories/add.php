<?php
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить категорию</title>
</head>
<body>
<h2>Добавить категорию</h2>

<form action="add_action.php" method="post">
    <p>
        <input type="text" name="name" placeholder="Название категории" required>
    </p>
    <button type="submit">Сохранить</button>
</form>

<p><a href="list.php">← Назад</a></p>
</body>
</html>