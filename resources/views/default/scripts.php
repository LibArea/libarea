<a class="up_down_btn none mb-none" title="<?= Translate::get('up'); ?>">&uarr;</a>

<script src="/assets/js/common.js"></script>
<script src="/assets/js/notiflix/notiflix-aio-3.2.2.min.js"></script>
<?php if ($uid) { ?><script src="/assets/js/app.js"></script><?php } ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if (!$uid) { ?>
    document.querySelectorAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
        Notiflix.Report.info(
          '<?= Translate::get('need.to.login'); ?>',
          '<?= Translate::get('info-login'); ?>',
          '<?= Translate::get('well'); ?>',
        );
      }));
  <?php } ?>
  <?php if ($msg = Html::getMsg()) { ?>
    <?php foreach ($msg as $message) {  ?>
      <?php if ($message[1] == 'error') { ?>
        Notiflix.Notify.failure('<?= Translate::get($message[0]); ?>');
      <?php } else { ?>
        Notiflix.Notify.info('<?= Translate::get($message[0]); ?>');
      <?php } ?>
    <?php } ?>
  <?php } ?>
</script>

</body>

</html>