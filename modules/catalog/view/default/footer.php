<footer>
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('site.directory'); ?></span>
</footer>

<script src="/assets/js/notiflix/notiflix-aio-3.2.2.min.js"></script>
<script src="/assets/js/tippy/popper.min.js"></script>
<script src="/assets/js/tippy/tippy-bundle.umd.min.js"></script>
<script src="/assets/js/admin.js"></script>
<script src="/assets/js/app.js"></script>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if ($user['id'] == 0) { ?>
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
    <?php foreach ($msg as $message) { ?>
      Notiflix.Notify.info('<?= $message[0]; ?>');
    <?php } ?>
  <?php } ?>
</script>

</body>

</html>