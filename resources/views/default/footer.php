</div>
<div class="wrap grid grid-cols-12 gap-4 pr10 mt15 pl10 h20 justify-between">
    &nbsp;
  <a id="scroll_top" class="red fixed size-24" title="<?= lang('Up'); ?>">
    &#8593;
  </a>
</div>
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

</body>
</html>