<?php
require_once '../includes/functions.php';

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if ($table && $id) {
    $structure = getTableStructure($table);
    $pk = $structure[0]['Field'];
    
    if (deleteRecord($table, $id, $pk)) {
        setMessage('✅ Запись удалена', 'success');
    } else {
        setMessage('❌ Ошибка при удалении', 'error');
    }
}

header("Location: list.php?table=" . urlencode($table));
exit;
?>