<?= insert(
  '/_block/navigation/nav',
  [
    'list' =>  [
      [
        'url'   => url('setting'),
        'title' => 'app.settings',
        'id'    => 'settings',
      ], [
        'url'   => url('setting.avatar'),
        'title' => 'app.avatar',
        'id'    => 'avatar',
      ], [
        'url'   => url('setting.security'),
        'title' => 'app.password',
        'id'    => 'security',
      ], [
        'url'   => url('setting.notification'),
        'title' => 'app.notifications',
        'id'    => 'notifications',
        // 'css'    => 'mb-none',
      ], [
        'url'   => url('setting.preferences'),
        'title' => 'app.preferences',
        'id'    => 'preferences',
      ], [
        'url'   => url('setting.ignored'),
        'title' => 'app.ignored',
        'id'    => 'ignored',
      ],
    ],
  ]
);
?>