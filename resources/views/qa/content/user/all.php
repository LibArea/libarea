<main class="col-span-12 mb-col-12">
  <div class="box-white bg-violet-50 center">
    <h1 class="m0 text-xl font-normal"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="m15">
    <ul class="nav">

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

  <div class="box">
    <div class="grid grid-cols-6 mb-grid-cols-2 gap-2">
    <?php foreach ($data['users'] as $ind => $user) { ?>
        <a class="center mb20" href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
          <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava-lg'); ?>
          <div class="block mt5">
            <?= $user['login']; ?>
          </div>
          <?php if ($user['name']) { ?>
            <span class="gray text-sm"><?= $user['name']; ?></span>
          <?php } ?>
         </a>
    <?php } ?>
    </div>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
</main>