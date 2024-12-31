<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <h2><?= __('app.ignored_users'); ?></h2>

    <?php if ($data['ignored']) : ?>
      <?php foreach ($data['ignored'] as $user) : ?>
        <div class="flex flex-row items-center justify-between mb15">
          <a href="/@<?= $user['login']; ?>">
            <?= Img::avatar($user['avatar'], $user['login'], 'img-sm', 'max'); ?>
            <?= $user['login']; ?>
          </a>
          <a id="ignore_<?= $user['ignored_id']; ?>" class="add-ignore red" data-id="<?= $user['ignored_id']; ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#lock"></use>
            </svg></i>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <span class="gray-600"><?= __('app.ignored_no'); ?></span>
    <?php endif; ?>
  </div>
</main>

<aside>
  <div class="box">
    <?= __('app.ignored_users_help'); ?>
  </div>
</aside>