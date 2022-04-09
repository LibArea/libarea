<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<?php $team = $data['team']; ?>

<main>
  <div class="box-white">
    <a href="<?= getUrlByName('teams'); ?>"><?= __('teams'); ?></a> /
    <span class="red"><?= $team['name']; ?></span>

    <div class="content-body">
      <?= Content::text($team['content'], 'text'); ?>
    </div>

    <h2><?= __('owner'); ?></h2>
    <div class="mb15">
      <?= Html::image($team['avatar'], $team['login'], 'ava-base', 'avatar', 'small'); ?>
      <a href="<?= getUrlByName('profile', ['login' => $team['login']]); ?>"><?= $team['login']; ?></a>
    </div>

    <h2><?= __('users'); ?></h2>
    <div class="mb15">
      ...
    </div>

    <a href="<?= getUrlByName('team.edit', ['id' => $team['id']]); ?>" class="btn btn-primary"><?= __('edit'); ?></a>
  </div>
</main>

<?= Tpl::insert('/footer', ['user' => $user]); ?>