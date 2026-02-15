<?php
/**
 * Левая боковая панель
 * Содержит список заметок и основную навигацию
 */
?>
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
            <li class="note-item"><a href="?project_id=1&view=note&id=1">📝 My first note</a></li>
            <li class="note-item"><a href="?project_id=1&view=note&id=2">💡 Project ideas</a></li>
            <li class="note-item"><a href="?project_id=1&view=note&id=3">✅ Task list</a></li>
            <li class="note-item"><a href="?project_id=1&view=note&id=4">📚 Books to read</a></li>
        </ul>
    </div>
</div>
