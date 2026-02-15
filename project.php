<?php
/**
 * Project View - Главный файл
 * 
 * Это контейнер для всего приложения.
 * Слева/справа панели остаются неизменными,
 * в центре меняются разные views в зависимости от ?view параметра
 * 
 * URL структура:
 * ?project_id=1&view=home
 * ?project_id=1&view=note&id=1
 * ?project_id=1&view=notes_list
 * ?project_id=1&view=reminders_list
 * ?project_id=1&view=files_list
 */

require_once __DIR__ . '/config.php';

// Обработка AJAX запросов
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    if ($_GET['ajax'] === 'save_project' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Получить JSON данные из body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if ($data && isset($data['project_id'])) {
            $projectId = (int)$data['project_id'];
            $projectName = sanitizeHtml($data['project_name'] ?? 'Project');
            $projectEmoji = substr($data['project_emoji'] ?? '📋', 0, 2); // Берём первые 2 символа (emoji часто составляет 2 символа UTF-8)
            
            // Сохраняем в сессию
            $_SESSION['project_' . $projectId . '_name'] = $projectName;
            $_SESSION['project_' . $projectId . '_emoji'] = $projectEmoji;
            
            echo json_encode([
                'success' => true,
                'message' => 'Project updated successfully',
                'project_id' => $projectId,
                'project_name' => $projectName,
                'project_emoji' => $projectEmoji
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
        }
        exit;
    }
}

$currentView = getCurrentView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MrCrm - Project Management</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/layout.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>
    <!-- Верхняя панель с инструментами (меняется для каждой view) -->
    <?php require_once __DIR__ . '/components/top_bar.php'; ?>
    
    <div class="app-container">
        <!-- Левая боковая панель (неизменяется) -->
        <?php require_once __DIR__ . '/components/left_sidebar.php'; ?>
        
        <!-- Центральная область (меняется в зависимости от view) -->
        <?php 
            $viewFile = __DIR__ . '/views/' . $currentView . '.php';
            if (file_exists($viewFile)) {
                require_once $viewFile;
            } else {
                // Если view не существует, показываем project home
                require_once __DIR__ . '/views/project_home.php';
            }
        ?>
        
        <!-- Правая боковая панель (неизменяется) -->
        <?php require_once __DIR__ . '/components/right_sidebar.php'; ?>
    </div>
    
    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/reminders.js"></script>
    
    <!-- Дополнительные скрипты для конкретных views -->
    <?php if ($currentView === 'note'): ?>
        <script src="assets/js/note_editor.js"></script>
    <?php endif; ?>
</body>
</html>
