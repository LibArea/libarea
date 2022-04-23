<?php
/*
 * Facets (fields for adding and editing)
 * Фасеты (поля для добавления и редактирования)
 */
$ch = __('characters');
return [
  'forma' =>  [
    [
      'title'   => __('title'),
      'tl'      => 1, // member's trust level 
      'arr'     => ['required' => true, 'min' => 3, 'max' => 64, 'help' => '3 - 64 ' . $ch],
      'name'    => 'facet_title'
    ], [
      'title'   => __('short.description'),
      'tl'      => 1,
      'arr'     => ['required' => true, 'min' => 9, 'max' => 120, 'help' => '9 - 120 ' . $ch],
      'name'    => 'facet_short_description',
    ], [
      'title'   => __('title') . ' (SEO)',
      'tl'      => 1,
      'arr'     => ['required' => true, 'min' => 3, 'max' => 32, 'help' => '3 - 32 ' . $ch],
      'name'    => 'facet_seo_title',
    ], [
      'title'   => __('slug'),
      'tl'      => 1,
      'arr'     => ['required' => true, 'min' => 4, 'max' => 32, 'help' => '4 - 32 ' . $ch],
      'name'    => 'facet_slug',
    ], [
      'title'   => __('meta.description'),
      'tl'      => 1,
      'arr'     => ['type' => 'textarea', 'required' => true, 'min' => 34, 'help' => '> 34 ' . $ch],
      'name'    => 'facet_description',
    ]
  ],
];