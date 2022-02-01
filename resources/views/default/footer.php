</div>
<footer class="box-shadow-top">
  <div class="left">
    <div class="mb5">
      <?= Config::get('meta.name'); ?>
      &copy; <?= date('Y'); ?> 
      <span class="mb-none">— <?= Translate::get('community'); ?></span>
    </div>
    <a rel="nofollow noopener" class="icon" title="DISCORD" href="https://discord.gg/dw47aNx5nU"><i class="bi bi-discord text-2xl"></i></a>
    <a rel="nofollow noopener" class="icon" title="Vkontakte" href="https://vk.com/agouti">VK</a>
    <a rel="nofollow noopener" class="icon" title="GitHub" href="https://github.com/agoutiDev/agouti"><i class="bi bi-github text-2xl"></i></a>
  </div>
  <div class="flex right">
    <ul class="mb-none">
      <li><a href="<?= getUrlByName('blogs.all'); ?>"><?= Translate::get('blogs'); ?></a></li>
      <li><a href="<?= getUrlByName('topics.all'); ?>"><?= Translate::get('topics'); ?></a></li>
      <li><a href="<?= getUrlByName('web.all'); ?>"><?= Translate::get('catalog'); ?></a></li>
    </ul>
    <ul class="mb-none">
      <li><a href="<?= getUrlByName('users.all'); ?>"><?= Translate::get('users'); ?></a></li>
      <li><a href="<?= getUrlByName('answers'); ?>"><?= Translate::get('answers'); ?></a></li>
      <li><a href="<?= getUrlByName('comments'); ?>"><?= Translate::get('comments'); ?></a></li>
    </ul>
    <ul>
      <li><a href="/info/<?= Config::get('facets.page-one'); ?>"><?= Translate::get('info'); ?></a></li>
      <li><a href="/info/<?= Config::get('facets.page-two'); ?>"><?= Translate::get('privacy'); ?></a></li>
    </ul>
  </div>
</footer>

<?= Tpl::import('/scripts', ['uid' => $user['id']]); ?>