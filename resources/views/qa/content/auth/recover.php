<div class="col-span-2 mb-none"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray p15 hidden">
  <h1 class="center"><?= Translate::get($data['sheet']); ?></h1>
  <form class="form max-w300" action="<?= getUrlByName('recover'); ?>/send" method="post">
    <?php csrf_field(); ?>

    <?= Tpl::import('/_block/form/field-input', [
      'data' => [
        [
          'title' => Translate::get('E-mail'),
          'type' => 'email',
          'name' => 'email',
          'value' => ''
        ],
      ]
    ]); ?>

    <?= Tpl::import('/_block/captcha'); ?>

    <p>
      <?= sumbit(Translate::get('reset')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml15 text-sm"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign.in'); ?></a></span>
    </p>
  </form>
  <p><?= Translate::get('login-use-condition'); ?>.</p>
  <p><?= Translate::get('info-recover'); ?></p>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_path'); ?>">
</main>