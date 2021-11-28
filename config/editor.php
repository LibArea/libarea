<?php
/*
 * Настройка редактора
 * Configuring the editor
 * resources/views/default/_block/editor.php
 */

return [
    // Резим редактора по умолчанию
    // Default editor mode 
    // markdown | wysiwyg
    'initialEditType'   => 'markdown',
    
    // Скрыть кнопки переключения редактора разные режимы (initialEditType) в подвале редактора
    // Hide buttons to switch editor different modes (initialEditType) in editor footer 
    // true | false
    'hideModeSwitch'    => true,
]; 