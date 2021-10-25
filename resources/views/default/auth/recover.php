<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 border-box-1 pt10 pr15 pb5 pl15">
  <h1 class="center pb15 size-24"><?= lang($data['sheet']); ?></h1>
  <form class="form max-w300" action="<?= getUrlByName('recover'); ?>/send" method="post">
    <?php csrf_field(); ?>

    <?= includeTemplate('/_block/form/field-input', ['data' => [
      [
        'title' => lang('E-mail'),
        'type' => 'email',
        'name' => 'email',
        'value' => ''
      ],
    ]]); ?>

    <?= includeTemplate('/_block/captcha'); ?>

    <div class="mb20">
      <button type="submit" class="button br-rd5 white">
        <?= lang('reset'); ?>
      </button>
      <?php if (Config::get('general.invite')) { ?>
        <span class="mr5 ml15 size-14"><a href="<?= getUrlByName('register'); ?>"><?= lang('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml15 size-14"><a href="<?= getUrlByName('login'); ?>"><?= lang('sign in'); ?></a></span>
    </div>
  </form>
  <div class="pt20 mb5 gray-light"><?= lang('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-light"><?= lang('info-recover'); ?></div>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="/assets/images/agouti_footer.gif">
</main>