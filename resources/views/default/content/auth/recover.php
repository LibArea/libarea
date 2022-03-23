<main class="max-w780 mr-auto box-white">
  <h1 class="center"><?= Translate::get($data['sheet']); ?></h1>
  <form class="form max-w300" action="<?= getUrlByName('recover.send'); ?>" method="post">
    <?php csrf_field(); ?>

    <fieldset>
      <label for="post_title"><?= Translate::get('E-mail'); ?></label>
      <input type="email" required="" name="email">
    </fieldset>

    <?= Tpl::import('/_block/captcha'); ?>

    <fieldset>
      <?= Html::sumbit(Translate::get('reset')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('registration'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign.in'); ?></a></span>
    </fieldset>
  </form>
  <p><?= Translate::get('login.use.condition'); ?>.</p>
  <p><?= Translate::get('info-recover'); ?></p>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_path'); ?>">
</main>