<?php
header("Content-Type: text/css");
require_once __DIR__ . '/../config/theme.php';
$theme = getTheme();
?>

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--background);
    color: var(--text);
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header */
header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 1rem 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

header h1 { font-size: 1.5rem; }

nav { display: flex; gap: 15px; flex-wrap: wrap; }
nav a {
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background 0.3s;
}
nav a:hover { background: rgba(255,255,255,0.2); }

/* Main */
main { padding: 30px 0; min-height: 70vh; }

/* Cards */
.card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    margin: 20px 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 4px solid var(--primary);
}
.card h2 { color: var(--primary); margin-bottom: 15px; }

/* Buttons */
.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
    margin: 5px 5px 5px 0;
}
.btn-primary { background: var(--primary); color: white; }
.btn-primary:hover { background: var(--secondary); }
.btn-accent { background: var(--accent); color: white; }
.btn-danger { background: #e74c3c; color: white; }
.btn-secondary { background: #95a5a6; color: white; }

/* Forms */
.form-group { margin-bottom: 20px; }
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: var(--secondary);
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
}

/* Tables */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}
th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
th { background: var(--primary); color: white; font-weight: 600; }
tr:hover { background: var(--primary); background: rgba(76,175,80,0.05); }

/* Alerts */
.alert {
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
}
.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

/* Footer */
footer {
    background: var(--secondary);
    color: white;
    text-align: center;
    padding: 20px 0;
    margin-top: 40px;
}

/* Color picker */
.color-picker-group {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}
.color-picker-group input {
    width: 80px;
    padding: 8px;
}
.color-preview {
    width: 100%;
    height: 50px;
    border-radius: 5px;
    margin: 10px 0;
    border: 2px solid #ddd;
}

/* Responsive */
@media (max-width: 768px) {
    header .container { flex-direction: column; text-align: center; }
    nav { justify-content: center; }
    .color-picker-group { flex-direction: column; }
    .color-picker-group input { width: 100%; }
}
?>