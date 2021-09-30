<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <h1><?= lang('Invite'); ?></h1>
    <form class="" action="/invite" method="post">
      <?php csrf_field(); ?>
      <div class="boxline">
        <label class="form-label" for="email"><?= lang('Code'); ?></label>
        <input class="form-input" type="text" name="invite" id="invite">
      </div>
      <div class="boxline">
        <button type="submit" class="button block br-rd-5 white"><?= lang('Sign in'); ?></button>
        <span class="ml15 size-14"><a href="<?= getUrlByName('recover'); ?>">
            <?= lang('Forgot your password'); ?>?</a>
        </span>
      </div>
    </form>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('Someone invited you from the site')]); ?>