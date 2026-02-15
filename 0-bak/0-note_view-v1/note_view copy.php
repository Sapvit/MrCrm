<?php
// Подключение БД (замените данные)
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=your_db;charset=utf8', 'user', 'pass');
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die('DB error: ' . $e->getMessage());
// }

$noteContent = '';
$noteId = $_GET['id'] ?? null;

if ($_POST && isset($_POST['save'])) {
    $content = trim($_POST['note_content']);
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Верхняя панель -->
    <div class="top-bar">
        <div class="top-section left-top">
            <button class="toggle-btn-top" data-target="left" title="Показать/скрыть заметки">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <rect x="3" y="4" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7 4v12" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M3 8h4M3 12h4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        
        <div class="top-section center-top">
            <div class="center-left-group">
                <button class="toolbar-btn back-btn" title="Назад">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M12 5l-5 5 5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            
            <div class="center-middle-group">
                <button class="toolbar-btn" title="Заголовок">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="600" fill="currentColor">H</text>
                    </svg>
                </button>
                <button class="toolbar-btn" title="Жирный">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="currentColor">B</text>
                    </svg>
                </button>
                <button class="toolbar-btn" title="Курсив">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="14" font-style="italic" fill="currentColor">I</text>
                    </svg>
                </button>
                <button class="toolbar-btn" title="Подчеркнутый">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <text x="50%" y="45%" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="14" fill="currentColor" text-decoration="underline">U</text>
                    </svg>
                </button>
                <div class="toolbar-divider"></div>
                <button class="toolbar-btn" title="Маркированный список">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <circle cx="4" cy="6" r="1.5" fill="currentColor"/>
                        <circle cx="4" cy="10" r="1.5" fill="currentColor"/>
                        <circle cx="4" cy="14" r="1.5" fill="currentColor"/>
                        <path d="M8 6h8M8 10h8M8 14h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
                <button class="toolbar-btn" title="Нумерованный список">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <text x="4" y="7" font-family="system-ui" font-size="10" fill="currentColor">1.</text>
                        <text x="4" y="11" font-family="system-ui" font-size="10" fill="currentColor">2.</text>
                        <text x="4" y="15" font-family="system-ui" font-size="10" fill="currentColor">3.</text>
                        <path d="M8 6h8M8 10h8M8 14h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            
            <div class="center-right-group">
                <button class="toolbar-btn save-btn" title="Сохранить">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M15 17H5a1 1 0 01-1-1V4a1 1 0 011-1h7l4 4v9a1 1 0 01-1 1z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M7 3v5h6V3M7 13h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="top-section right-top">
            <button class="toggle-btn-top" data-target="right" title="Показать/скрыть детали">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <rect x="3" y="4" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M13 4v12" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M13 8h4M13 12h4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>
    
    <div class="app-container">
        <!-- Левая панель -->
        <div class="sidebar left-sidebar">
            <div class="sidebar-content">
                <div class="section-header">
                    <h3>Заметки</h3>
                    <button class="add-btn" title="Добавить заметку">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                <ul>
                    <li class="note-item"><a href="?id=1">📝 Моя первая заметка</a></li>
                    <li class="note-item"><a href="?id=2">💡 Идеи для проекта</a></li>
                    <li class="note-item"><a href="?id=3">✅ Список задач</a></li>
                    <li class="note-item"><a href="?id=4">📚 Книги для чтения</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Центральная заметка -->
        <div class="main-content">
            <form method="POST" action="">
                <textarea 
                    name="note_content" 
                    id="note-textarea"
                    placeholder="Начните писать заметку..."
                ><?php echo htmlspecialchars($noteContent); ?></textarea>
            </form>
        </div>
        
        <!-- Правая панель -->
        <div class="sidebar right-sidebar">
            <div class="sidebar-content">
                <div class="panel-section reminders-panel">
                    <div class="section-header">
                        <h4>Напоминания</h4>
                        <button class="add-btn" title="Добавить напоминание">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <ul>
                        <li class="reminder-item">🔔 Позвонить клиенту — Завтра 10:00</li>
                        <li class="reminder-item">🔔 Отправить отчет — 15 фев</li>
                    </ul>
                </div>
                <div class="panel-section files-panel">
                    <div class="section-header">
                        <h4>Файлы</h4>
                        <button class="add-btn" title="Добавить файл">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <ul>
                        <li class="file-item">📄 Презентация.pdf</li>
                        <li class="file-item">🖼️ Скриншот.png</li>
                        <li class="file-item">📊 Отчет.xlsx</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
