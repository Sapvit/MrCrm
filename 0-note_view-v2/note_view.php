<?php
// Подключение БД (замените данные)
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=your_db;charset=utf8', 'user', 'pass');
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die('DB error: ' . $e->getMessage());
// }

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

$noteContent = '';
$noteId = $_GET['id'] ?? null;

if ($_POST && isset($_POST['save'])) {
    $content = sanitizeHtml($_POST['note_content']);
    
    if (empty($content)) {
        $content = '';
    }
    
    if ($noteId) {
        $stmt = $pdo->prepare("UPDATE notes SET content = ? WHERE id = ?");
        $stmt->execute([$content, $noteId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO notes (content, folder_id, created_at) VALUES (?, 1, NOW())");
        $stmt->execute([$content]);
        $noteId = $pdo->lastInsertId();
    }
    header("Location: ?id=$noteId");
    exit;
}

if ($noteId) {
    $stmt = $pdo->prepare("SELECT content FROM notes WHERE id = ?");
    $stmt->execute([$noteId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $noteContent = $row['content'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Editor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Верхняя панель -->
    <div class="top-bar">
        <div class="top-section left-top">
            <button class="toggle-btn-top" data-target="left" data-tooltip="Show/hide notes">
                <i class="bi bi-layout-sidebar"></i>
            </button>
        </div>
        
        <div class="top-section center-top">
            <div class="center-left-group">
                <button class="toolbar-btn back-btn" data-tooltip="Back">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            
            <div class="center-middle-group">
                <button class="toolbar-btn" data-tooltip="Heading">
                    <i class="bi bi-type-h1"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Bold">
                    <i class="bi bi-type-bold"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Italic">
                    <i class="bi bi-type-italic"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Underline">
                    <i class="bi bi-type-underline"></i>
                </button>
                <div class="toolbar-divider"></div>
                <button class="toolbar-btn" data-tooltip="Bullet list">
                    <i class="bi bi-list-ul"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Numbered list">
                    <i class="bi bi-list-ol"></i>
                </button>
            </div>
            
            <div class="center-right-group">
                <button class="toolbar-btn save-btn" data-tooltip="Save">
                    <i class="bi bi-floppy"></i>
                </button>
            </div>
        </div>
        
        <div class="top-section right-top">
            <button class="toggle-btn-top" data-target="right" data-tooltip="Show/hide details">
                <i class="bi bi-layout-sidebar" style="transform: scaleX(-1);"></i>
            </button>
        </div>
    </div>
    
    <div class="app-container">
        <!-- Левая панель -->
        <div class="sidebar left-sidebar">
            <div class="sidebar-content">
                <div class="section-header">
                    <h3>Notes</h3>
                    <button class="add-btn" data-tooltip="Add note">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
                <ul>
                    <li class="note-item"><a href="?id=1">📝 My first note</a></li>
                    <li class="note-item"><a href="?id=2">💡 Project ideas</a></li>
                    <li class="note-item"><a href="?id=3">✅ Task list</a></li>
                    <li class="note-item"><a href="?id=4">📚 Books to read</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Центральная заметка -->
        <div class="main-content">
            <form method="POST" action="">
                <div 
                    id="note-editor" 
                    contenteditable="true" 
                    class="note-editor"
                    data-placeholder="Start writing..."
                ><?php echo $noteContent; ?></div>
                <input type="hidden" name="note_content" id="note-content-input">
                <input type="hidden" name="save" value="1">
            </form>
        </div>
        
        <!-- Правая панель -->
        <div class="sidebar right-sidebar">
            <div class="sidebar-content">
                <div class="panel-section reminders-panel">
                    <div class="section-header">
                        <h4>Reminders</h4>
                        <button class="add-btn" data-tooltip="Add reminder">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <ul>
                        <li class="reminder-item">🔔 Call client — Tomorrow 10:00</li>
                        <li class="reminder-item">🔔 Send report — Feb 15</li>
                    </ul>
                </div>
                <div class="panel-section files-panel">
                    <div class="section-header">
                        <h4>Files</h4>
                        <button class="add-btn" data-tooltip="Attach file">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <ul>
                        <li class="file-item">📄 Presentation.pdf</li>
                        <li class="file-item">🖼️ Screenshot.png</li>
                        <li class="file-item">📊 Report.xlsx</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
