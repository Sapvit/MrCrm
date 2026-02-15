/**
 * Reminders - Логика управления напоминаниями
 * Загружается всегда, так как reminders есть на всех страницах
 */

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('#reminder-modal');
    const modalClose = document.querySelector('#reminder-modal-close');
    const reminderForm = document.querySelector('#reminder-form');
    const addReminderBtn = document.querySelector('#add-reminder-btn');
    
    let originalFormData = {};
    
    // Сохранить оригинальное состояние формы
    const saveOriginalFormData = () => {
        originalFormData = {
            title: document.querySelector('#reminder_title').value,
            date: document.querySelector('#reminder_date').value,
            time: document.querySelector('#reminder_time').value,
            allDay: document.querySelector('#reminder_all_day').checked,
            note: document.querySelector('#reminder_note').value
        };
    };
    
    // Проверить, есть ли изменения в форме
    const hasFormChanges = () => {
        const currentData = {
            title: document.querySelector('#reminder_title').value,
            date: document.querySelector('#reminder_date').value,
            time: document.querySelector('#reminder_time').value,
            allDay: document.querySelector('#reminder_all_day').checked,
            note: document.querySelector('#reminder_note').value
        };
        
        return JSON.stringify(originalFormData) !== JSON.stringify(currentData);
    };
    
    // Сохранить reminder
    const saveReminder = () => {
        const formData = new FormData(reminderForm);
        const reminderId = formData.get('reminder_id');
        
        // TODO: Отправить на backend
        // fetch('/api/reminders/' + (reminderId || 'new'), {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(Object.fromEntries(formData))
        // })
        
        console.log('Reminder saved:', Object.fromEntries(formData));
        closeModal();
    };
    
    // Скрыть модальное окно
    const closeModal = () => {
        if (modal) {
            modal.style.display = 'none';
            reminderForm.reset();
        }
    };
    
    // Показать модальное окно
    const showModal = (reminderId = null) => {
        if (!modal) return;
        
        // Очищаем форму
        reminderForm.reset();
        
        let dateToSet;
        
        if (reminderId) {
            // Редактирование существующего reminder
            document.querySelector('#reminder_id').value = reminderId;
            const dateString = loadReminderData(reminderId); // Получаем дату из данных
            dateToSet = dateString ? new Date(dateString) : new Date();
        } else {
            // Создание нового reminder
            document.querySelector('#reminder_id').value = '';
            // Устанавливаем дефолтную дату (сегодня)
            dateToSet = new Date();
            document.querySelector('#reminder_time').value = '10:00';
            document.querySelector('#reminder_all_day').checked = false;
            updateTimeVisibility();
            
            const dateString = dateToSet.toISOString().split('T')[0];
            document.querySelector('#reminder_date').value = dateString;
        }
        
        modal.style.display = 'flex';
        
        // Проверяем дату, обновляем календарь и сохраняем оригинальное состояние
        setTimeout(() => {
            // Обновляем Flatpickr календарь на выбранную дату
            const datePicker = document.querySelector('#reminder-date-picker');
            if (datePicker && datePicker._flatpickr) {
                datePicker._flatpickr.setDate(dateToSet, false);
            }
            
            checkIfPastDate();
            saveOriginalFormData();
            document.querySelector('#reminder_title').focus();
        }, 100);
    };
    
    // Загрузить данные reminder из атрибутов элемента
    const loadReminderData = (reminderId) => {
        const reminderElement = document.querySelector(`.reminder-content[data-reminder-id="${reminderId}"]`);
        if (!reminderElement) return;
        
        // Читаем data-атрибуты
        const title = reminderElement.querySelector('.reminder-title').textContent;
        const date = reminderElement.dataset.date;
        const time = reminderElement.dataset.time;
        const allDay = reminderElement.dataset.allDay === '1';
        const note = reminderElement.dataset.note;
        
        // Заполняем форму
        document.querySelector('#reminder_title').value = title;
        document.querySelector('#reminder_date').value = date;
        document.querySelector('#reminder_time').value = time;
        document.querySelector('#reminder_all_day').checked = allDay;
        document.querySelector('#reminder_note').value = note;
        
        updateTimeVisibility();
        
        return date; // Возвращаем дату для календаря
    };
    
    // Обновить состояние поля времени
    const updateTimeVisibility = () => {
        const allDayCheckbox = document.querySelector('#reminder_all_day');
        const timeInput = document.querySelector('#reminder_time');
        
        if (allDayCheckbox.checked) {
            timeInput.disabled = true;
            timeInput.style.opacity = '0.5';
            timeInput.style.cursor = 'not-allowed';
        } else {
            timeInput.disabled = false;
            timeInput.style.opacity = '1';
            timeInput.style.cursor = 'text';
        }
    };
    
    // Event Listeners
    
    // Кнопка добавления нового reminder
    if (addReminderBtn) {
        addReminderBtn.addEventListener('click', () => {
            showModal();
        });
    }
    
    // Кнопка закрытия (крестик)
    if (modalClose) {
        modalClose.addEventListener('click', () => {
            if (hasFormChanges()) {
                // Есть изменения - показываем подтверждение
                const result = confirm('You have unsaved changes. Do you want to save them?');
                if (result) {
                    saveReminder();
                } else {
                    closeModal();
                }
            } else {
                // Нет изменений - просто закрываем
                closeModal();
            }
        });
    }
    
    // Закрытие по Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            if (hasFormChanges()) {
                const result = confirm('You have unsaved changes. Do you want to save them?');
                if (result) {
                    saveReminder();
                } else {
                    closeModal();
                }
            } else {
                closeModal();
            }
        }
    });
    
    // Чекбокс "All day"
    const allDayCheckbox = document.querySelector('#reminder_all_day');
    if (allDayCheckbox) {
        allDayCheckbox.addEventListener('change', updateTimeVisibility);
    }
    
    // Поле времени - автоформатирование 24-часовой формат
    const timeInput = document.querySelector('#reminder_time');
    if (timeInput) {
        // Автоформатирование при вводе
        timeInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/[^\d]/g, ''); // Только цифры
            
            if (value.length >= 2) {
                const hours = Math.min(23, parseInt(value.substring(0, 2)));
                const minutes = value.length >= 3 ? value.substring(2, 4) : '';
                value = hours.toString().padStart(2, '0') + (minutes ? ':' + minutes.padStart(2, '0') : '');
            }
            
            e.target.value = value;
        });
        
        // Валидация при потере фокуса
        timeInput.addEventListener('blur', () => {
            let value = timeInput.value.replace(/[^\d:]/g, '');
            
            if (value.includes(':')) {
                const [hours, minutes] = value.split(':');
                const h = Math.min(23, parseInt(hours) || 0);
                const m = Math.min(59, parseInt(minutes) || 0);
                timeInput.value = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
            } else if (value.length > 0) {
                const h = Math.min(23, parseInt(value) || 0);
                timeInput.value = `${h.toString().padStart(2, '0')}:00`;
            }
        });
    }
    
    // Клик на reminder для редактирования
    document.querySelectorAll('.reminder-content').forEach(element => {
        element.addEventListener('click', (e) => {
            e.stopPropagation();
            const reminderId = element.dataset.reminderId;
            showModal(reminderId);
        });
    });
    
    // Клик на чекбокс для завершения reminder
    document.querySelectorAll('.reminder-checkbox').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const reminderId = button.dataset.reminderId;
            completeReminder(reminderId);
        });
    });
    
    // Завершить reminder
    const completeReminder = (reminderId) => {
        const reminderItem = document.querySelector(`li[data-reminder-id="${reminderId}"]`);
        if (!reminderItem) return;
        
        // Здесь отправляем request на backend
        // Пока просто показываем визуальное изменение
        const checkbox = reminderItem.querySelector('.reminder-checkbox i');
        const isCompleted = reminderItem.classList.contains('completed');
        
        if (isCompleted) {
            reminderItem.classList.remove('completed');
            checkbox.className = 'bi bi-circle';
        } else {
            reminderItem.classList.add('completed');
            checkbox.className = 'bi bi-check-circle-fill';
        }
        
        // TODO: Отправить на backend
        // fetch('/api/reminders/' + reminderId + '/complete', { method: 'POST' })
    };
    
    // Удалить reminder
    const deleteBtn = document.querySelector('#reminder_delete_btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', () => {
            const reminderId = document.querySelector('#reminder_id').value;
            
            if (!reminderId) {
                closeModal();
                return;
            }
            
            if (confirm('Delete this reminder?')) {
                // TODO: Отправить запрос на удаление
                // fetch('/api/reminders/' + reminderId, { method: 'DELETE' })
                closeModal();
            }
        });
    }
    
    // Инициализируем календарь (Flatpickr)
    initializeDatePicker();
});

/**
 * Инициализация Flatpickr календаря
 */
function initializeDatePicker() {
    const datepickerScript = document.createElement('script');
    datepickerScript.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
    document.head.appendChild(datepickerScript);
    
    const datepickerCSS = document.createElement('link');
    datepickerCSS.rel = 'stylesheet';
    datepickerCSS.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css';
    document.head.appendChild(datepickerCSS);
    
    // После загрузки Flatpickr инициализируем
    datepickerScript.addEventListener('load', () => {
        if (window.flatpickr) {
            window.flatpickr('#reminder-date-picker', {
                inline: true,
                dateFormat: 'Y-m-d',
                allowInput: true,
                enableTime: false,
                weekNumbers: false,
                monthSelectorType: 'static',
                onChange: (selectedDates) => {
                    // Обновляем скрытое поле даты
                    if (selectedDates.length > 0) {
                        const dateString = selectedDates[0].toISOString().split('T')[0];
                        document.querySelector('#reminder_date').value = dateString;
                        checkIfPastDate();
                    }
                }
            });
            
            // Добавляем обработчик для прямого ввода даты
            const dateHiddenInput = document.querySelector('#reminder_date');
            if (dateHiddenInput) {
                dateHiddenInput.addEventListener('change', checkIfPastDate);
            }
        }
    });
}

/**
 * Проверить, если дата в прошлом - красить время красным
 */
function checkIfPastDate() {
    const dateInput = document.querySelector('#reminder_date');
    const timeInput = document.querySelector('#reminder_time');
    
    if (!dateInput.value || !timeInput) return;
    
    const selectedDate = new Date(dateInput.value).setHours(0, 0, 0, 0);
    const today = new Date().setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        timeInput.classList.add('time-past');
    } else {
        timeInput.classList.remove('time-past');
    }
}
