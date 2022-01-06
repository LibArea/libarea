<ul class="flex flex-row list-none m0 p0 center">

  <?= tabs_nav(
    'nav',
    $data['sheet'],
    $uid,
    $pages = [
      [
        'id' => 'settings',
        'url' => getUrlByName('setting'),
        'title' => Translate::get('settings'),
        'icon' => 'bi bi-gear'
      ], [
        'id' => 'avatar',
        'url' => getUrlByName('setting.avatar'),
        'title' => Translate::get('avatar'),
        'icon' => 'bi bi-emoji-smile'
      ], [
        'id' => 'security',
        'url' => getUrlByName('setting.security'),
        'title' => Translate::get('password'),
        'icon' => 'bi bi-lock'
      ], [
        'id' => 'notifications',
        'url' => getUrlByName('setting.notifications'),
        'title' => Translate::get('notifications'),
        'icon' => 'bi bi-app-indicator'
      ]
    ]
  ); ?>

</ul>