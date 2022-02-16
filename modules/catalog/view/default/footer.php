<footer class="box-shadow-top bg-gray-100">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('web.home.title'); ?></span>
</footer>

<a class="up_down_btn none" title="<?= Translate::get('up'); ?>">&uarr;</a>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>
<script src="/assets/js/notiflix/notiflix-aio-3.2.2.min.js"></script>
<script src="/assets/js/common.js"></script>
<?php if (UserData::checkActiveUser()) { ?><script src="/assets/js/app.js"></script><?php } ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
<?php if (!UserData::checkActiveUser()) { ?>
  document.querySelectorAll(".click-no-auth")
    .forEach(el => el.addEventListener("click", function(e) {
      Notiflix.Report.info(
        '<?= Translate::get('you need to log in'); ?>',
        '<?= Translate::get('info-login'); ?>',
        '<?= Translate::get('well'); ?>',
      );
    }));
<?php } ?>
<?php if ($msg = getMsg()) { ?>
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