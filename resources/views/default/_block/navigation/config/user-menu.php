<?= insert(
  '/_block/navigation/menu-user',
  [
    'menu' => [
      [
        'tl'    => 1,
        'url'   => 'setting',
        'icon'  => 'settings',
        'title' => 'app.settings',
        'id'    => '',
      ], [
        'tl'    => 1,
        'icon'  => 'post',
        'url'   => 'drafts',
        'title' => 'app.drafts',
        'id'    => '',
      ], [
        'tl'    => 1,
        'url'   => 'notifications',
        'icon'  => 'bell',
        'title' => 'app.notifications',
        'id'    => '',
      ], [
        'tl'    => 1,
        'icon'  => 'mail',
        'url'   => 'messages',
        'title' => 'app.messages',
        'id'    => '',
      ], [
        'tl'    => 1,
        'url'   => 'favorites',
        'icon'  => 'bookmark',
        'title' => 'app.favorites',
        'id'    => '',
      ], [
        'tl'    => 2,
        'icon'  => 'link',
        'url'   => 'invitations',
        'title' => 'app.invites',
        'id'    => '',
      ], [
        'tl'    => 2,
        'icon'  => 'chart',
        'url'   => 'polls',
        'title' => 'app.polls',
        'id'    => '',
      ], [
        'hr'    => 'hr',
      ], [
        'tl'    => 10,
        'icon'  => 'users-2',
        'url'   => 'admin',
        'title' => 'app.admin',
        'id'    => '',
      ], [
        'url'   => 'logout',
        'icon'  => 'corner-down-right',
        'title' => 'app.sign_out',
        'id'    => '',
      ],
    ],
  ]
);
?>