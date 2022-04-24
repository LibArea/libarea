</div>
<footer class="box-shadow-top">
  <div class="wrap">
    <div class="left">
      <div class="mb5">
        <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?>
        <span class="mb-none">— <?= __('community'); ?></span>
      </div>
      <a rel="nofollow noopener" class="icon" title="<?= __('DISCORD'); ?>" href="https://discord.gg/dw47aNx5nU"><i class="bi-discord text-2xl"></i></a>
      <a rel="nofollow noopener" class="icon" title="<?= __('VK'); ?>" href="https://vk.com/libarea">VK</a>
      <a rel="nofollow noopener" class="icon" title="<?= __('GitHub'); ?>" href="https://github.com/libarea/agouti"><i class="bi-github text-2xl"></i></a>
    </div>
    <div class="flex right">
      <ul class="mb-none">
        <li><a href="<?= getUrlByName('blogs.all'); ?>"><?= __('blogs'); ?></a></li>
        <li><a href="<?= getUrlByName('topics.all'); ?>"><?= __('topics'); ?></a></li>
        <li><a href="<?= getUrlByName('web'); ?>"><?= __('catalog'); ?></a></li>
      </ul>
      <ul class="mb-none">
        <li><a href="<?= getUrlByName('users.all'); ?>"><?= __('users'); ?></a></li>
        <li><a href="<?= getUrlByName('answers'); ?>"><?= __('answers'); ?></a></li>
        <li><a href="<?= getUrlByName('comments'); ?>"><?= __('comments'); ?></a></li>
      </ul>
      <ul class="mb-pl0">
        <li><a href="<?= getUrlByName('facet.article', ['facet_slug' => 'info', 'slug' => Config::get('facets.page-one')]); ?>"><?= __('info'); ?></a></li>
        <li><a href="<?= getUrlByName('facet.article', ['facet_slug' => 'info', 'slug' => Config::get('facets.page-two')]); ?>"><?= __('privacy'); ?></a></li>
      </ul>
    </div>
  </div>
</footer>

<?= Tpl::insert('/scripts', ['uid' => $user['id'], 'scroll' => $user['scroll']]); ?>