<div class="clear"></div>
<footer>
  <div class="wrap white">
    <div class="text-info no-mob">
      <h4 class="p-head two"><?= lang('Info'); ?></h4>
      <a class="footer white size-13" title="<?= lang('Spaces'); ?>" href="/spaces"><?= lang('Spaces'); ?></a>
      <a class="footer white size-13" title="<?= lang('Topics'); ?>" href="/topics"><?= lang('Topics'); ?></a>
      <a class="footer white size-13" title="<?= lang('Users'); ?>" href="/users"><?= lang('Users'); ?></a>
    </div>
    <div class="text-info no-mob">
      <h4 class="p-head three"><?= lang('Other'); ?></h4>
      <a class="footer white size-13" title="<?= lang('All answers'); ?>" href="/answers"><?= lang('Answers-n'); ?></a>
      <a class="footer white size-13" title="<?= lang('All comments'); ?>" href="/comments"><?= lang('Comments-n'); ?></a>
      <a class="footer white size-13" title="<?= lang('All domains'); ?>" href="/web"><?= lang('Domains'); ?></a>
    </div>
    <div class="text-info">
      <h4 class="p-head one"><?= lang('Help'); ?></h4>
      <a class="footer white size-13" title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
      <a class="footer white size-13 no-mob" title="<?= lang('Privacy'); ?>" href="/info/privacy"><?= lang('Privacy'); ?></a>
    </div>
    <div class="text-oth ots">
      <h4 class="p-head-n"><?= lang('Social networks'); ?></h4>
      <a rel="nofollow noopener" class="discord" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <svg class="md-icon discord">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <circle cx="9" cy="12" r="1" />
          <circle cx="15" cy="12" r="1" />
          <path d="M7.5 7.5c3.5-1 5.5-1 9 0" />
          <path d="M7 16.5c3.5 1 6.5 1 10 0" />
          <path d="M15.5 17c0 1 1.5 3 2 3c1.5 0 2.833 -1.667 3.5 -3c.667 -1.667 .5 -5.833 -1.5 -11.5c-1.457 -1.015 -3 -1.34 -4.5 -1.5l-1 2.5" />
          <path d="M8.5 17c0 1 -1.356 3 -1.832 3c-1.429 0 -2.698 -1.667 -3.333 -3c-.635 -1.667 -.476 -5.833 1.428 -11.5c1.388 -1.015 2.782 -1.34 4.237 -1.5l1 2.5" />
        </svg></a>
      <a rel="nofollow noopener" class="github white" title="GitHub" href="https://github.com/LoriUp/loriup">
        <i class="light-icon-brand-github size-21"></i>
      </a>

      <div class="size-13">
        Loriup &copy; <?= date('Y'); ?>
        <span class="no-mob">— <?= lang('community'); ?></span>
      </div>
    </div>
  </div>
</footer>

<script async src="/assets/js/common.js"></script>
<script src="/assets/js/layer/layer.js"></script>

<?php if ($uid['msg']) { ?>
  <?php foreach ($uid['msg'] as $message) { ?>
    <script nonce="<?= $_SERVER['nonce']; ?>">
      layer.msg('<?= $message[0]; ?>', {
        icon: <?= $message[1]; ?>,
        time: 3000,
        skin: 'layui-layer-molv',
      });
    </script>
  <?php } ?>
<?php } ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<a id="scroll_top" class="red" title="Наверх">&#8593;</a>
</body>

</html>