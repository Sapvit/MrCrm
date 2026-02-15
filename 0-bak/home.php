<?php
/**
 * View: Home/Главная
 * Показывается при открытии проекта без конкретной заметки
 */
$projectId = getCurrentProjectId();
?>
<!-- Центральная область - главная страница проекта -->
<div class="main-content home-view">
    <div class="home-container">
        <h1>Welcome to Project</h1>
        <p>Select a note from the left panel or create a new one to get started.</p>
        
        <div class="quick-actions">
            <button class="action-btn" id="new-note-btn">
                <i class="bi bi-file-earmark-plus"></i>
                New note
            </button>
        </div>
        
        <div class="recent-items">
            <h2>Recent notes</h2>
            <ul>
                <li><a href="?project_id=<?php echo $projectId; ?>&view=note&id=1">📝 My first note</a></li>
                <li><a href="?project_id=<?php echo $projectId; ?>&view=note&id=2">💡 Project ideas</a></li>
                <li><a href="?project_id=<?php echo $projectId; ?>&view=note&id=3">✅ Task list</a></li>
            </ul>
        </div>
    </div>
</div>
