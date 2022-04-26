</div>
<footer class="box-shadow-top">
  <div class="wrap">
    <div class="left">
      <div class="mb5">
        <?= config('meta.name'); ?> &copy; <?= date('Y'); ?>
        <span class="mb-none">— <?= __('community'); ?></span>
      </div>
      <a rel="nofollow noopener" class="icon" title="DISCORD" href="https://discord.gg/dw47aNx5nU"><i class="bi-discord text-2xl"></i></a>
      <a rel="nofollow noopener" class="icon" title="VK" href="https://vk.com/libarea">VK</a>
      <a rel="nofollow noopener" class="icon" title="GitHub" href="https://github.com/libarea/agouti"><i class="bi-github text-2xl"></i></a>
    </div>
    <div class="flex right">
      <ul class="mb-none">
        <li><a href="<?= url('blogs.all'); ?>"><?= __('blogs'); ?></a></li>
        <li><a href="<?= url('topics.all'); ?>"><?= __('topics'); ?></a></li>
        <li><a href="<?= url('web'); ?>"><?= __('catalog'); ?></a></li>
      </ul>
      <ul class="mb-none">
        <li><a href="<?= url('users.all'); ?>"><?= __('users'); ?></a></li>
        <li><a href="<?= url('answers'); ?>"><?= __('answers'); ?></a></li>
        <li><a href="<?= url('comments'); ?>"><?= __('comments'); ?></a></li>
      </ul>
      <ul class="mb-pl0">
        <li><a href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => config('facets.page-one')]); ?>"><?= __('info'); ?></a></li>
        <li><a href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => config('facets.page-two')]); ?>"><?= __('privacy'); ?></a></li>
      </ul>
    </div>
  </div>
</footer>

<?= Tpl::insert('/scripts'); ?>