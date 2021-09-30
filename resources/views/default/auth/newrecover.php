<main class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
  <h1><?= lang('password recovery'); ?></h1>
  <div class="box wide">
    <form class="" action="<?= getUrlByName('recover'); ?>/send/pass" method="post">
      <?php csrf_field(); ?>
      <div class="boxline">
        <label class="form-label" for="password">
          <?= lang('new password'); ?>
        </label>
        <input class="form-input" type="password" name="password" id="password">
      </div>
      <div class="boxline">
        <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
        <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
        <button type="submit" class="button block br-rd-5 white">
          <?= lang('reset'); ?>
        </button>
        <?php if (!Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
          <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('register'); ?>"><?= lang('sign up'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('login'); ?>"><?= lang('sign in'); ?></a></span>
      </div>
    </form>
  </div>
</main>