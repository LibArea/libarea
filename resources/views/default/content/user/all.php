<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<main class="col-span-10 mb-col-12">
  <div class="box-white center">
    <h1 class="m0 text-xl font-normal"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="bg-white box-flex br-box-gray">
    <ul class="flex flex-row list-none text-sm">

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
            <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava-lg'); ?>
            <div class="block mt5">
              <?= $user['login']; ?>
            </div>
            <?php if ($user['name']) { ?>
              <span class="gray text-sm"><?= $user['name']; ?></span>
            <?php } ?>
          </a>
        </div>
      <?php } ?>
    </div>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
</main>