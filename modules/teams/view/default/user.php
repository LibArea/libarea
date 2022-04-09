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
            <a class="mr15" href="<?= getUrlByName('team.view', ['id' => $team['id']]); ?>">
              <?= $team['name']; ?>
            </a>
            <a class="mr15 gray-600" href="<?= getUrlByName('team.edit', ['id' => $team['id']]); ?>">
              <?= __('edit'); ?>
            </a>
            <?= sprintf(__('team.delete'), $team['id']); ?>
            <div class="content-body">
              <?= Content::text($team['content'], 'line'); ?>
            </div>
          <?php else : ?>
            <?= $team['name']; ?>
            <div class="red"><?= sprintf(__('team.reestablish'), $team['id']); ?></div>
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
    <?= __('team.info'); ?>
  </div>
  <aside>
    <?= Tpl::insert('/footer', ['user' => $user]); ?>