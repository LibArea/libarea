<main class="col-span-12 mb-col-12 bg-white br-rd5 br-box-gray p15">
  <h1><?= Translate::get('registration by invite'); ?></h1>
    <form class="max-w300" action="<?= getUrlByName('register'); ?>/add" method="post">
      <?php csrf_field(); ?>

      <?= Tpl::import('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('nickname'),
            'type' => 'text',
            'name' => 'login',
            'min' => 3,
            'max' => 10,
            'help' => '3 - 10 ' . Translate::get('characters')
          ],
          [
            'title' => Translate::get('E-mail'),
            'type' => 'email',
            'name' => 'email',
            'value' => $data['invate']['invitation_email']
          ],
          [
            'title' => Translate::get('password'),
            'type' => 'password',
            'name' => 'password',
            'min' => 8,
            'max' => 32,
            'help' => '8 - 32 ' . Translate::get('characters')
          ],
          [
            'title' => Translate::get('repeat the password'),
            'type' => 'password',
            'name' => 'password_confirm',
          ],
        ]
      ]); ?>

      <p>
        <input type="hidden" name="invitation_code" id="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
        <input type="hidden" name="invitation_id" id="invitation_id" value="<?= $data['invate']['uid']; ?>">
        <?= sumbit(Translate::get('sign up')); ?>
      </p>
    </form>
</main>