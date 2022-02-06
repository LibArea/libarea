<footer class="box-shadow-top">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('site.directory'); ?></span>
</footer>

<a class="up_down_btn none" title="<?= Translate::get('up'); ?>">&uarr;</a>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>
<script src="/assets/js/notiflix/notiflix-aio-3.2.2.min.js"></script>
<script src="/assets/js/common.js"></script>

<?php if (UserData::checkActiveUser()) { ?>
  <script src="/assets/js/app.js"></script>
<?php } else { ?>
  <script nonce="<?= $_SERVER['nonce']; ?>">
    document.querySelectorAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
        Notiflix.Report.info(
          '<?= Translate::get('you need to log in'); ?>',
          '<?= Translate::get('info-login'); ?>',
          '<?= Translate::get('well'); ?>',
        );
      }));
  </script>
<?php } ?>

</body>

</html>