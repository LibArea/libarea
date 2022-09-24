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
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#bold"></use></svg>',
            'title'     => __('app.bold'),
       ], [
            'name'      => 'italic',
            'action'    => 'EasyMDE.toggleItalic',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#italic"></use></svg>',
            'title'     => __('app.italic'),
       ], [
            'name'      => 'strikethrough',
            'action'    => 'EasyMDE.toggleStrikethrough',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#strikethrough"></use></svg>',
            'title'     => __('app.strikethrough'),
       ], [
            'name'      => 'quote',
            'action'    => 'EasyMDE.toggleBlockquote',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#quote"></use></svg>',
            'title'     => __('app.quote'),
       ], [
            'name'      => 'code',
            'action'    => 'EasyMDE.toggleCodeBlock',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#code"></use></svg>',
            'title'     => __('app.code'),
       ],  [
            'name'      => 'link',
            'action'    => 'EasyMDE.drawLink',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#link"></use></svg>',
            'title'     => __('app.link'),
       ],  [
            'name'      => 'table',
            'action'    => 'EasyMDE.drawTable',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#table"></use></svg>',
            'title'     => __('app.table'),
       ], [
            'separator' => 'separator',
       ], [
            'name'      => 'upload-image',
            'action'    => 'EasyMDE.drawUploadedImage',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#camera"></use></svg>',
            'title'     => __('app.foto'),
       ], [
            'separator' => 'separator',
       ], [
            'name'      => 'previewe',
            'action'    => 'EasyMDE.togglePreview',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#eye"></use></svg>',
            'title'     => __('app.preview'),
       ], [
            'name'      => 'side-by-side',
            'action'    => 'EasyMDE.toggleSideBySide',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#layout-columns"></use></svg>',
            'title'     => __('app.toggle_two'),
       ], [
            'name'      => 'fullscreen',
            'action'    => 'EasyMDE.toggleFullScreen',
            'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#arrows-maximize"></use></svg>',
            'title'     => __('app.fullscreen'),
       ], [
            'separator' => 'separator',
       ],

 

]; 