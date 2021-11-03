<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-grey pt10 pr15 pb5 pl15">
  <h1 class="mt0 mb10 size-24 center font-normal"><?= Translate::get($data['sheet']); ?></h1>
  <form class="form max-w300" action="<?= getUrlByName('recover'); ?>/send" method="post">
    <?php csrf_field(); ?>

    <?= includeTemplate('/_block/form/field-input', ['data' => [
      [
        'title' => Translate::get('E-mail'),
        'type' => 'email',
        'name' => 'email',
        'value' => ''
      ],
    ]]); ?>

    <?= includeTemplate('/_block/captcha'); ?>

    <div class="mb20">
      <button type="submit" class="button br-rd5 white">
        <?= Translate::get('reset'); ?>
      </button>
      <?php if (Config::get('general.invite')) { ?>
        <span class="mr5 ml15 size-14"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml15 size-14"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign in'); ?></a></span>
    </div>
  </form>
  <div class="pt20 mb5 gray-light"><?= Translate::get('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-light"><?= Translate::get('info-recover'); ?></div>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="/assets/images/agouti_footer.gif">
</main>