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
    
    // Кнопка сохранения
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            const form = document.querySelector('form');
            form.submit();
        });
    }
    
    // Сохранение по Ctrl+S / Cmd+S
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const form = document.querySelector('form');
            form.submit();
        }
    });
    
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
