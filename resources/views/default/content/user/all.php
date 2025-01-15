<main class="w-100">
  <div class="box">
    <div class="mb15">
      <h1 class="title"><?= __('meta.' . $data['sheet'] . '_users'); ?></h1>
      <span class="gray-600 text-sm">
        <?= __('meta.' . $data['sheet'] . '_users_info'); ?>.
      </span>
    </div>

    <div class="nav-bar">
      <ul class="nav">

        <?= insert(
          '/_block/navigation/nav',
          [
            'list' => [
              [
                'id'    => $data['sheet'] . '_users_all',
                'url'   => url('users.all'),
                'title' => 'app.all',
              ], [
                'id'    => $data['sheet'] . '_users_new',
                'url'   => url('users.new'),
                'title' => 'app.new_ones',
              ],
            ]
          ]
        );
        ?>

      </ul>
    </div>

    <div class="flex flex-wrap justify-between">
      <?php foreach ($data['users'] as $user) : ?>
        <div class="w200 mb20">
          <a href="<?= url('profile', ['login' => $user['login']]); ?>">
            <?= Img::avatar($user['avatar'], $user['login'], 'img-lg', 'max'); ?>
            <div class="block mt5 nickname<?php if (Html::loginColor($user['created_at'] ?? false)) : ?> new<?php endif; ?>">
              <?= $user['login']; ?>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url('users.' . $data['sheet'])); ?>
  </div>
</main>