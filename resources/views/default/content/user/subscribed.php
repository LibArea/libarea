<div class="col-span-2 justify-between no-mob">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $uid,
        $pages = [
          [
            'id'    => 'favorites',
            'url'   => getUrlByName('user.favorites', ['login' => $uid['user_login']]),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark'
          ],
          [
            'id'    => 'subscribed',
            'url'   => getUrlByName('user.subscribed', ['login' => $uid['user_login']]),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="mt10">
    <?= import('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </div>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-preferences'), 'uid' => $uid]); ?>