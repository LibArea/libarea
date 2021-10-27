<aside class="col-span-3 relative">
  <div class="bg-white br-rd5 border-box-1 menu-info p15">
    <a title="<?= Translate::get('info'); ?>" class="size-15 block mb5 gray<?php if ($sheet == 'info') { ?> red<?php } ?>" href="<?= getUrlByName('info'); ?>">
      <i class="bi bi-info-square middle mr5"></i>
      <span class="middle"><?= Translate::get('info'); ?></span>
    </a>
    <a title="<?= Translate::get('privacy'); ?>" class="size-15 block gray<?php if ($sheet == 'privacy') { ?> red" <?php } ?>" href="<?= getUrlByName('info.privacy'); ?>">
      <i class="bi bi-shield-x middle mr5"></i>
      <span class="middle"><?= Translate::get('privacy'); ?></span>
    </a>
  </div>
</aside>