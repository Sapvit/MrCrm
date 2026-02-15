<?php
/**
 * Правая боковая панель
 * Содержит напоминания, файлы и другие детали
 */

// Примерные данные reminders (потом подключим из БД)
$reminders = [
    [
        'id' => 1,
        'title' => 'Call client',
        'note' => 'Обсудить контракт',
        'date' => strtotime('+1 day'),
        'time' => '10:00',
        'all_day' => true,
        'completed' => false
    ],
    [
        'id' => 2,
        'title' => 'Send report',
        'note' => '',
        'date' => strtotime('2026-02-15'),
        'time' => '18:00',
        'all_day' => false,
        'completed' => false
    ]
];

/**
 * Форматировать дату и время reminder для отображения
 */
function formatReminderDateTime($timestamp, $time, $allDay) {
    $date = new DateTime('@' . $timestamp);
    $now = new DateTime();
    $now->setTime(0, 0);
    
    $diff = $date->diff($now);
    $daysAhead = (int)$diff->format('%R%a');
    
    if ($allDay) {
        if ($daysAhead === 0) {
            return 'Today';
        } elseif ($daysAhead === 1) {
            return 'Tomorrow';
        } elseif ($daysAhead > 0 && $daysAhead <= 6) {
            return $date->format('l'); // День недели
        } else {
            return $date->format('d.m.Y');
        }
    } else {
        if ($daysAhead === 0) {
            return 'Today, ' . $time;
        } elseif ($daysAhead === 1) {
            return 'Tomorrow, ' . $time;
        } elseif ($daysAhead > 0 && $daysAhead <= 6) {
            return $date->format('Tue') . ', ' . $time; // День недели, время
        } else {
            return $date->format('d.m.Y');
        }
    }
}
?>
<!-- Правая панель -->
<div class="sidebar right-sidebar">
    <div class="sidebar-content">
        <div class="panel-section reminders-panel">
            <div class="section-header">
                <h4>Reminders</h4>
                <button class="add-btn" data-tooltip="Add reminder" id="add-reminder-btn">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
            <ul class="reminders-list">
                <?php foreach ($reminders as $reminder): ?>
                    <li class="reminder-item" data-reminder-id="<?php echo $reminder['id']; ?>">
                        <button class="reminder-checkbox" data-reminder-id="<?php echo $reminder['id']; ?>" 
                                aria-label="Complete reminder">
                            <i class="bi bi-circle"></i>
                        </button>
                        <div class="reminder-content" 
                            data-reminder-id="<?php echo $reminder['id']; ?>"
                            data-date="<?php echo date('Y-m-d', $reminder['date']); ?>"
                            data-time="<?php echo $reminder['time']; ?>"
                            data-all-day="<?php echo $reminder['all_day'] ? '1' : '0'; ?>"
                            data-note="<?php echo htmlspecialchars($reminder['note']); ?>">
                            <div class="reminder-title"><?php echo htmlspecialchars($reminder['title']); ?></div>
                            <div class="reminder-datetime">
                                <?php echo formatReminderDateTime($reminder['date'], $reminder['time'], $reminder['all_day']); ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="panel-section files-panel">
            <div class="section-header">
                <h4>Files</h4>
                <button class="add-btn" data-tooltip="Attach file">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
            <ul>
                <li class="file-item">📄 Presentation.pdf</li>
                <li class="file-item">🖼️ Screenshot.png</li>
                <li class="file-item">📊 Report.xlsx</li>
            </ul>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования reminder -->
<!-- Version: 2.0 - Updated 2026-02-15 -->
<div class="reminder-modal" id="reminder-modal" style="display: none;">
    <div class="reminder-modal-content">
        <button type="button" class="reminder-modal-close" id="reminder-modal-close" aria-label="Close">
            <i class="bi bi-x"></i>
        </button>
        <form id="reminder-form" method="POST">
            <input type="hidden" name="reminder_id" id="reminder_id">
            
            <div class="form-group">
                <input type="text" id="reminder_title" name="reminder_title" placeholder="Reminder title" class="reminder-title-input">
            </div>
            
            <!-- Инлайн календарь -->
            <div class="reminder-date-picker" id="reminder-date-picker"></div>
            
            <!-- Поле для скрытого значения даты -->
            <input type="hidden" id="reminder_date" name="reminder_date">
            
            <!-- Время (если не all day) -->
            <div class="form-group" id="reminder_time_group">
                <div class="form-control-inline">
                    <input type="text" id="reminder_time" name="reminder_time" placeholder="10:00" pattern="[0-2][0-9]:[0-5][0-9]" maxlength="5">
                    <label class="checkbox-label">
                        <input type="checkbox" id="reminder_all_day" name="reminder_all_day">
                        All day
                    </label>
                </div>
            </div>
            
            <!-- Примечание -->
            <div class="form-group">
                <textarea id="reminder_note" name="reminder_note" placeholder="Note..." class="reminder-note-input"></textarea>
            </div>
            
            <!-- Удалить -->
            <button type="button" class="btn btn-delete-outline" id="reminder_delete_btn">Delete reminder</button>
        </form>
    </div>
</div>
