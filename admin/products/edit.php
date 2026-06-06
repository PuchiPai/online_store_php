<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Товар не найден");
}

$product = $result->fetch_assoc();
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
</head>
<body>
<h2>Редактировать товар</h2>

<form action="edit_action.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $product["id"] ?>">

    <p>
        <input type="text" name="name" value="<?= htmlspecialchars($product["name"]) ?>" required>
    </p>

    <p>
        <textarea name="description"><?= htmlspecialchars($product["description"] ?? '') ?></textarea>
    </p>

    <p>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product["price"]) ?>" required>
    </p>

    <p>
        <select name="category_id">
            <option value="">Без категории</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat["id"] ?>" <?= ($product["category_id"] == $cat["id"]) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat["name"]) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>

    <p>
        Текущее фото:
        <?php if (!empty($product["image"])): ?>
            <br>
            <img src="../../uploads/products/<?= htmlspecialchars($product["image"]) ?>" width="100" alt="">
        <?php else: ?>
            нет фото
        <?php endif; ?>
    </p>

    <p>
        <input type="file" name="image">
    </p>

    <button type="submit">Обновить</button>
</form>

<p><a href="list.php">← Назад</a></p>
</body>
</html>