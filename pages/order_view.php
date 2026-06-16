<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$userId = currentUserId();

if ($id <= 0) {
    die("Некорректный ID");
}

$stmt = $conn->prepare("
    SELECT id, status, total_amount, created_at, items_json
    FROM orders
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Заказ не найден");
}

// Добавляем image в запрос
$itemsStmt = $conn->prepare("
    SELECT oi.quantity, oi.price, p.id, p.name, p.image
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$itemsStmt->bind_param("i", $id);
$itemsStmt->execute();
$items = $itemsStmt->get_result();

$pageTitle = 'Заказ #' . $id;
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<script>
    document.body.classList.add('auth-page');
</script>

<div class="profile-container" style="margin-top: 100px;">
    <h1 class="profile-title" style="margin-bottom: 0.5rem;">Заказ #<?= $order['id'] ?></h1>

    <!-- Хлебные крошки  -->
    <div class="breadcrumbs" style="margin-bottom: 1.5rem; font-size: 0.85rem;">
        <a href="/">Главная</a> / 
        <a href="profile.php">Профиль</a> / 
        <a href="orders.php">Мои заказы</a> / 
        <span>Заказ #<?= $id ?></span>
    </div>

    <div class="profile-card" style="display: block; padding: 1.5rem;">
        <!-- Статус и дата -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
            <div>
                <strong>Статус:</strong>
                <span style="background: var(--off-white); padding: 0.2rem 0.8rem; border-radius: 20px; font-size: 0.85rem; margin-left: 0.5rem;">
                    <?= h($order['status']) ?>
                </span>
            </div>
            <div>
                <strong>Дата:</strong> <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
            </div>
        </div>

        <h3 style="font-size: 1.2rem; font-weight: 500; margin: 1.5rem 0 1rem 0;">Состав заказа</h3>

        <!-- Список товаров в стиле корзины -->
        <?php 
        $total = 0;
        while ($item = $items->fetch_assoc()):
            $sum = $item['quantity'] * $item['price'];
            $total += $sum;
        ?>
            <div class="cart-item-card" style="margin-bottom: 1rem;">
                <!-- Картинка -->
                <div class="cart-item-img">
                    <?php if (!empty($item['image']) && file_exists("../uploads/products/" . $item['image'])): ?>
                        <img src="../uploads/products/<?= h($item['image']) ?>" alt="<?= h($item['name']) ?>">
                    <?php else: ?>
                        <img src="/assets/images/no-image.png" alt="Нет фото">
                    <?php endif; ?>
                </div>
                <!-- Информация -->
                <div class="cart-item-info" style="flex: 2;">
                    <div class="cart-item-title"><?= h($item['name']) ?></div>
                    <div class="cart-item-price"><?= number_format($item['price'], 0, '', ' ') ?> ₽</div>
                </div>
                <!-- Количество -->
                <div class="cart-item-quantity" style="width: auto;">
                    <span style="margin-right: 0.5rem;"><?= $item['quantity'] ?> шт</span>
                </div>
                <!-- Сумма -->
                <div class="cart-item-total">
                    <?= number_format($sum, 0, '', ' ') ?> ₽
                </div>
            </div>
        <?php endwhile; ?>

        <!-- Итоговая сумма -->
        <div class="checkout-total" style="margin-top: 1rem; padding-top: 1rem; font-size: 1.3rem;">
            <span>Итого:</span>
            <strong><?= number_format($total, 0, '', ' ') ?> ₽</strong>
        </div>
    </div>

    <div class="profile-actions" style="margin-top: 2rem;">
        <a href="orders.php" class="profile-link">← Назад к заказам</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
