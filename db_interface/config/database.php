<?php
// Настройки подключения к базе данных
// Для вашей конфигурации: логин=admin, пароль=admin

define('DB_HOST', 'localhost');
define('DB_NAME', 'warehouse_db');
define('DB_USER', 'admin');
define('DB_PASS', 'admin');
define('DB_CHARSET', 'utf8mb4');

/**
 * Получить подключение к БД (PDO)
 */
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            die("❌ Ошибка подключения к базе данных");
        }
    }
    
    return $pdo;
}

/**
 * Проверка подключения
 */
function checkDB() {
    try {
        $pdo = getDBConnection();
        $pdo->query("SELECT 1");
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>