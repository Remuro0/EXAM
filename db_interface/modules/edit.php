<?php
$pageTitle = 'Редактировать';
require_once '../includes/header.php';
require_once '../includes/functions.php';

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';
$record = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $table && $id) {
    $data = [];
    $struct = getTableStructure($table);
    $pk = $struct[0]['Field'];
    
    foreach ($struct as $col) {
        if ($col['Key'] === 'PRI' || $col['Extra'] === 'auto_increment') continue;
        if (isset($_POST[$col['Field']])) {
            $data[$col['Field']] = $_POST[$col['Field']];
        }
    }
    
    if (updateRecord($table, $id, $data, $pk)) {
        setMessage('✅ Запись обновлена', 'success');
        header("Location: list.php?table=$table");
        exit;
    } else {
        setMessage('❌ Ошибка при обновлении', 'error');
    }
}

if ($table && $id) {
    $structure = getTableStructure($table);
    $pk = $structure[0]['Field'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$pk` = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch();
}
?>

<div class="card">
    <h2>✏️ Редактировать: <?= e($table) ?></h2>
    
    <?php if($record && $structure): ?>
        <form method="POST">
            <?php foreach($structure as $col): ?>
                <?php if($col['Key'] !== 'PRI' && $col['Extra'] !== 'auto_increment'): ?>
                    <div class="form-group">
                        <label><?= e($col['Field']) ?></label>
                        <?php
                        $val = $record[$col['Field']] ?? '';
                        $type = strtolower($col['Type']);
                        if (preg_match('/enum\(([^)]+)\)/', $type, $m)) {
                            $opts = str_getcsv($m[1]);
                            echo '<select name="'.e($col['Field']).'">';
                            foreach($opts as $o) {
                                $o = trim($o, "'\"");
                                $sel = $val === $o ? 'selected' : '';
                                echo "<option value=\"$o\" $sel>$o</option>";
                            }
                            echo '</select>';
                        } elseif (strpos($type, 'date') !== false && $val) {
                            echo '<input type="date" name="'.e($col['Field']).'" value="'.e($val).'">';
                        } elseif (strpos($type, 'text') !== false) {
                            echo '<textarea name="'.e($col['Field']).'" rows="3">'.e($val).'</textarea>';
                        } else {
                            echo '<input type="text" name="'.e($col['Field']).'" value="'.e($val).'">';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">💾 Сохранить</button>
            <a href="list.php?table=<?= e($table) ?>" class="btn btn-secondary">↩️ Отмена</a>
        </form>
    <?php else: ?>
        <p class="alert alert-error">Запись не найдена</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>