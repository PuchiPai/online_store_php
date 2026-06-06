<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$result = $conn->query("
    SELECT orders.id, orders.status, orders.created_at, users.name AS user_name, users.email AS user_email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы</title>
</head>
<body>
<h2>Заказы</h2>

<p><a href="../index.php">← Назад в админку</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Пользователь</th>
        <th>Email</th>
        <th>Статус</th>
        <th>Дата</th>
        <th>Действия</th>
    </tr>

    <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $order["id"] ?></td>
            <td><?= htmlspecialchars($order["user_name"]) ?></td>
            <td><?= htmlspecialchars($order["user_email"]) ?></td>
            <td><?= htmlspecialchars($order["status"]) ?></td>
            <td><?= htmlspecialchars($order["created_at"]) ?></td>
            <td>
                <a href="view.php?id=<?= $order["id"] ?>">Просмотр</a> |
                <a href="view.php?id=<?= $order["id"] ?>">Изменить статус</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>