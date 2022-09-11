</div>
<footer class="footer box-shadow-top" itemtype="http://schema.org/WPFooter">
  <?php $type = $data['type'] ?? false; ?> 
  <?php if (UserData::getUserScroll() == false || $type != 'main') : ?> 
  <div class="wrap">
    <div class="left">
      <div class="mb5">
        <?= config('meta.name'); ?> &copy; <?= date('Y'); ?>
        <span class="mb-none">— <?= __('app.community'); ?></span>
      </div>
      <a rel="nofollow noopener" class="icon" title="DISCORD" href="https://discord.gg/adJnPEGZZZ"><svg class="icons max">
          <use xlink:href="/assets/svg/icons.svg#discord"></use>
        </svg></a>
      <a rel="nofollow noopener" class="icon" title="VK" href="https://vk.com/libarea"><svg class="icons max">
          <use xlink:href="/assets/svg/icons.svg#vk"></use>
        </svg></a>
      <a rel="nofollow noopener" class="icon" title="GitHub" href="https://github.com/LibArea/libarea"><svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#github"></use>
        </svg></a>
    </div>
    <div class="flex right">
      <ul class="mb-none">
        <li><a href="<?= url('blogs.all'); ?>"><?= __('app.blogs'); ?></a></li>
        <li><a href="<?= url('topics.all'); ?>"><?= __('app.topics'); ?></a></li>
        <li><a href="<?= url('web'); ?>"><?= __('app.catalog'); ?></a></li>
      </ul>
      <ul class="mb-none">
        <li><a href="<?= url('users.all'); ?>"><?= __('app.users'); ?></a></li>
        <li><a href="<?= url('answers'); ?>"><?= __('app.answers'); ?></a></li>
        <li><a href="<?= url('comments'); ?>"><?= __('app.comments'); ?></a></li>
      </ul>
      <ul>
        <?php foreach (config('facets.page') as $page) : ?>
          <li><a href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => $page]); ?>"><?= __('app.' . $page); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>
</footer>

<?= insert('/scripts'); ?>