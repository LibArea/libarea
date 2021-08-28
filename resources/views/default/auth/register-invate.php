<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <h1><?= lang('Registration by invite'); ?></h1>
        <div class="form mini">
          <form class="" action="/register/add" method="post">
            <?php csrf_field(); ?>
            <div class="boxline">
              <label class="form-label" for="login"><?= lang('Nickname'); ?></label>
              <input type="text" class="form-input" name="login" id="login">
            </div>
            <div class="boxline">
              <label class="form-label" for="email">Email</label>
              <input type="text" class="form-input" name="email" id="email" value="<?= $data['invate']['invitation_email']; ?>">
            </div>
            <div class="boxline">
              <label class="form-label" for="password"><?= lang('Password'); ?></label>
              <input type="password" class="form-input" name="password" id="password">
            </div>
            <div class="boxline">
              <label class="form-label" for="password_confirm"><?= lang('Repeat the password'); ?></label>
              <input type="password" class="form-input" name="password_confirm" id="password_confirm">
            </div>
            <div class="boxline">
              <div class="boxline">
                <input type="hidden" name="invitation_code" id="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
                <input type="hidden" name="invitation_id" id="invitation_id" value="<?= $data['invate']['uid']; ?>">
                <button type="submit" class="button"><?= lang('Sign up'); ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>