<a class="up_down_btn none" title="<?= Translate::get('up'); ?>">&uarr;</a>

<script src="/assets/js/notiflix/notiflix-aio-3.2.2.min.js"></script>
<script src="/assets/js/tippy/popper.min.js"></script>
<script src="/assets/js/tippy/tippy-bundle.umd.min.js"></script>
<script src="/assets/js/common.js"></script>

<?php if ($user['id']) { ?><script src="/assets/js/app.js"></script><?php } ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if ($user['id'] == 0) { ?>
    document.querySelectorAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
         Notiflix.Notify.success('<?= Translate::get('you need to log in'); ?>');
      }));
  <?php } ?>
  <?php if ($msg = getMsg()) { ?>
    <?php foreach ($msg as $message) { ?>
      Notiflix.Notify.info('<?= $message[0]; ?>');
    <?php } ?>
  <?php } ?>
</script>

</body>

</html>