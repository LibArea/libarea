<main class="col-two">
  <div class="box">
    <h1 class="mt0 mb10 text-2xl font-normal"><?= __('invite'); ?></h1>
    <form class="" action="/invite" method="post">
      <?php csrf_field(); ?>
      <fieldset>
        <label for="invite"><?= __('code'); ?></label>
        <input type="text" name="invite" id="invite">
      </fieldset>
      <fieldset>
        <?= Html::sumbit(__('sign.in')); ?>
        <span class="ml15 text-sm"><a href="<?= getUrlByName('recover'); ?>">
            <?= __('forgot.password'); ?>?</a>
        </span>
      </fieldset>
    </form>
    <?php if (Config::get('general.invite') == true) { ?>
      <?= __('invate.text'); ?>
    <?php } ?>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('invited.you'); ?>
  </div>
</aside>