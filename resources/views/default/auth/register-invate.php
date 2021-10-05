<main class="col-span-12 mb-col-12 bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
  <h1><?= lang('registration by invite'); ?></h1>
  <div class="form mini">
    <form class="" action="<?= getUrlByName('register'); ?>/add" method="post">
      <?php csrf_field(); ?>

      <?= includeTemplate('/_block/form/field-input', ['data' => [
        ['title' => lang('nickname'), 'type' => 'text', 'name' => 'login', 'value' => '', 'min' => 3, 'max' => 10, 'help' => '3 - 10 ' . lang('characters')],
        ['title' => lang('E-mail'), 'type' => 'email', 'name' => 'email', 'value' => $data['invate']['invitation_email']],
        ['title' => lang('rassword'), 'type' => 'password', 'name' => 'password', 'value' => '', 'min' => 8, 'max' => 32, 'help' => '8 - 32 ' . lang('characters')],
        ['title' => lang('repeat the password'), 'type' => 'password', 'name' => 'password_confirm', 'value' => ''],
      ]]); ?>

      <div class="boxline">
        <input type="hidden" name="invitation_code" id="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
        <input type="hidden" name="invitation_id" id="invitation_id" value="<?= $data['invate']['uid']; ?>">
        <button type="submit" class="button block br-rd-5 white"><?= lang('sign up'); ?></button>
      </div>
    </form>
  </div>
</main>