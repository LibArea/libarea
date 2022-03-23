<main class="col-two">
  <div class="bg-violet-50 box center">
    <h1 class="m0 text-xl font-normal"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="box-flex">
    <ul class="nav">

      <?= Html::nav(
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

    <div class="flex flex-wrap">
      <?php foreach ($data['users'] as $user) { ?>
      <div class="w-20 mb20 mb-w-33 center">
        <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
          <?= Html::image($user['avatar'], $user['login'], 'ava-lg', 'avatar', 'max'); ?>
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
  <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
</main>