<?php
$fs = $data['facet'];
$url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main>
  <div class="box">
    <div class="nav-bar">
      <ul class="nav">
        <?= insert(
          '/_block/navigation/nav',
          [
            'list' => [
              [
                'id'        => 'topic',
                'url'       => url('facet.form.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.edit_' . $data['type'],
              ], [
                'id'        => 'blog',
                'url'       => url('team.form.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.team',
              ]
            ]
          ]
        ); ?>
      </ul>
      <a class="gray-600" href="<?= $url; ?>"><?= __('app.go_to'); ?></a>
    </div>

    <form class="max-w-md" action="<?= url('team.edit', ['id' => $fs['facet_id'], 'type' => $data['type']], method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>

      <?= insert('/_block/form/select/users-team', ['users' => $data['users_team']]); ?>

      <div class="right"><?= Html::sumbit(__('app.add')); ?></div>
    </form>

    <h2><?= __('app.team'); ?></h2>
    <?php if ($data['users_team']) : ?>
      <?php foreach ($data['users_team'] as $usr) : ?>
        <div class="mb15">
          <?= Img::avatar($usr['avatar'], $usr['value'], 'img-base', 'small'); ?>
          <a href="<?= url('profile', ['login' => $usr['value']]); ?>"><?= $usr['value']; ?></a>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <?= __('app.team_not_users'); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.team_info'); ?>
  </div>
</aside>
<?= insert('/_block/add-js-css'); ?>