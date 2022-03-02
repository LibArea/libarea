<?php
/*
 * Customizing the editor toolbar
 * Настрока панели инструментов редактора
 */

return [
     [
          'name'      => 'bold',
          'action'    => 'EasyMDE.toggleBold',
          'className' => 'bi-type-bold',
          'title'     => Translate::get('bold'),
     ], [
          'name'      => 'italic',
          'action'    => 'EasyMDE.toggleItalic',
          'className' => 'bi-type-italic',
          'title'     => Translate::get('italic'),
     ], [
          'name'      => 'strikethrough',
          'action'    => 'EasyMDE.toggleStrikethrough',
          'className' => 'bi-type-strikethrough',
          'title'     => Translate::get('strikethrough'),
     ], [
          'name'      => 'quote',
          'action'    => 'EasyMDE.toggleBlockquote',
          'className' => 'bi-quote',
          'title'     => Translate::get('quote'),
     ], [
          'name'      => 'code',
          'action'    => 'EasyMDE.toggleCodeBlock',
          'className' => 'bi-code',
          'title'     => Translate::get('code'),
     ],  [
          'name'      => 'link',
          'action'    => 'EasyMDE.drawLink',
          'className' => 'bi-link-45deg',
          'title'     => Translate::get('link'),
     ], [
          'separator' => 'separator',
     ], [
          'name'      => 'upload-image',
          'action'    => 'EasyMDE.drawUploadedImage',
          'className' => 'bi-camera',
          'title'     => Translate::get('foto'),
     ], [
          'separator' => 'separator',
     ], [
          'name'      => 'previewe',
          'action'    => 'EasyMDE.togglePreview',
          'className' => 'bi-eye',
          'title'     => Translate::get('preview'),
     ], [
          'name'      => 'side-by-side',
          'action'    => 'EasyMDE.toggleSideBySide',
          'className' => 'bi-layout-split',
          'title'     => Translate::get('toggle.two'),
     ], [
          'name'      => 'fullscreen',
          'action'    => 'EasyMDE.toggleFullScreen',
          'className' => 'bi-arrows-move',
          'title'     => Translate::get('fullscreen'),
     ], [
          'separator' => 'separator',
     ],
];
