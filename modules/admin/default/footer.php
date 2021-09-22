<div class="clear"></div>

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

</body>
<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>
<script src="/assets/js/admin.js"></script>

<script nonce="<?= $_SERVER['nonce']; ?>">
$(document).on('click', '.tips', function () {
  let title = $(this).data('id');
  layer.tips (title, '.tips', {
    tips: [1, '#339900']
  });
});
</script>
</html>