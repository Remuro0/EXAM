<?php
$pageTitle = 'Главная — Склад';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();

// Статистика
$tables = ['products', 'batches', 'suppliers', 'storage_locations'];
$stats = [];
foreach ($tables as $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
    $stats[$table] = $stmt->fetchColumn();
}

// Товары с низким остатком
$lowStock = $pdo->query("
    SELECT p.name, p.sku, SUM(b.quantity_remaining) as total, p.min_stock
    FROM products p
    LEFT JOIN batches b ON p.product_id = b.product_id 
    WHERE b.status = 'in_stock'
    GROUP BY p.product_id 
    HAVING total <= p.min_stock
")->fetchAll();
?>

<div class="card">
    <h2>👋 Добро пожаловать!</h2>
    <p>Система учёта товаров на складе — предметная область №3</p>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin:20px 0;">
    <div class="card" style="margin:0;text-align:center">
        <h3>📦 Товары</h3>
        <p style="font-size:32px;font-weight:bold;color:var(--primary)"><?= $stats['products'] ?></p>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <h3>📦 Партии</h3>
        <p style="font-size:32px;font-weight:bold;color:var(--primary)"><?= $stats['batches'] ?></p>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <h3>🏢 Поставщики</h3>
        <p style="font-size:32px;font-weight:bold;color:var(--primary)"><?= $stats['suppliers'] ?></p>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <h3>📍 Места</h3>
        <p style="font-size:32px;font-weight:bold;color:var(--primary)"><?= $stats['storage_locations'] ?></p>
    </div>
</div>

<?php if(!empty($lowStock)): ?>
<div class="card" style="border-left-color:#f44336">
    <h3 style="color:#f44336">⚠️ Низкий остаток</h3>
    <table>
        <tr><th>Товар</th><th>Артикул</th><th>Остаток</th><th>Минимум</th></tr>
        <?php foreach($lowStock as $item): ?>
        <tr>
            <td><?= e($item['name']) ?></td>
            <td><code><?= e($item['sku']) ?></code></td>
            <td style="color:#f44336;font-weight:bold"><?= $item['total'] ?></td>
            <td><?= $item['min_stock'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<div class="card">
    <h3>⚡ Быстрые действия</h3>
    <a href="modules/list.php?table=products" class="btn btn-primary">📦 Товары</a>
    <a href="modules/list.php?table=batches" class="btn btn-primary">📦 Партии</a>
    <a href="modules/create.php" class="btn btn-accent">➕ Добавить</a>
    <a href="admin/theme_settings.php" class="btn btn-secondary">🎨 Тема</a>
</div>

<?php require_once 'includes/footer.php'; ?>