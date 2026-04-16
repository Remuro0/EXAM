<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Склад</title>
    <link rel="stylesheet" href="css/style.php">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .login-card { background:white; padding:40px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.2); width:100%; max-width:400px; }
        .login-card h2 { color:var(--primary); margin-bottom:20px; text-align:center; }
        input { width:100%; padding:12px; margin:10px 0; border:2px solid #ddd; border-radius:8px; }
        input:focus { outline:none; border-color:var(--primary); }
        button { width:100%; padding:14px; background:var(--primary); color:white; border:none; border-radius:8px; font-size:16px; cursor:pointer; margin-top:10px; }
        button:hover { background:var(--secondary); }
        .error { background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin:10px 0; }
        .hint { font-size:12px; color:#666; text-align:center; margin-top:20px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>🔐 Вход</h2>
        <?php if(isset($error)): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required value="admin">
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        <p class="hint">Тестовый вход: <strong>admin</strong> / <strong>admin123</strong></p>
    </div>
</body>
</html>