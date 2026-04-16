<?php
$pageTitle = 'Настройки темы';
require_once '../includes/header.php';
require_once '../includes/functions.php';
require_once '../config/theme.php';

$theme = getTheme();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTheme = [
        'primary_r' => (int)($_POST['primary_r'] ?? 76),
        'primary_g' => (int)($_POST['primary_g'] ?? 175),
        'primary_b' => (int)($_POST['primary_b'] ?? 80),
        'secondary_r' => (int)($_POST['secondary_r'] ?? 56),
        'secondary_g' => (int)($_POST['secondary_g'] ?? 142),
        'secondary_b' => (int)($_POST['secondary_b'] ?? 60),
        'accent_r' => (int)($_POST['accent_r'] ?? 255),
        'accent_g' => (int)($_POST['accent_g'] ?? 152),
        'accent_b' => (int)($_POST['accent_b'] ?? 0),
        'background_r' => (int)($_POST['background_r'] ?? 245),
        'background_g' => (int)($_POST['background_g'] ?? 245),
        'background_b' => (int)($_POST['background_b'] ?? 245),
        'text_r' => (int)($_POST['text_r'] ?? 33),
        'text_g' => (int)($_POST['text_g'] ?? 33),
        'text_b' => (int)($_POST['text_b'] ?? 33),
    ];
    
    if (saveTheme($newTheme)) {
        setMessage('🎨 Тема сохранена', 'success');
        $theme = $newTheme;
    }
}
?>

<div class="card">
    <h2>🎨 Настройка RGB темы</h2>
    
    <form method="POST">
        <div class="form-group">
            <label>Основной цвет (Primary)</label>
            <div class="color-picker-group">
                <input type="number" name="primary_r" min="0" max="255" value="<?= $theme['primary_r'] ?>" placeholder="R">
                <input type="number" name="primary_g" min="0" max="255" value="<?= $theme['primary_g'] ?>" placeholder="G">
                <input type="number" name="primary_b" min="0" max="255" value="<?= $theme['primary_b'] ?>" placeholder="B">
            </div>
            <div class="color-preview" style="background:rgb(<?= $theme['primary_r'] ?>,<?= $theme['primary_g'] ?>,<?= $theme['primary_b'] ?>)"></div>
        </div>
        
        <div class="form-group">
            <label>Вторичный цвет (Secondary)</label>
            <div class="color-picker-group">
                <input type="number" name="secondary_r" min="0" max="255" value="<?= $theme['secondary_r'] ?>" placeholder="R">
                <input type="number" name="secondary_g" min="0" max="255" value="<?= $theme['secondary_g'] ?>" placeholder="G">
                <input type="number" name="secondary_b" min="0" max="255" value="<?= $theme['secondary_b'] ?>" placeholder="B">
            </div>
            <div class="color-preview" style="background:rgb(<?= $theme['secondary_r'] ?>,<?= $theme['secondary_g'] ?>,<?= $theme['secondary_b'] ?>)"></div>
        </div>
        
        <div class="form-group">
            <label>Акцентный цвет (Accent)</label>
            <div class="color-picker-group">
                <input type="number" name="accent_r" min="0" max="255" value="<?= $theme['accent_r'] ?>" placeholder="R">
                <input type="number" name="accent_g" min="0" max="255" value="<?= $theme['accent_g'] ?>" placeholder="G">
                <input type="number" name="accent_b" min="0" max="255" value="<?= $theme['accent_b'] ?>" placeholder="B">
            </div>
            <div class="color-preview" style="background:rgb(<?= $theme['accent_r'] ?>,<?= $theme['accent_g'] ?>,<?= $theme['accent_b'] ?>)"></div>
        </div>
        
        <button type="submit" class="btn btn-primary">💾 Сохранить тему</button>
        <a href="../index.php" class="btn btn-secondary">← На главную</a>
    </form>
</div>

<script>
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', function() {
        const group = this.closest('.form-group');
        const preview = group.querySelector('.color-preview');
        const r = group.querySelector('[name$="_r"]').value;
        const g = group.querySelector('[name$="_g"]').value;
        const b = group.querySelector('[name$="_b"]').value;
        preview.style.background = `rgb(${r},${g},${b})`;
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>