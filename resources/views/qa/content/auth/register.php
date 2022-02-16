<div class="col-span-2 mb-none"></div>
<main class="col-span-8 mb-col-12 box-white">
  <h1 class="center"><?= Translate::get($data['sheet']); ?></h1>
  <form class="form max-w300 mb20 block" action="<?= getUrlByName('register.add'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= Tpl::import('/_block/form/field-input', [
      'data' => [
        [
          'title' => Translate::get('nickname'),
          'type' => 'text',
          'name' => 'login',
          'min' => 3,
          'max' => 32,
          'help' => '>= 3 ' . Translate::get('characters') . ' (' . Translate::get('english') . ')'
        ],
        [
          'title' => Translate::get('E-mail'),
          'type' => 'email',
          'name' => 'email',
          'help' => Translate::get('work email')
        ],
        [
          'title' => Translate::get('password'),
          'type' => 'password',
          'name' => 'password',
          'min' => 8,
          'max' => 32
        ],
        [
          'title' => Translate::get('repeat the password'),
          'type' => 'password',
          'name' => 'password_confirm',
          'min' => 8,
          'max' => 32
        ],
      ]
    ]); ?>

    <?= Tpl::import('/_block/captcha'); ?>

    <p>
      <?= sumbit(Translate::get('sign up')); ?>
      <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign.in'); ?></a></span>
    </p>
  </form>
  <p><?= Translate::get('login-use-condition'); ?>.</p>
  <p><?= Translate::get('info-security'); ?></p>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_path'); ?>">
</main>