<div class="col-span-2 mb-none"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray p15 hidden">
  <h1 class="mt0 mb10 text-2xl center font-normal"><?= Translate::get('authorization'); ?></h1>
  <form class="max-w300 mb20 block" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= Tpl::import('/_block/form/field-input', [
      'data' =>  [
        [
          'title' => Translate::get('E-mail'),
          'type' => 'email',
          'name' => 'email',
        ]
      ]
    ]); ?>

    <div class="inputs relative mb20">
      <label for="password" class="block mb5"><?= Translate::get('password'); ?></label>
      <input type="password" name="password" class="password w-100 h40 pl5">
      <span class="showPassword absolute gray-400 right5 bottom5 text-xl"><i class="bi bi-eye"></i></span>
    </div>

    <div class="mb20">
      <input type="checkbox" class="left mr5" id="rememberme" name="rememberme" value="1">
      <label class="form-check-label" for="rememberme"><?= Translate::get('remember me'); ?></label>
    </div>

    <div class="mb20">
      <?= sumbit(Translate::get('sign in')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <span class="mr5 ml5 text-sm"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml5 text-sm"><a href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot your password'); ?>?</a></span>
    </div>
  </form>
  <?php if (Config::get('general.invite') == 1) { ?>
    <?= Translate::get('no-invate-txt'); ?>
  <?php } ?>
  <div class="pt20 mb5 gray-600"><?= Translate::get('login-use-condition'); ?>.</div>
  <div class="pt20 mb20 gray-600"><?= Translate::get('info-login'); ?></div>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_url'); ?>">
</main>