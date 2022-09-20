<?php $team = $data['team']; ?>

<main>
  <a href="<?= url('teams'); ?>"><?= __('team.home'); ?></a> /
  <span class="red"><?= $team['team_name']; ?></span>

  <div class="content-body">
    <?= markdown($team['team_content'], 'text'); ?>
  </div>

  <div class="mb15 gray-600">
    <svg class="icons">
      <use xlink:href="/assets/svg/icons.svg#calendar"></use>
    </svg> <?= Html::langDate($team['team_date']); ?> / <?= Html::langDate($team['team_modified']); ?>
  </div>

  <h2><?= __('team.owner'); ?></h2>
  <div class="mb15 mt5">
    <?= Img::avatar($team['avatar'], $team['login'], 'img-base', 'small'); ?>
    <a href="<?= url('profile', ['login' => $team['login']]); ?>"><?= $team['login']; ?></a>
  </div>

  <h2><?= __('team.users'); ?></h2>
  <div class="mb15 mt5">
    <?php foreach ($data['team_users'] as $usr) : ?>
      <div class="mb15">
        <?= Img::avatar($usr['avatar'], $usr['login'], 'img-base', 'small'); ?>
        <a href="<?= url('profile', ['login' => $usr['login']]); ?>"><?= $usr['login']; ?></a>
      </div>
    <?php endforeach; ?>
  </div>

  <a href="<?= url('team.edit', ['id' => $team['team_id']]); ?>" class="btn btn-primary"><?= __('team.edit'); ?></a>
</main>