<footer class="box-shadow-top">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('site.directory'); ?></span>

  <div data-template="catalog" class="tippy right gray-400" aria-expanded="false">
    <i class="bi bi-info-square"></i>
  </div>
  <div id="catalog" style="display: none;"><?= Translate::get('directory.info'); ?></div>
</footer>

<a class="up_down_btn none" title="<?= Translate::get('up'); ?>">&uarr;</a>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script src="/assets/js/tippy/popper.min.js"></script>
<script src="/assets/js/tippy/tippy-bundle.umd.min.js"></script>
<script src="/assets/js/common.js"></script>
<?php if (UserData::checkActiveUser()) { ?><script src="/assets/js/app.js"></script><?php } ?>

</body>

</html>