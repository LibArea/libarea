<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<?php $team = $data['team']; ?>

<main>
  <div class="box-white">
    <a href="<?= getUrlByName('teams'); ?>"><?= __('teams'); ?></a> /
    <span class="red"><?= $team['name']; ?></span>

    <div class="content-body">
      <?= Content::text($team['content'], 'text'); ?>
    </div>

    <div class="mb15 gray-600">
      <i class="bi-calendar-week mr5"></i> <?= Html::langDate($team['created_at']); ?> / <?= Html::langDate($team['updated_at']); ?>
    </div>

    <h2><?= __('owner'); ?></h2>
    <div class="mb15 mt5">
      <?= Html::image($team['avatar'], $team['login'], 'ava-base', 'avatar', 'small'); ?>
      <a href="<?= getUrlByName('profile', ['login' => $team['login']]); ?>"><?= $team['login']; ?></a>
    </div>

    <h2><?= __('users'); ?></h2>
    <div class="mb15 mt5">
      <?php foreach ($data['team_users'] as $usr) : ?>
        <div class="mb15">
          <?= Html::image($usr['avatar'], $usr['login'], 'ava-base', 'avatar', 'small'); ?>
          <a href="<?= getUrlByName('profile', ['login' => $usr['login']]); ?>"><?= $usr['login']; ?></a>
        </div>
      <?php endforeach; ?>
    </div>

    <a href="<?= getUrlByName('team.edit', ['id' => $team['id']]); ?>" class="btn btn-primary"><?= __('edit'); ?></a>
  </div>
</main>

<?= Tpl::insert('/footer', ['user' => $user]); ?>