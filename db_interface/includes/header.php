<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/theme.php';

$theme = getTheme();
$pageTitle = $pageTitle ?? 'Склад — Главная';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/db_interface/css/style.php">
    <style>
        :root {
            --primary: <?= rgb($theme['primary_r'], $theme['primary_g'], $theme['primary_b']) ?>;
            --secondary: <?= rgb($theme['secondary_r'], $theme['secondary_g'], $theme['secondary_b']) ?>;
            --accent: <?= rgb($theme['accent_r'], $theme['accent_g'], $theme['accent_b']) ?>;
            --background: <?= rgb($theme['background_r'], $theme['background_g'], $theme['background_b']) ?>;
            --text: <?= rgb($theme['text_r'], $theme['text_g'], $theme['text_b']) ?>;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>🗄️ Склад №3</h1>
            <nav>
                <a href="/db_interface/index.php">🏠 Главная</a>
                <a href="/db_interface/modules/list.php">📋 Данные</a>
                <a href="/db_interface/modules/create.php">➕ Добавить</a>
                <a href="/db_interface/admin/theme_settings.php">🎨 Тема</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/db_interface/logout.php" style="color:#f44336">🚪 Выход</a>
                <?php else: ?>
                    <a href="/db_interface/login.php">🔐 Вход</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">
<?php
// Сообщения сессии
if(isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>