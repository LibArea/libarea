<?php
/*
 * Настройка редактора
 * Configuring the editor
 * resources/views/default/_block/editor.php
 */

return [
     [
            'name'      => 'bold',
            'action'    => 'EasyMDE.toggleBold',
            'className' => 'bi-type-bold',
            'title'     => __('app.bold'),
       ], [
            'name'      => 'italic',
            'action'    => 'EasyMDE.toggleItalic',
            'className' => 'bi-type-italic',
            'title'     => __('app.italic'),
       ], [
            'name'      => 'strikethrough',
            'action'    => 'EasyMDE.toggleStrikethrough',
            'className' => 'bi-type-strikethrough',
            'title'     => __('app.strikethrough'),
       ], [
            'name'      => 'quote',
            'action'    => 'EasyMDE.toggleBlockquote',
            'className' => 'bi-quote',
            'title'     => __('app.quote'),
       ], [
            'name'      => 'code',
            'action'    => 'EasyMDE.toggleCodeBlock',
            'className' => 'bi-code',
            'title'     => __('app.code'),
       ],  [
            'name'      => 'link',
            'action'    => 'EasyMDE.drawLink',
            'className' => 'bi-link-45deg',
            'title'     => __('app.link'),
       ], [
            'separator' => 'separator',
       ], [
            'name'      => 'upload-image',
            'action'    => 'EasyMDE.drawUploadedImage',
            'className' => 'bi-camera',
            'title'     => __('app.foto'),
       ], [
            'separator' => 'separator',
       ], [
            'name'      => 'previewe',
            'action'    => 'EasyMDE.togglePreview',
            'className' => 'bi-eye',
            'title'     => __('app.preview'),
       ], [
            'name'      => 'side-by-side',
            'action'    => 'EasyMDE.toggleSideBySide',
            'className' => 'bi-layout-split',
            'title'     => __('app.toggle_two'),
       ], [
            'name'      => 'fullscreen',
            'action'    => 'EasyMDE.toggleFullScreen',
            'className' => 'bi-arrows-move',
            'title'     => __('app.fullscreen'),
       ], [
            'separator' => 'separator',
       ],

 

]; 