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
    
    // Функция для сохранения (отправляет HTML контент из div)
    const saveNote = () => {
        const editor = document.querySelector('#note-editor');
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
                document.querySelector('#note-editor').focus();
                // Обновляем состояние кнопок
                setTimeout(updateButtonStates, 0);
            });
        }
    });
    
    // Обновляем состояние кнопок при движении курсора в редакторе
    const editor = document.querySelector('#note-editor');
    editor.addEventListener('click', updateButtonStates);
    editor.addEventListener('keyup', updateButtonStates);
    editor.addEventListener('keydown', (e) => {
        // Проверяем после отпускания клавиши
        setTimeout(updateButtonStates, 0);
    });
    
    // Фокус на редактор при загрузке
    if (window.location.search.includes('id=')) {
        editor.focus();
    }
    
    // Подсветить активную заметку
    const urlParams = new URLSearchParams(window.location.search);
    const currentNoteId = urlParams.get('id');
    if (currentNoteId) {
        document.querySelectorAll('.note-item a').forEach(link => {
            if (link.href.includes(`id=${currentNoteId}`)) {
                link.parentElement.classList.add('active');
            }
        });
    }
});
