<div class="white-box menu-info sticky p15">
  <a title="<?= lang('Info'); ?>" class="size-15 block gray<?php if ($uid['uri'] == '/info') { ?> red<?php } ?>" href="/info">
    <i class="icon-record-outline middle"></i>
    <span class="middle"><?= lang('Info'); ?></span>
  </a>
  <a title="<?= lang('Privacy'); ?>" class="size-15 block gray<?php if ($uid['uri'] == '/info/privacy') { ?> red" <?php } ?>" href="/info/privacy">
    <i class="icon-record-outline middle"></i>
    <span class="middle"><?= lang('Privacy'); ?></span>
  </a>
</div>