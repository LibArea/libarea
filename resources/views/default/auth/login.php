<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15 mini">
    <h1><?= lang('authorization'); ?></h1>
    <form class="" action="<?= getUrlByName('login'); ?>" method="post">
      <?php csrf_field(); ?>

      <?= includeTemplate('/_block/form/field-input', ['data' =>  [
        ['title' => lang('E-mail'), 'type' => 'email', 'name' => 'email', 'value' => ''],
        ['title' => lang('password'), 'type' => 'password', 'name' => 'password', 'value' => ''],
      ]]); ?>

      <div class="boxline">
        <input type="checkbox" class="left mr5" id="rememberme" name="rememberme" value="1">
        <label class="form-check-label" for="rememberme"><?= lang('remember me'); ?></label>
      </div>
      <div class="boxline">
        <button type="submit" class="button br-rd-5 white">
          <?= lang('sign in'); ?>
        </button>
        <?php if (!Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
          <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('register'); ?>"><?= lang('sign up'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('recover'); ?>"><?= lang('forgot your password'); ?>?</a></span>
      </div>
    </form>

    <?php if (Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
      <?= lang('no-invate-txt'); ?>
    <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-login')]); ?>