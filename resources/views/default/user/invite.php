<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd5 br-box-grey pt5 pr15 pb5 pl15">
    <h1 class="mt0 mb10 size-24 font-normal"><?= Translate::get('invite'); ?></h1>
    <form class="" action="/invite" method="post">
      <?php csrf_field(); ?>
      <div class="mb20">
        <label class="block" for="email"><?= Translate::get('code'); ?></label>
        <input class="w-100 h30" type="text" name="invite" id="invite">
      </div>
      <div class="mb20">
        <button type="submit" class="button br-rd5 white"><?= Translate::get('sign in'); ?></button>
        <span class="ml15 size-14"><a href="<?= getUrlByName('recover'); ?>">
            <?= Translate::get('forgot your password'); ?>?</a>
        </span>
      </div>
    </form>
    <?php if (Config::get('general.invite') == 1) { ?>
      <?= Translate::get('no-invate-txt'); ?>
    <?php } ?>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('someone invited you from the site')]); ?>