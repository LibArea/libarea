<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15 mini">
    <h1><?= lang($data['sheet']); ?></h1>
    <form class="form mini" action="<?= getUrlByName('register'); ?>/add" method="post">
      <?php csrf_field(); ?>

      <?= includeTemplate('/_block/form/field-input', ['data' => [
        ['title' => lang('nickname'), 'type' => 'text', 'name' => 'login', 'value' => '', 'min' => 3, 'max' => 32, 'help' => '>= 3 ' . lang('characters') . ' (' . lang('english') . ')'],
        ['title' => lang('E-mail'), 'type' => 'email', 'name' => 'email', 'value' => '', 'help' => lang('work e-mail (to activate your account)')],
        ['title' => lang('password'), 'type' => 'password', 'name' => 'password', 'value' => '', 'min' => 8, 'max' => 32],
        ['title' => lang('repeat the password'), 'type' => 'password', 'name' => 'password_confirm', 'value' => '', 'min' => 8, 'max' => 32],
      ]]); ?>

      <?= includeTemplate('/_block/captcha'); ?>

      <div class="boxline">
        <button type="submit" class="button br-rd-5 white">
          <?= lang('sign up'); ?>
        </button>
        <span class="mr5 ml15 size-14"><a href="<?= getUrlByName('login'); ?>"><?= lang('sign in'); ?></a></span>
      </div>
    </form>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-security')]); ?>