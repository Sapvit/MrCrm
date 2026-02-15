<?php
/**
 * Верхняя панель с инструментами
 * Меняется в зависимости от текущей view
 */
$currentView = getCurrentView();
?>
<!-- Верхняя панель -->
<div class="top-bar">
    <div class="top-section left-top">
        <button class="toggle-btn-top" data-target="left" data-tooltip="Show/hide notes">
            <i class="bi bi-layout-sidebar"></i>
        </button>
    </div>
    
    <div class="top-section center-top">
        <?php if ($currentView === 'note'): ?>
            <!-- Инструменты редактора для note view -->
            <div class="center-left-group">
                <button class="toolbar-btn back-btn" data-tooltip="Back to project">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            
            <div class="center-middle-group">
                <button class="toolbar-btn" data-tooltip="Heading">
                    <i class="bi bi-type-h1"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Bold">
                    <i class="bi bi-type-bold"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Italic">
                    <i class="bi bi-type-italic"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Underline">
                    <i class="bi bi-type-underline"></i>
                </button>
                <div class="toolbar-divider"></div>
                <button class="toolbar-btn" data-tooltip="Bullet list">
                    <i class="bi bi-list-ul"></i>
                </button>
                <button class="toolbar-btn" data-tooltip="Numbered list">
                    <i class="bi bi-list-ol"></i>
                </button>
            </div>
            
            <div class="center-right-group">
                <button class="toolbar-btn save-btn" data-tooltip="Save">
                    <i class="bi bi-floppy"></i>
                </button>
            </div>
        <?php else: ?>
            <!-- Пусто для других view -->
            <div style="flex: 1;"></div>
        <?php endif; ?>
    </div>
    
    <div class="top-section right-top">
        <button class="toggle-btn-top" data-target="right" data-tooltip="Show/hide details">
            <i class="bi bi-layout-sidebar" style="transform: scaleX(-1);"></i>
        </button>
    </div>
</div>
