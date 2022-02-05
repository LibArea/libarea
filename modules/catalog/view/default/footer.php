<footer class="box-shadow-top">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('site.directory'); ?></span>

   <div class="gray-400 right mb-none"><?= Translate::get('directory.info'); ?></div>
 
</footer>

<a class="up_down_btn none" title="<?= Translate::get('up'); ?>">&uarr;</a>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script src="/assets/js/common.js"></script>
<?php if (UserData::checkActiveUser()) { ?><script src="/assets/js/app.js"></script><?php } ?>

</body>

</html>