<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
requireAdmin();

$result = $conn->query("
    SELECT
        o.id,
        o.status,
        o.total_amount,
        o.created_at,
        u.name AS user_name,
        u.email AS user_email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
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
        <th>Сумма</th>
        <th>Дата</th>
        <th>Действия</th>
    </tr>

    <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$order["id"] ?></td>
            <td><?= htmlspecialchars($order["user_name"]) ?></td>
            <td><?= htmlspecialchars($order["user_email"]) ?></td>
            <td><?= htmlspecialchars($order["status"]) ?></td>
            <td><?= htmlspecialchars($order["total_amount"]) ?> ₽</td>
            <td><?= htmlspecialchars($order["created_at"]) ?></td>
            <td>
                <a href="view.php?id=<?= (int)$order["id"] ?>">Просмотр</a> |
                <a href="view.php?id=<?= (int)$order["id"] ?>">Изменить статус</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>