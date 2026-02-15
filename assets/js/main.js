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
    
    // Скрыть контент перед выгрузкой страницы (не всегда работает, но помогает)
    window.addEventListener('beforeunload', () => {
        const container = document.querySelector('.app-container');
        if (container) {
            container.style.display = 'none';
        }
    });
});
