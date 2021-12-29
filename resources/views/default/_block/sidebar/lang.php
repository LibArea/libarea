<aside class="col-span-3 relative br-rd5 no-mob">
  <?php if (!empty($lang)) { ?>
    <div class="bg-white p15 br-box-gray">
      <?= $lang; ?>...
    </div>
  <?php } ?>
  <div class="last-comm br-rd5 br-box-gray p15 mt15 bg-white">
    <div class="mb-3 text-sm">
      <a class="inline gray-600" title="<?= Translate::get('help'); ?>" href="/info/<?= Config::get('facets.page-one'); ?>">
        <?= Translate::get('help'); ?>
      </a>
      <a rel="nofollow noopener" class="gray-400 right" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="bi bi-discord middle text-xl"></i>
      </a>
      <a rel="nofollow noopener" class="gray-400 right ml15 mr15" title="Vkontakte" href="https://vk.com/agouti">
        VK
      </a>
      <a rel="nofollow noopener" class="gray-400 right" title="GitHub" href="https://github.com/agoutiDev/agouti">
        <i class="bi bi-github middle text-xl"></i>
      </a> 
    </div>
  </div>
</aside>