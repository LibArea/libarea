<main class="col-span-10 mb-col-12  bg-white br-rd5 br-box-gray p15">
  <h1 class="center"><?= Translate::get('password recovery'); ?></h1>
  <div class="box wide">
    <form action="<?= getUrlByName('recover'); ?>/send/pass" method="post">
      <?php csrf_field(); ?>
      <fieldset>
        <label for="password">
          <?= Translate::get('new password'); ?>
        </label>
        <input type="password" name="password" id="password">
      </fieldset>
      <p>
        <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
        <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
        <?= sumbit(Translate::get('reset')); ?>
        <?php if (Config::get('general.invite') == false) { ?>
          <span class="mr5 ml5 text-sm"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('registration'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml5 text-sm"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign.in'); ?></a></span>
      </p>
    </form>
  </div>
</main>