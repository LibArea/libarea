<aside class="col-span-3 relative br-rd5 mb-none">
  <?php if (!empty($lang)) { ?>
    <div class="box-white">
      <?= $lang; ?>...
    </div>
  <?php } ?>
  <div class="box-flex-white">
    <div class="text-sm">
      <a class="inline gray-400" title="<?= Translate::get('help'); ?>" href="/info/<?= Config::get('facets.page-one'); ?>">
        <?= Translate::get('help'); ?>
      </a>
     </div>
    <div class="text-sm">
      <a rel="nofollow noopener" class="gray-400 right" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="bi bi-discord middle text-xl"></i>
      </a>
      <a rel="nofollow noopener" class="gray-400 text-xl right ml15 mr15" title="Vkontakte" href="https://vk.com/agouti">
        VK
      </a>
      <a rel="nofollow noopener" class="gray-400 right" title="GitHub" href="https://github.com/agoutiDev/agouti">
        <i class="bi bi-github middle text-xl"></i>
      </a> 
    </div>
  </div>
</aside>