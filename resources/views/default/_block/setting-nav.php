<ul class="flex flex-row list-none m0 p0 center size-15">

  <?= tabs_nav(
    $uid['user_id'],
    $data['sheet'],
    $pages = [
      [
        'id' => 'settings',
        'url' => getUrlByName('setting', ['login' => $uid['user_login']]),
        'content' => Translate::get('settings'),
        'icon' => 'bi bi-gear'
      ], [
        'id' => 'avatar',
        'url' => getUrlByName('setting.avatar', ['login' => $uid['user_login']]),
        'content' => Translate::get('avatar'),
        'icon' => 'bi bi-emoji-smile'
      ], [
        'id' => 'security',
        'url' => getUrlByName('setting.security', ['login' => $uid['user_login']]),
        'content' => Translate::get('password'),
        'icon' => 'bi bi-lock'
      ], [
        'id' => 'notifications',
        'url' => getUrlByName('setting.notifications', ['login' => $uid['user_login']]),
        'content' => Translate::get('notifications'),
        'icon' => 'bi bi-app-indicator'
      ]
    ]
  ); ?>

</ul>