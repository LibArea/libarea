<?php $team = $data['team']; ?>

<main>
  <div class="box">
    <a href="<?= url('teams'); ?>"><?= __('team.home'); ?></a> /
    <span class="red"><?= $team['team_name']; ?></span>

    <div class="content-body">
      <?= Content::text($team['team_content'], 'text'); ?>
    </div>

    <div class="mb15 gray-600">
      <i class="bi-calendar-week mr5"></i> <?= Html::langDate($team['team_date']); ?> / <?= Html::langDate($team['team_modified']); ?>
    </div>

    <h2><?= __('team.owner'); ?></h2>
    <div class="mb15 mt5">
      <?= Html::image($team['avatar'], $team['login'], 'img-base', 'avatar', 'small'); ?>
      <a href="<?= url('profile', ['login' => $team['login']]); ?>"><?= $team['login']; ?></a>
    </div>

    <h2><?= __('team.users'); ?></h2>
    <div class="mb15 mt5">
      <?php foreach ($data['team_users'] as $usr) : ?>
        <div class="mb15">
          <?= Html::image($usr['avatar'], $usr['login'], 'img-base', 'avatar', 'small'); ?>
          <a href="<?= url('profile', ['login' => $usr['login']]); ?>"><?= $usr['login']; ?></a>
        </div>
      <?php endforeach; ?>
    </div>

    <a href="<?= url('team.edit', ['id' => $team['team_id']]); ?>" class="btn btn-primary"><?= __('team.edit'); ?></a>
  </div>
</main>
