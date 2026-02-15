/**
 * Note Editor - Логика редактирования заметок
 * Загружается только на view=note
 */

document.addEventListener('DOMContentLoaded', () => {
    const editor = document.querySelector('#note-editor');
    
    // Если нет редактора на странице, выходим
    if (!editor) {
        return;
    }
    
    // Функция для сохранения (отправляет HTML контент из div)
    const saveNote = () => {
        const input = document.querySelector('#note-content-input');
        input.value = editor.innerHTML;
        const form = document.querySelector('form');
        form.submit();
    };
    
    // Кнопка сохранения
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', saveNote);
    }
    
    // Сохранение по Ctrl+S / Cmd+S
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            saveNote();
        }
    });
    
    // Привязка кнопок форматирования
    const formatButtons = {
        'bi-type-h1': () => document.execCommand('formatBlock', false, '<h1>'),
        'bi-type-bold': () => document.execCommand('bold'),
        'bi-type-italic': () => document.execCommand('italic'),
        'bi-type-underline': () => document.execCommand('underline'),
        'bi-list-ul': () => document.execCommand('insertUnorderedList'),
        'bi-list-ol': () => document.execCommand('insertOrderedList')
    };
    
    const commandStates = {
        'bi-type-h1': 'formatBlock',
        'bi-type-bold': 'bold',
        'bi-type-italic': 'italic',
        'bi-type-underline': 'underline',
        'bi-list-ul': 'insertUnorderedList',
        'bi-list-ol': 'insertOrderedList'
    };
    
    // Обновляет активное состояние кнопок
    const updateButtonStates = () => {
        document.querySelectorAll('.center-middle-group .toolbar-btn').forEach((btn) => {
            const icon = btn.querySelector('i');
            const iconClass = Array.from(icon.classList).find(cls => cls.startsWith('bi-'));
            const command = commandStates[iconClass];
            
            if (command) {
                const isActive = document.queryCommandState(command);
                if (isActive) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            }
        });
    };
    
    document.querySelectorAll('.center-middle-group .toolbar-btn').forEach((btn) => {
        const icon = btn.querySelector('i');
        const iconClass = Array.from(icon.classList).find(cls => cls.startsWith('bi-'));
        
        if (formatButtons[iconClass]) {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                formatButtons[iconClass]();
                // Возвращаем фокус в редактор
                editor.focus();
                // Обновляем состояние кнопок
                setTimeout(updateButtonStates, 0);
            });
        }
    });
    
    // Обновляем состояние кнопок при движении курсора в редакторе
    editor.addEventListener('click', updateButtonStates);
    editor.addEventListener('keyup', updateButtonStates);
    editor.addEventListener('keydown', (e) => {
        // Проверяем после отпускания клавиши
        setTimeout(updateButtonStates, 0);
    });
    
    // Фокус на редактор при загрузке
    editor.focus();
});
