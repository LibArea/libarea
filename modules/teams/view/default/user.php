<?= Tpl::insert('/header', ['data' => $data, 'meta' => $meta]); ?>

<main>
  <div class="box-flex justify-between">
    <p class="m0"><?= __('team.home'); ?></p>
    <?php if ($data['count'] < $data['limit']) : ?>
      <a href="<?= url('team.add'); ?>" class="btn btn-primary"><?= __('team.add_team'); ?></a>
    <?php endif; ?>
  </div>
  <div class="box">
    <?php if (!empty($data['teams'])) : ?>

      <?php foreach ($data['teams'] as $team) : ?>
        <div class="mb15">
          <?php if ($team['is_deleted'] == 0) : ?>
            <h2><a class="mr15" href="<?= url('team.view', ['id' => $team['id']]); ?>"><?= $team['name']; ?></a></h2>
            <div class="content-body">
              <?= Content::text($team['content'], 'line'); ?>
            </div>
            <blockquote class="box">
              <?php if ($team['users_list']) : ?>
                <?= \Modules\Teams\App\Teams::users($team['users_list']); ?>
              <?php else : ?>
                <?= __('team.no_users'); ?>...
              <?php endif; ?>
            </blockquote>
            <a class="mr15 gray-600" href="<?= url('team.edit', ['id' => $team['id']]); ?>">
              <?= __('team.edit'); ?>
            </a>
            <span class="action-team gray-600" data-id="<?= $team['id']; ?>"><?= __('team.remove'); ?></span>
          <?php else : ?>
            <?= $team['name']; ?>
            <div class="gray-600">
              <?= __('team.reestablish'); ?>. <span class="action-team" data-id="<?= $team['id']; ?>"><?= __('team.recover'); ?></span>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

    <?php else : ?>
      <div class="mt15">
        <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('team.no_teams'), 'icon' => 'bi-info-lg']); ?>
      </div>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box">
    <h3 class="uppercase-box"><?= __('team.clue'); ?></h3>
    <span class="gray-600"><?= __('team.info'); ?></span>
    <h3 class="uppercase-box mt15"><?= __('team.owner'); ?></h3>
    <div class="mb15">
      <?= Html::image($user['avatar'], $user['login'], 'img-base', 'avatar', 'small'); ?>
      <a href="<?= url('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
    </div>
  </div>
  <aside>

  <?= Tpl::insert('/footer'); ?>