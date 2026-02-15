document.addEventListener('DOMContentLoaded', function() {
    const newProjectCard = document.getElementById('newProjectCard');
    const projectNameInput = document.getElementById('projectNameInput');
    const projectNameDisplay = document.getElementById('projectNameDisplay');
    const projectEmojiIcon = document.getElementById('projectEmojiIcon');
    const confirmCreateBtn = document.getElementById('confirmCreateBtn');
    const emojiPickerModal = document.getElementById('emojiPickerModal');
    const emojiPickerGrid = document.getElementById('emojiPickerGrid');
    const emojiPickerClose = document.getElementById('emojiPickerClose');
    const emojiCustomInput = document.getElementById('emojiCustomInput');
    const emojiCustomConfirm = document.getElementById('emojiCustomConfirm');
    
    let isEditing = false;
    let hasChanges = false;
    let originalProjectName = 'New project';
    let currentEmoji = '📁';
    
    const popularEmojis = ['📁', '📋', '🚀', '💡', '📚', '💼', '🎨', '📱', '⚙️', '🎯', '📊', '🎬', '🎮', '🎪', '🎭', '💎', '🌟', '📢', '📈', '🔧', '🎁', '✅', '⭐', '🎵', '🎯', '👥', '🏆', '🔐', '🌈', '🚀'];
    
    // Инициализация emoji picker grid
    function initializeEmojiPicker() {
        emojiPickerGrid.innerHTML = '';
        popularEmojis.forEach(emoji => {
            const button = document.createElement('button');
            button.className = 'emoji-picker-item';
            button.textContent = emoji;
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                selectEmoji(emoji);
            });
            emojiPickerGrid.appendChild(button);
        });
    }
    
    // Выбор emoji
    function selectEmoji(emoji) {
        currentEmoji = emoji;
        projectEmojiIcon.innerHTML = '<span style="font-size: 48px; line-height: 1; display: block;">' + emoji + '</span>';
        hasChanges = true;
        closeEmojiPicker();
    }
    
    // Открыть emoji picker
    function openEmojiPicker() {
        emojiPickerModal.classList.add('active');
        setTimeout(() => {
            emojiCustomInput.focus();
        }, 100);
    }
    
    // Закрыть emoji picker
    function closeEmojiPicker() {
        emojiPickerModal.classList.remove('active');
        emojiCustomInput.value = '';
    }
    
    // Клик на карточку - переход в режим редактирования
    if (newProjectCard) {
        newProjectCard.addEventListener('click', function(e) {
            if (!isEditing && !e.target.closest('.new-project-emoji') && !e.target.closest('.confirm-create-btn')) {
                enterEditMode();
            }
        });
    }
    
    // Клик на эмоджи когда редактируем - открыть picker
    if (projectEmojiIcon) {
        projectEmojiIcon.addEventListener('click', function(e) {
            if (isEditing) {
                e.stopPropagation();
                openEmojiPicker();
            }
        });
        
        projectEmojiIcon.addEventListener('keydown', function(e) {
            if (isEditing && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                openEmojiPicker();
            }
        });
    }
    
    // Закрытие picker при клике на X
    if (emojiPickerClose) {
        emojiPickerClose.addEventListener('click', closeEmojiPicker);
    }
    
    // Обработка ввода пользовательского emoji
    if (emojiCustomInput) {
        emojiCustomInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim()) {
                    const emoji = this.value.trim().substring(0, 2);
                    selectEmoji(emoji);
                }
            }
        });
    }
    
    // Кнопка подтверждения пользовательского emoji
    if (emojiCustomConfirm) {
        emojiCustomConfirm.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (emojiCustomInput.value.trim()) {
                const emoji = emojiCustomInput.value.trim().substring(0, 2);
                selectEmoji(emoji);
            }
        });
    }
    
    // Закрытие picker при клике вне его
    document.addEventListener('click', function(e) {
        if (emojiPickerModal.classList.contains('active') && 
            !emojiPickerModal.contains(e.target) && 
            !projectEmojiIcon.contains(e.target)) {
            closeEmojiPicker();
        }
    });
    
    // Функция входа в режим редактирования
    function enterEditMode() {
        isEditing = true;
        hasChanges = false;
        newProjectCard.classList.add('editing');
        
        // Меняем иконку с плюса на текущий emoji
        projectEmojiIcon.innerHTML = '<span style="font-size: 48px; line-height: 1; display: block;">' + currentEmoji + '</span>';
        
        // Фокус на поле ввода
        setTimeout(() => {
            projectNameInput.focus();
            projectNameInput.select();
        }, 100);
    }
    
    // Функция выхода из режима редактирования
    function exitEditMode(save = false) {
        if (save && hasChanges) {
            // Создание проекта
            const projectName = projectNameInput.value.trim() || 'New project';
            createProject(projectName, currentEmoji);
        } else {
            // Возврат к исходному виду
            isEditing = false;
            hasChanges = false;
            newProjectCard.classList.remove('editing');
            projectNameInput.value = originalProjectName;
            closeEmojiPicker();
            
            // Меняем иконку обратно на плюс
            projectEmojiIcon.innerHTML = '<i class="bi bi-plus-lg"></i>';
            currentEmoji = '📁';
        }
    }
    
    // Отслеживание изменений в названии проекта
    if (projectNameInput) {
        projectNameInput.addEventListener('input', function() {
            if (projectNameInput.value !== originalProjectName) {
                hasChanges = true;
            }
        });
        
        // Enter для создания проекта
        projectNameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                confirmCreateBtn.click();
            }
        });
        
        // Escape для отмены
        projectNameInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                if (hasChanges) {
                    if (confirm('Discard changes?')) {
                        exitEditMode(false);
                    }
                } else {
                    exitEditMode(false);
                }
            }
        });
    }
    
    // Кнопка подтверждения создания
    if (confirmCreateBtn) {
        confirmCreateBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exitEditMode(true);
        });
    }
    
    // Клик вне карточки
    document.addEventListener('click', function(e) {
        // Не срабатываем если modal с picker'ом открыт
        if (emojiPickerModal.classList.contains('active')) {
            return;
        }
        
        if (isEditing && !newProjectCard.contains(e.target) && !emojiPickerModal.contains(e.target)) {
            if (hasChanges) {
                if (confirm('Save changes?')) {
                    exitEditMode(true);
                } else {
                    exitEditMode(false);
                }
            } else {
                exitEditMode(false);
            }
        }
    });
    
    // Функция создания проекта
    function createProject(name, emoji) {
        console.log('Creating project:', { name, emoji });
        
        // Временно: генерируем случайный ID и переходим в проект
        const projectId = Math.floor(Math.random() * 10000);
        window.location.href = `project.php?project_id=${projectId}&view=project_home`;
    }
    
    // Анимация карточек при загрузке
    const projectCards = document.querySelectorAll('.project-card:not(.new-project-card)');
    projectCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, (index + 1) * 50);
    });
    
    // Анимация карточки создания проекта
    if (newProjectCard) {
        newProjectCard.style.opacity = '0';
        newProjectCard.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            newProjectCard.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            newProjectCard.style.opacity = '1';
            newProjectCard.style.transform = 'translateY(0)';
        }, 0);
    }
    
    // Инициализация
    initializeEmojiPicker();
});
