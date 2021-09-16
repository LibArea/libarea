<footer class="footer bg-gray-800 clear mt15 pt10 pb15 mr20 ml20">
  <div class="wrap white">
    <div class="text-info width-20 left no-mob">
      <h4 class="p-head relative two"><?= lang('Info'); ?></h4>
      <a class="footer white size-13" title="<?= lang('Spaces'); ?>" href="<?= getUrlByName('spaces'); ?>">
        <?= lang('Spaces'); ?>
      </a>
      <a class="footer white size-13" title="<?= lang('Topics'); ?>" href="<?= getUrlByName('topics'); ?>">
        <?= lang('Topics'); ?>
      </a>
      <a class="footer white size-13" title="<?= lang('Users'); ?>" href="<?= getUrlByName('users'); ?>">
        <?= lang('Users'); ?>
      </a>
    </div>
    <div class="text-info width-20 no-mob">
      <h4 class="p-head relative three"><?= lang('Other'); ?></h4>
      <a class="footer white size-13" title="<?= lang('All answers'); ?>" href="<?= getUrlByName('answers'); ?>">
        <?= lang('Answers-n'); ?>
      </a>
      <a class="footer white size-13" title="<?= lang('All comments'); ?>" href="<?= getUrlByName('comments'); ?>">
        <?= lang('Comments-n'); ?>
      </a>
      <a class="footer white size-13" title="<?= lang('All domains'); ?>" href="<?= getUrlByName('web'); ?>">
        <?= lang('Domains'); ?>
      </a>
    </div>
    <div class="text-info width-20">
      <h4 class="p-head relative one"><?= lang('Help'); ?></h4>
      <a class="footer white size-13" title="<?= lang('Info'); ?>" href="<?= getUrlByName('info'); ?>">
        <?= lang('Info'); ?>
      </a>
      <a class="footer white size-13 no-mob" title="<?= lang('Privacy'); ?>" href="<?= getUrlByName('info.privacy'); ?>">
        <?= lang('Privacy'); ?>
      </a>
    </div>
    <div class="text-info width-40 left ots">
      <h4 class="mt5 mb5"><?= lang('Social networks'); ?></h4>
      <a rel="nofollow noopener" class="white" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="icon-wechat size-21 mr5"></i>
      </a>
      <a rel="nofollow noopener" class="white" title="GitHub" href="https://github.com/agoutiDev/agouti">
        <i class="icon-github-circled size-21"></i>
      </a>
      <div class="size-13 mt5">
        Agouti &copy; <?= date('Y'); ?>
        <span class="no-mob">— <?= lang('community'); ?></span>
      </div>
    </div>
  </div>
</footer>

<script async src="/assets/js/common.js"></script>
<script src="/assets/js/layer/layer.js"></script>

<?php if ($msg = getMsg()) { ?>
  <?php foreach ($msg as $message) { ?>
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

<a id="scroll_top" class="red" title="<?= lang('Up'); ?>">&#8593;</a>
</body>

</html>