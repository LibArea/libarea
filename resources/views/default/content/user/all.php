<main class="col-two">
  <div class="box center">
    <h1 class="m0 text-xl font-normal"><?= __('meta.' . $data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= __('meta.' . $data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="box-flex">
    <ul class="nav">

      <?= Tpl::insert(
        '/_block/navigation/nav',
        [
          'type' => $data['sheet'],
          'list' => [
            [
              'id'    => $data['type'] . 's.all',
              'url'   => url($data['type'] . 's.all'),
              'title' => __('app.all'),
              'icon'  => 'bi-app'
            ], [
              'id'    => $data['type'] . 's.new',
              'url'   => url($data['type'] . 's.new'),
              'title' => __('app.new_ones'),
              'icon'  => 'bi-sort-up'
            ],
          ]
        ]
      );
      ?>

    </ul>
  </div>

  <div class="box">
    <div class="flex flex-wrap">
      <?php foreach ($data['users'] as $user) : ?>
        <div class="w-20 mb20 mb-w-33 center">
          <a href="<?= url('profile', ['login' => $user['login']]); ?>">
            <?= Html::image($user['avatar'], $user['login'], 'ava-lg', 'avatar', 'max'); ?>
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
  </div>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url($data['sheet'])); ?>
</main>