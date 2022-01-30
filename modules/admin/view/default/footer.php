</container>
<footer class="w-100 mt15 p15 bg-zinc-800 clear">
  <div class="col-span-12 max-width grid grid-cols-12 mr-auto">
    <div class="text-sm mt5 mb5 gray-400 col-span-3">
      <?= Config::get('meta.name'); ?>
      &copy; <?= date('Y'); ?> — <?= Translate::get('community'); ?>
    </div>
  </div>
</footer>

<script src="/assets/js/tippy/popper.min.js"></script>
<script src="/assets/js/tippy/tippy-bundle.umd.min.js"></script>
<script src="/assets/js/admin.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/notiflix/notiflix-aio-3.2.2.min.js"></script>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if ($msg = getMsg()) { ?>
    <?php foreach ($msg as $message) { ?>
      Notiflix.Notify.info('<?= $message[0]; ?>');
    <?php } ?>
  <?php } ?>
</script>

</body>

</html>