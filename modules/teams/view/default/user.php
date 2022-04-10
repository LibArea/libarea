<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<main>
  <div class="box-flex-white">
    <p class="m0"><?= __('teams'); ?></p>
    <?php if ($data['count'] < $data['limit']) : ?>
      <a href="<?= getUrlByName('team.add'); ?>" class="btn btn-primary"><?= __('add'); ?></a>
    <?php endif; ?>
  </div>
  <div class="box-white">
    <?php if (!empty($data['teams'])) : ?>

      <?php foreach ($data['teams'] as $team) : ?>
        <div class="mb15">
          <?php if ($team['is_deleted'] == 0) : ?>
            <h2><a class="mr15" href="<?= getUrlByName('team.view', ['id' => $team['id']]); ?>"><?= $team['name']; ?></a></h2>
            <div class="content-body">
              <?= Content::text($team['content'], 'line'); ?>
            </div>
            <blockquote class="bg-white">
              <?php if ($team['users_list']) : ?>
                <?= \Modules\Teams\App\Teams::users($team['users_list']); ?>
              <?php else : ?>
                <?= __('no.users'); ?>...
              <?php endif; ?>
            </blockquote> 
            <a class="mr15 gray-600" href="<?= getUrlByName('team.edit', ['id' => $team['id']]); ?>">
              <?= __('edit'); ?>
            </a>
            <span class="action-team gray-600" data-id="<?= $team['id']; ?>"><?= __('remove'); ?></span>
          <?php else : ?>
            <?= $team['name']; ?>
            <div class="gray-600">
              <?= __('team.reestablish'); ?>. <span class="action-team" data-id="<?= $team['id']; ?>"><?= __('recover'); ?></span>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

    <?php else : ?>
      <div class="mt15">
        <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.teams'), 'icon' => 'bi-info-lg']); ?>
      </div>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box-white">
    <h3 class="uppercase-box"><?= __('info'); ?></h3>
    <span class="gray-600"><?= __('team.info'); ?></span>
    <h3 class="uppercase-box mt15"><?= __('owner'); ?></h3>
    <div class="mb15">
      <?= Html::image($user['avatar'], $user['login'], 'ava-base', 'avatar', 'small'); ?>
      <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
    </div>
  </div>
  <aside>

    <?= Tpl::insert('/footer', ['user' => $user]); ?>