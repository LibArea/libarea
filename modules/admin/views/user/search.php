<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'users.all',
        'url' => url('admin.users'),
        'name' => __('admin.all'),
      ], [
        'id' => 'users.ban',
        'url' => url('admin.users.ban'),
        'name' => __('admin.deleted'),
      ], [
        'id' => 'users.search',
        'url' => url('admin.users.search'),
        'name' => __('admin.search'),
      ]
    ]
  ]
); ?>

<form class="max-w-md" action="<?= url('admin.user.search', method: 'post'); ?>" method="post">
  <?= $container->csrf()->field(); ?>
  <fieldset>
    <label for="word"><?= __('admin.nickname'); ?></label>
    <input type="text" name="login" value="<?php if (!empty($data['login'])) : ?><?= $data['login']; ?><?php endif; ?>">
  </fieldset>
    <fieldset>
    <label for="word"><?= __('admin.ip'); ?></label>
    <input type="text" name="ip" value="<?php if (!empty($data['ip'])) : ?><?= $data['ip']; ?><?php endif; ?>">
  </fieldset>
  <?= Html::sumbit(__('admin.search')); ?>
</form>

<?php if (!empty($data['results'])) : ?>
  <br>
  <?php foreach ($data['results'] as $user) : ?>
    <a href="<?= url('profile', ['login' => $user['login']]); ?>">
      <?= $user['login']; ?>
    </a>
    (id <?= $user['id']; ?>)
    <?php if ($user['name']) : ?>
      (<?= $user['name']; ?>)
    <?php endif; ?>
    <sup class="gray-600">TL:<?= $user['trust_level']; ?></sup>
    <span class="green"> - <?= $user['email']; ?></span>
    <?php if ($user['trust_level'] != 10) : ?>
      <a title="<?= __('admin.edit'); ?>" href="<?= url('admin.user.edit.form', ['id' => $user['id']]); ?>">
        <svg class="icon">
          <use xlink:href="/assets/svg/icons.svg#edit"></use>
        </svg>
      </a>
    <?php endif; ?> 
	<sup class="gray-600"><?= $user['reg_ip']; ?></sup> <br>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (!empty($data['login']) || !empty($data['ip']) ) : ?><p><?= __('search.no_results'); ?>...</p><?php endif; ?>
<?php endif; ?>
</main>
</div>
<?= insertTemplate('footer'); ?>