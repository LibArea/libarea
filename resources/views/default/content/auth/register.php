<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray hidden p15">
  <h1 class="mt0 mb10 text-2xl center font-normal"><?= Translate::get($data['sheet']); ?></h1>
  <form class="form max-w300 mb20 block" action="<?= getUrlByName('register'); ?>/add" method="post">
    <?php csrf_field(); ?>

    <?= import('/_block/form/field-input', [
      'uid'  => $uid,
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

    <?= import('/_block/captcha', ['uid'  => $uid]); ?>

    <div class="mb20">
      <?= sumbit(Translate::get('sign up')); ?>
      <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign in'); ?></a></span>
    </div>
  </form>
  <div class="pt20 mb5 gray-600"><?= Translate::get('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-600"><?= Translate::get('info-security'); ?></div>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_url'); ?>">
</main>