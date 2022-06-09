<main>
  <div class="box bg-violet center">
    <h1 class="m0 text-xl font-normal"><?= __('meta.' . $data['sheet'] . '_users'); ?></h1>
    <span class="gray-600">
      <?= __('meta.' . $data['sheet'] . '_users_info'); ?>.
    </span>
  </div>

  <div class="flex justify-between mb20">
    <ul class="nav">

      <?= insert(
        '/_block/navigation/nav',
        [
          'list' => [
            [
              'id'    => $data['sheet'] . '_users_all',
              'url'   => url('users.all'),
              'title' => __('app.all'),
            ], [
              'id'    => $data['sheet'] . '_users_new',
              'url'   => url('users.new'),
              'title' => __('app.new_ones'),
            ],
          ]
        ]
      );
      ?>

    </ul>
  </div>

    <div class="flex flex-wrap justify-between">
      <?php foreach ($data['users'] as $user) : ?>
        <div class="w160 mb-w100 mb20 center">
          <a href="<?= url('profile', ['login' => $user['login']]); ?>">
            <?= Html::image($user['avatar'], $user['login'], 'img-lg', 'avatar', 'max'); ?>
            <div class="block mt5">
              <?= $user['login']; ?>
            </div>
            <?php if ($user['name']) : ?>
              <span class="gray text-sm"><?= $user['name']; ?></span>
            <?php endif; ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

  <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url('users.' . $data['sheet'])); ?>
</main>