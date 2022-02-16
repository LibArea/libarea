<?php
/*
 * Fields for adding and editing a site in the catalog 
 * Поля для добавления и редактирования сайта в каталоге
 */

return [
    'add' =>  [
       [
          'title' => 'title',
          'tl'      => 1, // member's trust level 
          'arr'   => ['min' => 14, 'max' => 250, 'required' => true],
          'name'  => 'title'
        ], [
          'title' => 'URL',
          'tl'      => 1,
          'arr'   => ['required' => true],
          'name'  => 'url',
        ],  [
          'title'   => 'description',
          'tl'      => 1,
          'arr'     => ['type' => 'textarea', 'required' => true],
          'name'    => 'content',
        ]
      ],
];
