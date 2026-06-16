<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$userId = currentUserId();

$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result();

$pageTitle = 'Мои заказы';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<script>
    document.body.classList.add('auth-page');
</script>

<div class="profile-container"  style="margin-top: 140px;">
    <h1 class="profile-title" style="color: var(--black); text-shadow: 0 1px 2px rgba(255,255,255,0.5); margin-bottom: 1rem">Мои заказы</h1>

    <?php if ($orders->num_rows === 0): ?>
        <div class="profile-card" style="text-align: center;">
            <p>📦 У вас пока нет заказов.</p>
            <a href="catalog.php" class="auth-btn" style="margin-top: 1rem;">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <?php while ($order = $orders->fetch_assoc()):
            $orderId = $order['id'];
            $totalStmt = $conn->prepare("
                SELECT SUM(quantity * price) AS total
                FROM order_items
                WHERE order_id = ?
            ");
            $totalStmt->bind_param("i", $orderId);
            $totalStmt->execute();
            $total = $totalStmt->get_result()->fetch_assoc()['total'];
        ?>
            <div class="profile-card" style="display: block; padding: 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0;">Заказ #<?= $orderId ?></h3>
                    <span style="background: var(--off-white); padding: 0.2rem 0.8rem; border-radius: 20px; font-size: 0.8rem;">
                        <?= h($order['status']) ?>
                    </span>
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <span style="color: var(--neutral);">Дата:</span> 
                    <strong><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></strong>
                </div>
                <div style="margin-bottom: 1rem;">
                    <span style="color: var(--neutral);">Сумма:</span> 
                    <strong style="color: var(--primary);"><?= number_format($total ?? 0, 0, '', ' ') ?> ₽</strong>
                </div>
                <div style="text-align: right;">
                    <a href="order_view.php?id=<?= $orderId ?>" class="profile-link" style="display: inline-block; padding: 0.4rem 1rem;">Подробнее →</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <div class="profile-actions" style="margin-top: 2rem;">
        <a href="profile.php" class="profile-link">← Назад в профиль</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>