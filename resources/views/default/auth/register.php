<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 border-box-1 pt10 pr15 pb5 pl15">
  <h1 class="center pb15 size-24"><?= lang($data['sheet']); ?></h1>
  <form class="form max-w300 mb20 block" action="<?= getUrlByName('register'); ?>/add" method="post">
    <?php csrf_field(); ?>

    <?= includeTemplate('/_block/form/field-input', ['data' => [
      [
        'title' => lang('nickname'),
        'type' => 'text',
        'name' => 'login',
        'value' => '',
        'min' => 3,
        'max' => 32,
        'help' => '>= 3 ' . lang('characters') . ' (' . lang('english') . ')'
      ],
      [
        'title' => lang('E-mail'),
        'type' => 'email',
        'name' => 'email',
        'value' => '',
        'help' => lang('work e-mail (to activate your account)')
      ],
      [
        'title' => lang('password'),
        'type' => 'password',
        'name' => 'password',
        'value' => '',
        'min' => 8,
        'max' => 32
      ],
      [
        'title' => lang('repeat the password'),
        'type' => 'password',
        'name' => 'password_confirm',
        'value' => '',
        'min' => 8,
        'max' => 32
      ],
    ]]); ?>

    <?= includeTemplate('/_block/captcha'); ?>

    <div class="mb20">
      <button type="submit" class="button br-rd5 white">
        <?= lang('sign up'); ?>
      </button>
      <span class="mr5 ml15 size-14"><a href="<?= getUrlByName('login'); ?>"><?= lang('sign in'); ?></a></span>
    </div>
  </form>
  <div class="pt20 mb5 gray-light"><?= lang('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-light"><?= lang('info-security'); ?></div>
    <img class="right" alt="<?= Config::get('meta.name'); ?>" src="/assets/images/agouti_footer.gif">
</main>