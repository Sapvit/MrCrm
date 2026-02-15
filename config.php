<?php
/**
 * Конфигурация приложения
 */

// Подключение БД (замените данные)
// Пример:
// $pdo = new PDO('mysql:host=localhost;dbname=your_db;charset=utf8', 'user', 'pass');
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $pdo = new PDO(...);
// $GLOBALS['pdo'] = $pdo;

/**
 * Очистить HTML от опасного кода, оставляя только безопасные теги форматирования
 */
function sanitizeHtml($html) {
    // Разрешённые теги: для текстового форматирования
    $allowed = '<b><strong><i><em><u><br><p><h1><ul><ol><li>';
    
    // Убираем всё, кроме разрешённых тегов
    $clean = strip_tags($html, $allowed);
    
    // Дополнительная защита: кодируем атрибуты
    $clean = preg_replace('/<([^>]+)on[a-z]+\s*=\s*["\'][^"\']*/', '<$1', $clean);
    
    return trim($clean);
}

/**
 * Получить текущий проект/папку
 */
function getCurrentProjectId() {
    return $_GET['project_id'] ?? null;
}

/**
 * Получить текущую активную view (note, project_home, notes_list и т.д.)
 */
function getCurrentView() {
    return $_GET['view'] ?? 'project_home';
}
?>
