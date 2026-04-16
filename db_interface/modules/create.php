<?php
$pageTitle = 'Добавить запись';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$table = $_GET['table'] ?? 'products';
$tables = ['products', 'batches', 'suppliers', 'categories', 'storage_locations'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($table, $tables)) {
    $data = [];
    $struct = getTableStructure($table);
    
    foreach ($struct as $col) {
        if ($col['Key'] === 'PRI' || $col['Extra'] === 'auto_increment') continue;
        if (isset($_POST[$col['Field']])) {
            $data[$col['Field']] = $_POST[$col['Field']];
        }
    }
    
    if (insertRecord($table, $data)) {
        setMessage('✅ Запись добавлена', 'success');
        header("Location: list.php?table=$table");
        exit;
    } else {
        setMessage('❌ Ошибка при добавлении', 'error');
    }
}

$structure = in_array($table, $tables) ? getTableStructure($table) : [];
?>

<div class="card">
    <h2>➕ Добавить в: <?= e($table) ?></h2>
    
    <div class="form-group">
        <label>Таблица:</label>
        <select onchange="location.href='?table='+this.value">
            <?php foreach($tables as $t): ?>
                <option value="<?= e($t) ?>" <?= $table === $t ? 'selected' : '' ?>><?= e($t) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php if($structure): ?>
        <form method="POST">
            <?php foreach($structure as $col): ?>
                <?php if($col['Key'] !== 'PRI' && $col['Extra'] !== 'auto_increment'): ?>
                    <div class="form-group">
                        <label><?= e($col['Field']) ?> <?= $col['Null']==='NO'?'<span style="color:red">*</span>':'' ?></label>
                        <?php
                        $type = strtolower($col['Type']);
                        if (preg_match('/enum\(([^)]+)\)/', $type, $m)) {
                            $opts = str_getcsv($m[1]);
                            echo '<select name="'.e($col['Field']).'">';
                            foreach($opts as $o) {
                                $o = trim($o, "'\"");
                                echo "<option value=\"$o\">$o</option>";
                            }
                            echo '</select>';
                        } elseif (strpos($type, 'int') !== false) {
                            echo '<input type="number" name="'.e($col['Field']).'">';
                        } elseif (strpos($type, 'date') !== false) {
                            echo '<input type="date" name="'.e($col['Field']).'">';
                        } elseif (strpos($type, 'text') !== false) {
                            echo '<textarea name="'.e($col['Field']).'" rows="3"></textarea>';
                        } else {
                            echo '<input type="text" name="'.e($col['Field']).'">';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">💾 Сохранить</button>
            <a href="list.php?table=<?= e($table) ?>" class="btn btn-secondary">↩️ Отмена</a>
        </form>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>