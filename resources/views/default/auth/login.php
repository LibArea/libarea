<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 border-box-1 pt10 pr15 pb5 pl15">
  <h1 class="center pb15 size-24"><?= lang('authorization'); ?></h1>
  <form class="max-w300 mb20 block" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= includeTemplate('/_block/form/field-input', ['data' =>  [
      [
        'title' => lang('E-mail'),
        'type' => 'email',
        'name' => 'email',
        'value' => ''
      ],
      [
        'title' => lang('password'),
        'type' => 'password',
        'name' => 'password',
        'value' => ''
      ],
    ]]); ?>

    <div class="mb20">
      <input type="checkbox" class="left mr5" id="rememberme" name="rememberme" value="1">
      <label class="form-check-label" for="rememberme"><?= lang('remember me'); ?></label>
    </div>
    <div class="mb20">
      <button type="submit" class="button br-rd5 white">
        <?= lang('sign in'); ?>
      </button>
      <?php if (Config::get('general.invite') == 0) { ?>
        <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('register'); ?>"><?= lang('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('recover'); ?>"><?= lang('forgot your password'); ?>?</a></span>
    </div>
  </form>
  <?php if (Config::get('general.invite') == 1) { ?>
    <?= lang('no-invate-txt'); ?>
  <?php } ?>
  <div class="pt20 mb5 gray-light"><?= lang('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-light"><?= lang('info-login'); ?></div>
    <img class="right" alt="<?= Config::get('meta.name'); ?>" src="/assets/images/agouti_footer.gif">
</main>
 