/**
 * Main.js - Общая логика приложения
 */

document.addEventListener('DOMContentLoaded', () => {
    const MIN_WIDTH = 1460; // 280 + 900 + 280
    const leftSidebar = document.querySelector('.left-sidebar');
    const rightSidebar = document.querySelector('.right-sidebar');
    
    // Функция для проверки ширины и скрытия панелей
    const checkWindowWidth = () => {
        const width = window.innerWidth;
        
        if (width < MIN_WIDTH) {
            leftSidebar.classList.add('collapsed');
            rightSidebar.classList.add('collapsed');
        } else {
            leftSidebar.classList.remove('collapsed');
            rightSidebar.classList.remove('collapsed');
        }
    };
    
    // Проверяем при загрузке
    checkWindowWidth();
    
    // Проверяем при изменении размера окна
    window.addEventListener('resize', checkWindowWidth);
    
    // Toggle панелей
    document.querySelectorAll('.toggle-btn-top').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const target = btn.dataset.target;
            const sidebar = document.querySelector(`.${target}-sidebar`);
            
            sidebar.classList.toggle('collapsed');
        });
    });
    
    // Подсветить активную заметку в левой панели
    const urlParams = new URLSearchParams(window.location.search);
    const currentNoteId = urlParams.get('id');
    if (currentNoteId) {
        document.querySelectorAll('.note-item a').forEach(link => {
            // Парсим URL ссылки и сравниваем параметр id точно
            const linkUrl = new URL(link.href);
            const linkNoteId = linkUrl.searchParams.get('id');
            
            if (linkNoteId === currentNoteId) {
                link.parentElement.classList.add('active');
            }
        });
    }
    
    // Back button - ведёт на project home
    const backBtn = document.querySelector('.back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const urlParams = new URLSearchParams(window.location.search);
            const projectId = urlParams.get('project_id') || '1';
            
            // Добавляем fade-out
            const container = document.querySelector('.app-container');
            if (container) {
                container.style.transition = 'opacity 0.2s ease-out';
                container.classList.add('page-loading');
            }
            
            // Через 200ms идём на home
            setTimeout(() => {
                window.location.href = `?project_id=${projectId}&view=project_home`;
            }, 200);
        });
    }
    
    // Плавный переход при переходе между view
    // Когда пользователь клик по ссылке, контент исчезает
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (link && link.href) {
            // Игнорируем ссылки которые открываются в новой вкладке
            if (link.target === '_blank') return;
            
            // Проверяем что это внутренняя ссылка (не на другой сайт)
            try {
                const linkOrigin = new URL(link.href, window.location.origin).origin;
                if (linkOrigin === window.location.origin) {
                    // Добавляем класс загрузки - контент исчезает
                    const container = document.querySelector('.app-container');
                    if (container) {
                        // Убираем animation, чтобы контент сразу фейдился
                        container.style.animation = 'none';
                        container.style.transition = 'opacity 0.15s ease-out';
                        container.classList.add('page-loading');
                    }
                }
            } catch (e) {
                // Игнорируем ошибки парсинга URL
            }
        }
    });
    
    // ========== Project Home View ==========
    // Обработка редактирования названия и emoji проекта
    const projectNameDisplay = document.getElementById('projectNameDisplay');
    const projectEmojiDisplay = document.getElementById('projectEmojiDisplay');
    const projectEmojiPickerModal = document.getElementById('projectEmojiPickerModal');
    const projectEmojiPickerGrid = document.getElementById('projectEmojiPickerGrid');
    const projectEmojiPickerClose = document.getElementById('projectEmojiPickerClose');
    const projectEmojiCustomInput = document.getElementById('projectEmojiCustomInput');
    const projectEmojiCustomConfirm = document.getElementById('projectEmojiCustomConfirm');
    
    if (projectNameDisplay) {
        const projectId = document.getElementById('projectId').value;
        const popularEmojis = ['📁', '📋', '🚀', '💡', '📚', '💼', '🎨', '📱', '⚙️', '🎯', '📊', '🎬', '🎮', '🎪', '🎭', '💎', '🌟', '📢', '📈', '🔧', '🎁', '✅', '⭐', '🎵', '🎯', '👥', '🏆', '🔐', '🌈', '🚀'];
        
        let isEditingName = false;
        let originalName = projectNameDisplay.textContent;
        
        // Инициализация emoji picker
        function initializeProjectEmojiPicker() {
            if (!projectEmojiPickerGrid) return;
            projectEmojiPickerGrid.innerHTML = '';
            popularEmojis.forEach(emoji => {
                const button = document.createElement('button');
                button.className = 'emoji-picker-item';
                button.textContent = emoji;
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    selectProjectEmoji(emoji);
                });
                projectEmojiPickerGrid.appendChild(button);
            });
        }
        
        // Выбор emoji
        function selectProjectEmoji(emoji) {
            projectEmojiDisplay.innerHTML = '<span>' + emoji + '</span>';
            const projectEmoji = document.getElementById('currentProjectEmoji');
            if (projectEmoji) projectEmoji.value = emoji;
            closeProjectEmojiPicker();
            saveProjectData();
        }
        
        // Открыть emoji picker
        function openProjectEmojiPicker() {
            if (projectEmojiPickerModal) {
                projectEmojiPickerModal.classList.add('active');
            }
            if (projectEmojiCustomInput) {
                setTimeout(() => projectEmojiCustomInput.focus(), 100);
            }
        }
        
        // Закрыть emoji picker
        function closeProjectEmojiPicker() {
            projectEmojiPickerModal.classList.remove('active');
            if (projectEmojiCustomInput) projectEmojiCustomInput.value = '';
        }
        
        // Сохранение данных проекта
        function saveProjectData() {
            const name = projectNameDisplay.textContent;
            const emoji = document.getElementById('currentProjectEmoji').value;
            
            // Сохраняем через AJAX или обновляем сессию
            fetch('?ajax=save_project', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    project_id: projectId,
                    project_name: name,
                    project_emoji: emoji
                })
            }).catch(() => {
                // Если есть ошибка, хотя бы обновляем локально
                console.log('Project data:', { name, emoji });
            });
        }
        
        // Клик на emoji для открытия picker
        if (projectEmojiDisplay) {
            projectEmojiDisplay.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                openProjectEmojiPicker();
            });
        }
        
        // Закрытие picker
        if (projectEmojiPickerClose) {
            projectEmojiPickerClose.addEventListener('click', closeProjectEmojiPicker);
        }
        
        // Обработка пользовательского emoji
        if (projectEmojiCustomInput) {
            projectEmojiCustomInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim()) {
                    const emoji = this.value.trim().substring(0, 2);
                    selectProjectEmoji(emoji);
                }
            });
        }
        
        if (projectEmojiCustomConfirm) {
            projectEmojiCustomConfirm.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (projectEmojiCustomInput.value.trim()) {
                    const emoji = projectEmojiCustomInput.value.trim().substring(0, 2);
                    selectProjectEmoji(emoji);
                }
            });
        }
        
        // Закрытие picker при клике вне него
        document.addEventListener('click', function(e) {
            if (projectEmojiPickerModal.classList.contains('active') && 
                !projectEmojiPickerModal.contains(e.target) && 
                !projectEmojiDisplay.contains(e.target)) {
                closeProjectEmojiPicker();
            }
        });
        
        // Inline редактирование названия
        projectNameDisplay.addEventListener('click', function(e) {
            if (!isEditingName) {
                e.stopPropagation();
                isEditingName = true;
                this.contentEditable = 'true';
                this.focus();
                
                // Выделяем весь текст
                const range = document.createRange();
                range.selectNodeContents(this);
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            }
        });
        
        // Сохранение при Enter
        projectNameDisplay.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur();
            }
        });
        
        // Отмена при Escape
        projectNameDisplay.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                this.textContent = originalName;
                this.blur();
            }
        });
        
        // Завершение редактирования
        projectNameDisplay.addEventListener('blur', function() {
            isEditingName = false;
            this.contentEditable = 'false';
            const newName = this.textContent.trim();
            if (newName && newName !== originalName) {
                originalName = newName;
                document.getElementById('currentProjectName').value = newName;
                saveProjectData();
            } else if (!newName) {
                this.textContent = originalName;
            }
        });
        
        // Инициализация
        initializeProjectEmojiPicker();
    }
    
    // Скрыть контент перед выгрузкой страницы (не всегда работает, но помогает)
    window.addEventListener('beforeunload', () => {
        const container = document.querySelector('.app-container');
        if (container) {
            container.style.display = 'none';
        }
    });
});
