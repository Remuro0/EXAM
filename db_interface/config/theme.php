<?php
// RGB настройки темы интерфейса
session_start();

// Значения по умолчанию (зелёная складская тема)
$defaultTheme = [
    'primary_r' => 76, 'primary_g' => 175, 'primary_b' => 80,      // Основной зелёный
    'secondary_r' => 56, 'secondary_g' => 142, 'secondary_b' => 60, // Тёмно-зелёный
    'accent_r' => 255, 'accent_g' => 152, 'accent_b' => 0,          // Оранжевый акцент
    'background_r' => 245, 'background_g' => 245, 'background_b' => 245, // Светлый фон
    'text_r' => 33, 'text_g' => 33, 'text_b' => 33,                 // Тёмный текст
];

/**
 * Получить текущую тему
 */
function getTheme() {
    global $defaultTheme;
    
    // Проверяем сессию
    if (isset($_SESSION['theme']) && is_array($_SESSION['theme'])) {
        return $_SESSION['theme'];
    }
    
    // Пробуем загрузить из БД
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("SELECT * FROM theme_settings LIMIT 1");
        $theme = $stmt->fetch();
        
        if ($theme) {
            $_SESSION['theme'] = $theme;
            return $theme;
        }
    } catch (Exception $e) {
        // Игнорируем ошибки БД
    }
    
    // Возвращаем дефолт
    $_SESSION['theme'] = $defaultTheme;
    return $defaultTheme;
}

/**
 * Сохранить тему
 */
function saveTheme($theme) {
    $_SESSION['theme'] = $theme;
    
    try {
        $pdo = getDBConnection();
        // Обновляем или вставляем
        $pdo->exec("DELETE FROM theme_settings");
        $stmt = $pdo->prepare("
            INSERT INTO theme_settings 
            (primary_r, primary_g, primary_b, secondary_r, secondary_g, secondary_b,
             accent_r, accent_g, accent_b, background_r, background_g, background_b,
             text_r, text_g, text_b)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $theme['primary_r'], $theme['primary_g'], $theme['primary_b'],
            $theme['secondary_r'], $theme['secondary_g'], $theme['secondary_b'],
            $theme['accent_r'], $theme['accent_g'], $theme['accent_b'],
            $theme['background_r'], $theme['background_g'], $theme['background_b'],
            $theme['text_r'], $theme['text_g'], $theme['text_b']
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Helper: форматирование RGB
 */
function rgb($r, $g, $b, $alpha = 1) {
    return $alpha === 1 
        ? "rgb($r, $g, $b)" 
        : "rgba($r, $g, $b, $alpha)";
}
?>