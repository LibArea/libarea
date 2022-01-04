<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray pt10 pr15 pb5 pl15">
  <h1 class="mt0 mb10 text-2xl center font-normal"><?= Translate::get($data['sheet']); ?></h1>
  <form class="form max-w300" action="<?= getUrlByName('recover'); ?>/send" method="post">
    <?php csrf_field(); ?>

    <?= import('/_block/form/field-input', [
      'uid'  => $uid,
      'data' => [
        [
          'title' => Translate::get('E-mail'),
          'type' => 'email',
          'name' => 'email',
          'value' => ''
        ],
      ]
    ]); ?>

    <?= import('/_block/captcha', ['uid'  => $uid]); ?>

    <div class="mb20">
      <?= sumbit(Translate::get('reset')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign in'); ?></a></span>
    </div>
  </form>
  <div class="pt20 mb5 gray-600"><?= Translate::get('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-600"><?= Translate::get('info-recover'); ?></div>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_url'); ?>">
</main>