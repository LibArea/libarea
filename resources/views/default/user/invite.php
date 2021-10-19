<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <h1><?= lang('invite'); ?></h1>
    <form class="" action="/invite" method="post">
      <?php csrf_field(); ?>
      <div class="boxline">
        <label class="block" for="email"><?= lang('code'); ?></label>
        <input class="form-input" type="text" name="invite" id="invite">
      </div>
      <div class="boxline">
        <button type="submit" class="button br-rd-5 white"><?= lang('sign in'); ?></button>
        <span class="ml15 size-14"><a href="<?= getUrlByName('recover'); ?>">
            <?= lang('forgot your password'); ?>?</a>
        </span>
      </div>
    </form>
    <?php if (Config::get('general.invite') == 1) { ?>
      <?= lang('no-invate-txt'); ?>
    <?php } ?>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('someone invited you from the site')]); ?>