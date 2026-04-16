<?php
$pageTitle = 'Просмотр данных';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$table = $_GET['table'] ?? 'products';
$tables = ['products', 'batches', 'suppliers', 'categories', 'storage_locations', 'inventory_movements', 'users'];
$records = [];
$structure = [];

if (in_array($table, $tables)) {
    $records = getAllRecords($table, 100);
    $structure = getTableStructure($table);
}
?>

<div class="card">
    <h2>📋 Просмотр: <?= e($table) ?></h2>
    
    <div class="form-group">
        <label>Выберите таблицу:</label>
        <select onchange="location.href='?table='+this.value">
            <?php foreach($tables as $t): ?>
                <option value="<?= e($t) ?>" <?= $table === $t ? 'selected' : '' ?>><?= e($t) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php if($structure): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach($structure as $col): ?>
                        <th><?= e($col['Field']) ?></th>
                    <?php endforeach; ?>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                        <tr>
                            <?php foreach($structure as $col): ?>
                                <td><?= e($row[$col['Field']]) ?></td>
                            <?php endforeach; ?>
                            <td>
                                <a href="edit.php?table=<?= e($table) ?>&id=<?= e($row[$structure[0]['Field']]) ?>" class="btn btn-primary">✏️</a>
                                <a href="delete.php?table=<?= e($table) ?>&id=<?= e($row[$structure[0]['Field']]) ?>" class="btn btn-danger" onclick="return confirm('Удалить?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?= count($structure)+1 ?>" style="text-align:center">Записей нет</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <p><a href="../index.php" class="btn btn-secondary">← На главную</a></p>
</div>

<?php require_once '../includes/footer.php'; ?>