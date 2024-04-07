<?php
/*
 * Настройка редактора (личные сообщения)
 * Editor settings (private messages)
 * resources/views/default/_block/editor.php
 */

return [
     'config' =>  [
          [
               'name'      => 'bold',
               'action'    => 'EasyMDE.toggleBold',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#bold"></use></svg>',
               'title'     => 'app.bold',
          ], [
               'name'      => 'italic',
               'action'    => 'EasyMDE.toggleItalic',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#italic"></use></svg>',
               'title'     => 'app.italic',
          ], [
               'name'      => 'strikethrough',
               'action'    => 'EasyMDE.toggleStrikethrough',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#strikethrough"></use></svg>',
               'title'     => 'app.strikethrough',
          ], [
               'name'      => 'quote',
               'action'    => 'EasyMDE.toggleBlockquote',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#quote"></use></svg>',
               'title'     => 'app.quote',
          ], [
               'name'      => 'code',
               'action'    => 'EasyMDE.toggleCodeBlock',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#code"></use></svg>',
               'title'     => 'app.code',
          ], [
               'separator' => 'separator',
          ], [
               'name'      => 'previewe',
               'action'    => 'EasyMDE.togglePreview',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#eye"></use></svg>',
               'title'     => 'app.preview',
          ], [
               'name'      => 'side-by-side',
               'action'    => 'EasyMDE.toggleSideBySide',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#layout-columns"></use></svg>',
               'title'     => 'app.toggle_two',
          ], [
               'name'      => 'fullscreen',
               'action'    => 'EasyMDE.toggleFullScreen',
               'icon'      => '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#arrows-maximize"></use></svg>',
               'title'     => 'app.fullscreen',
          ], [
               'separator' => 'separator',
          ],
     ],
];
