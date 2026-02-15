<?php
/**
 * Home View - Главная страница
 * 
 * Показывает список всех проектов
 * Каждый проект кликабелен и ведет в project.php
 */

require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MrCrm - Projects</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body class="home-page">
    <div class="home-header">
        <div class="header-content">
            <h1 class="app-title">📋 MrCrm</h1>
        </div>
    </div>
    
    <div class="projects-container">
        <div class="projects-grid">
            <!-- Карточка создания нового проекта -->
            <div class="project-card new-project-card" id="newProjectCard">
                <div class="project-emoji new-project-emoji" id="projectEmojiIcon" role="button" tabindex="0">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <h2 class="project-title new-project-title" id="projectNameDisplay">New project</h2>
                <input type="text" class="project-name-input" id="projectNameInput" value="New project">
                <button class="confirm-create-btn" id="confirmCreateBtn">
                    <i class="bi bi-check-lg"></i>
                </button>
            </div>
            
            <!-- Модальное окно выбора emoji -->
            <div class="emoji-picker-modal" id="emojiPickerModal" style="display: none;">
                <div class="emoji-picker-content">
                    <div class="emoji-picker-header">
                        <h3>Choose an emoji</h3>
                        <button class="emoji-picker-close" id="emojiPickerClose">&times;</button>
                    </div>
                    <div class="emoji-picker-grid" id="emojiPickerGrid"></div>
                    <div class="emoji-picker-footer">
                        <div class="emoji-picker-input-wrapper">
                            <input type="text" class="emoji-picker-input-field" id="emojiCustomInput" placeholder="Or paste an emoji here">
                            <button class="emoji-picker-confirm-btn" id="emojiCustomConfirm">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Проект 1 -->
            <a href="project.php?project_id=1&view=project_home" class="project-card">
                <div class="project-emoji">🚀</div>
                <h2 class="project-title">Product Launch</h2>
                <div class="project-reminders">
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Team meeting - Today at 15:00</span>
                    </div>
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Update roadmap - Tomorrow</span>
                    </div>
                </div>
            </a>
            
            <!-- Проект 2 -->
            <a href="project.php?project_id=2&view=project_home" class="project-card">
                <div class="project-emoji">💼</div>
                <h2 class="project-title">Client Work</h2>
                <div class="project-reminders">
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Call with John - Today at 17:30</span>
                    </div>
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Send proposal - Feb 18</span>
                    </div>
                </div>
            </a>
            
            <!-- Проект 3 -->
            <a href="project.php?project_id=3&view=project_home" class="project-card">
                <div class="project-emoji">🎨</div>
                <h2 class="project-title">Design System</h2>
                <div class="project-reminders">
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Review components - Feb 20</span>
                    </div>
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Update documentation - Feb 25</span>
                    </div>
                </div>
            </a>
            
            <!-- Проект 4 -->
            <a href="project.php?project_id=4&view=project_home" class="project-card">
                <div class="project-emoji">📱</div>
                <h2 class="project-title">Mobile App</h2>
                <div class="project-reminders">
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Bug fixes - Today</span>
                    </div>
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>App Store submission - Feb 28</span>
                    </div>
                </div>
            </a>
            
            <!-- Проект 5 -->
            <a href="project.php?project_id=5&view=project_home" class="project-card">
                <div class="project-emoji">📚</div>
                <h2 class="project-title">Personal Notes</h2>
                <div class="project-reminders">
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Review weekly goals - Friday</span>
                    </div>
                    <div class="reminder-item">
                        <i class="bi bi-bell"></i>
                        <span>Plan next month - Feb 27</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script src="assets/js/home.js"></script>
</body>
</html>
