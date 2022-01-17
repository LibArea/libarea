<div class="col-span-2 justify-between mb-none">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $user,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-10 mb-col-12">
  <div class="bg-white   center justify-between br-box-gray br-rd5 p15 mb15">
    <h1 class="m0 text-xl font-normal"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-500">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <ul class="flex flex-row list-none m0 p0 center text-sm">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => $data['type'] . 's.all',
            'url'   => getUrlByName($data['type'] . 's.all'),
            'title' => Translate::get('all'),
            'icon'  => 'bi bi-app'
          ], [
            'id'    => $data['type'] . 's.new',
            'url'   => getUrlByName($data['type'] . 's.new'),
            'title' => Translate::get('new ones'),
            'icon'  => 'bi bi-sort-up'
          ],
        ]
      );
      ?>

    </ul>
  </div>

  <div class="bg-white br-rd5 br-box-gray p20">
    <div class="max-width mr-auto w-100 grid grid-cols-6 gap-2 justify-between">
      <?php foreach ($data['users'] as $ind => $user) { ?>
        <div class="center inline pr10 pl10 mb20 mb-col-2">
          <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
            <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'br-rd-50 w60 h60'); ?>
          </a>
          <a class="block dark-gray-300 black mt5" href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
            <?= $user['login']; ?>
          </a>
          <?php if ($user['name']) { ?>
            <span class="gray dark-gray-300 text-sm"><?= $user['name']; ?></span>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
</main>
</div>
<?= Tpl::import('/_block/wide-footer'); ?>