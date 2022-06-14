<ul class="menu">
  <?php foreach ($users as $user) : ?>
    <li>
      <a href="<?= url('profile', ['login' => $user['login']]); ?>">
        <?= Html::image($user['avatar'], $user['login'], 'img-sm mr5', 'avatar', 'max'); ?>
        <?= $user['login']; ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
