<?php
/**
 * Вспомогательные функции
 */

// Безопасный вывод
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Получить список таблиц
function getTables() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SHOW TABLES");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Получить структуру таблицы
function getTableStructure($table) {
    $pdo = getDBConnection();
    $stmt = $pdo->query("DESCRIBE `$table`");
    return $stmt->fetchAll();
}

// Получить все записи
function getAllRecords($table, $limit = 100) {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM `$table` LIMIT $limit");
    return $stmt->fetchAll();
}

// Поиск записей
function searchRecords($table, $term, $field = null) {
    $pdo = getDBConnection();
    if ($field) {
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$field` LIKE ? LIMIT 100");
        $stmt->execute(["%$term%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM `$table` LIMIT 100");
    }
    return $stmt->fetchAll();
}

// Вставить запись
function insertRecord($table, $data) {
    $pdo = getDBConnection();
    $fields = array_keys($data);
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    $sql = "INSERT INTO `$table` (`" . implode('`, `', $fields) . "`) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(array_values($data));
}

// Обновить запись
function updateRecord($table, $id, $data, $idField = 'id') {
    $pdo = getDBConnection();
    $set = implode(', ', array_map(fn($f) => "`$f` = ?", array_keys($data)));
    $values = array_values($data);
    $values[] = $id;
    $sql = "UPDATE `$table` SET $set WHERE `$idField` = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($values);
}

// Удалить запись
function deleteRecord($table, $id, $idField = 'id') {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM `$table` WHERE `$idField` = ?");
    return $stmt->execute([$id]);
}

// Сообщение пользователю
function setMessage($text, $type = 'info') {
    $_SESSION['message'] = $text;
    $_SESSION['message_type'] = $type;
}

// Проверка авторизации
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /db_interface/login.php');
        exit;
    }
}
?>