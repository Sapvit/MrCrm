<?php
/**
 * View: Редактирование и просмотр заметки
 * Показывается при ?view=note&id=NOTEID
 */

$noteContent = '';
$noteId = $_GET['id'] ?? null;

if ($_POST && isset($_POST['save'])) {
    global $pdo;
    
    if (!$pdo) {
        // БД не подключена
        $_SESSION['error'] = 'Database not connected';
    } else {
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
        header("Location: ?project_id=" . getCurrentProjectId() . "&view=note&id=$noteId");
        exit;
    }
}

if ($noteId) {
    global $pdo;
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT content FROM notes WHERE id = ?");
        $stmt->execute([$noteId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $noteContent = $row['content'] ?? '';
    }
}
?>
<!-- Центральная область - редактирование заметки -->
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
