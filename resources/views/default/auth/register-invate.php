<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <h1><?= lang('Registration by invite'); ?></h1>
    <div class="form mini">
      <form class="" action="/register/add" method="post">
        <?php csrf_field(); ?>

        <?php field_input(array(
          array('title' => lang('Nickname'), 'type' => 'text', 'name' => 'login', 'value' => '', 'min' => 3, 'max' => 10, 'help' => '3 - 10 ' . lang('characters')),
          array('title' => lang('Email'), 'type' => 'email', 'name' => 'email', 'value' => $data['invate']['invitation_email']),
          array('title' => lang('Password'), 'type' => 'password', 'name' => 'password', 'value' => '', 'min' => 8, 'max' => 32, 'help' => '8 - 32 ' . lang('characters')),
          array('title' => lang('Repeat the password'), 'type' => 'password', 'name' => 'password_confirm', 'value' => ''),
        )); ?>

        <div class="boxline">
          <input type="hidden" name="invitation_code" id="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
          <input type="hidden" name="invitation_id" id="invitation_id" value="<?= $data['invate']['uid']; ?>">
          <button type="submit" class="button"><?= lang('Sign up'); ?></button>
        </div>
      </form>
    </div>
  </main>
</div>