<aside class="col-span-3">
  <div class="bg-white br-rd-5 border-box-1 menu-info p15">
    <a title="<?= lang('info'); ?>" class="size-15 block mb5 gray<?php if ($sheet == 'info') { ?> red<?php } ?>" href="<?= getUrlByName('info'); ?>">
      <i class="bi bi-info-square middle mr5"></i>
      <span class="middle"><?= lang('info'); ?></span>
    </a>
    <a title="<?= lang('privacy'); ?>" class="size-15 block gray<?php if ($sheet == 'privacy') { ?> red" <?php } ?>" href="<?= getUrlByName('info.privacy'); ?>">
      <i class="bi bi-shield-x middle mr5"></i>
      <span class="middle"><?= lang('privacy'); ?></span>
    </a>
  </div>
</aside>