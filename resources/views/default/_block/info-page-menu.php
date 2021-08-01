<div class="white-box menu-info sticky">
  <div class="inner-padding big">
    <a title="<?= lang('Info'); ?>" class="gray<?php if ($uid['uri'] == '/info') { ?> active<?php } ?>" href="/info">
      <i class="light-icon-point middle"></i>
      <span class="middle"><?= lang('Info'); ?></span>
    </a>
    <a title="<?= lang('Privacy'); ?>" class="gray<?php if ($uid['uri'] == '/info/privacy') { ?> active" <?php } ?>" href="/info/privacy">
      <i class="light-icon-point middle"></i>
      <span class="middle"><?= lang('Privacy'); ?></span>
    </a>
  </div>
</div>