<?php
/**
 * View: Project Home/Главная проекта
 * Показывается при открытии проекта без конкретной заметки
 */
$projectId = getCurrentProjectId();

// Получаем данные проекта из сессии или параметров
$projectName = $_SESSION['project_' . $projectId . '_name'] ?? ($_GET['project_name'] ?? 'Project');
$projectEmoji = $_SESSION['project_' . $projectId . '_emoji'] ?? ($_GET['project_emoji'] ?? '📋');
?>
<!-- Центральная область - главная страница проекта -->
<div class="main-content home-view">
    <div class="home-container">
        <!-- Emoji as background decoration -->
        <div class="project-emoji-decoration" id="projectEmojiDisplay">
            <span><?php echo htmlspecialchars($projectEmoji); ?></span>
        </div>
        
        <!-- Welcome text with editable project name -->
        <div class="welcome-header">
            <h1>Welcome to <span class="editable-project-name" id="projectNameDisplay" contenteditable="false" tabindex="0" role="button"><?php echo htmlspecialchars($projectName); ?></span></h1>
            <p>Select a note from the left panel or create a new one to get started.</p>
        </div>
        
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

<!-- Модальное окно выбора emoji (копируем из home.php) -->
<div class="emoji-picker-modal" id="projectEmojiPickerModal">
    <div class="emoji-picker-content">
        <div class="emoji-picker-header">
            <h3>Choose project emoji</h3>
            <button class="emoji-picker-close" id="projectEmojiPickerClose">&times;</button>
        </div>
        <div class="emoji-picker-grid" id="projectEmojiPickerGrid"></div>
        <div class="emoji-picker-footer">
            <div class="emoji-picker-input-wrapper">
                <input type="text" class="emoji-picker-input-field" id="projectEmojiCustomInput" placeholder="Or paste an emoji here">
                <button class="emoji-picker-confirm-btn" id="projectEmojiCustomConfirm">
                    <i class="bi bi-check-lg"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden input для сохранения проекта -->
<input type="hidden" id="projectId" value="<?php echo htmlspecialchars($projectId); ?>">
<input type="hidden" id="currentProjectName" value="<?php echo htmlspecialchars($projectName); ?>">
<input type="hidden" id="currentProjectEmoji" value="<?php echo htmlspecialchars($projectEmoji); ?>">
