<?php
session_start();
require_once __DIR__ . '/../../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT id, name FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Категория не найдена");
}

$cat = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать категорию</title>
</head>
<body>
<h2>Редактировать категорию</h2>

<form action="edit_action.php" method="post">
    <input type="hidden" name="id" value="<?= $cat["id"] ?>">

    <p>
        <input type="text" name="name" value="<?= htmlspecialchars($cat["name"]) ?>" required>
    </p>

    <button type="submit">Обновить</button>
</form>

<p><a href="list.php">← Назад</a></p>
</body>
</html>