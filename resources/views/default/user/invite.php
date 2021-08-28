<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <h1><?= lang('Invite'); ?></h1>
        <div class="box wide">
          <form class="" action="/invite" method="post">
            <?php csrf_field(); ?>
            <div class="boxline">
              <label class="form-label" for="email"><?= lang('Code'); ?></label>
              <input class="form-input" type="text" name="invite" id="invite">
            </div>
            <div class="row">
              <div class="boxline">
                <button type="submit" class="button"><?= lang('Sign in'); ?></button>
                <span class="ml15 size-13"><a href="/recover"><?= lang('Forgot your password'); ?>?</a></span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
  <aside>
    <div class="white-box">
      <div class="p15">
        <?= lang('Someone invited you from the site'); ?>...
      </div>
    </div>
  </aside>
</div>