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
          'title'     => __('bold'),
     ], [
          'name'      => 'italic',
          'action'    => 'EasyMDE.toggleItalic',
          'className' => 'bi-type-italic',
          'title'     => __('italic'),
     ], [
          'name'      => 'strikethrough',
          'action'    => 'EasyMDE.toggleStrikethrough',
          'className' => 'bi-type-strikethrough',
          'title'     => __('strikethrough'),
     ], [
          'name'      => 'quote',
          'action'    => 'EasyMDE.toggleBlockquote',
          'className' => 'bi-quote',
          'title'     => __('quote'),
     ], [
          'name'      => 'code',
          'action'    => 'EasyMDE.toggleCodeBlock',
          'className' => 'bi-code',
          'title'     => __('code'),
     ],  [
          'name'      => 'link',
          'action'    => 'EasyMDE.drawLink',
          'className' => 'bi-link-45deg',
          'title'     => __('link'),
     ], [
          'separator' => 'separator',
     ], [
          'name'      => 'upload-image',
          'action'    => 'EasyMDE.drawUploadedImage',
          'className' => 'bi-camera',
          'title'     => __('foto'),
     ], [
          'separator' => 'separator',
     ], [
          'name'      => 'previewe',
          'action'    => 'EasyMDE.togglePreview',
          'className' => 'bi-eye',
          'title'     => __('preview'),
     ], [
          'name'      => 'side-by-side',
          'action'    => 'EasyMDE.toggleSideBySide',
          'className' => 'bi-layout-split',
          'title'     => __('toggle.two'),
     ], [
          'name'      => 'fullscreen',
          'action'    => 'EasyMDE.toggleFullScreen',
          'className' => 'bi-arrows-move',
          'title'     => __('fullscreen'),
     ], [
          'separator' => 'separator',
     ],
];
