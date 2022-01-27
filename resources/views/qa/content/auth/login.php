<div class="col-span-2 mb-none"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray p15 hidden">
  <h1 class="center"><?= Translate::get('authorization'); ?></h1>
  <form class="max-w300" action="<?= getUrlByName('login'); ?>" method="post">
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

    <fieldset>
      <label for="password" class="block mb5"><?= Translate::get('password'); ?></label>
      <input type="password" name="password" class="password w-100 h40 pl5">
      <span class="showPassword absolute gray-400 right5 bottom5 text-xl"><i class="bi bi-eye"></i></span>
    </fieldset>

    <fieldset class="flex items-center">
      <input type="checkbox" id="rememberme" name="rememberme" value="1">
      <label for="rememberme" class="mb0 gray-600 ml5"><?= Translate::get('remember me'); ?></label>
    </fieldset>
 
    <p>
      <?= sumbit(Translate::get('sign.in')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <span class="mr5 ml5 text-sm"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign up'); ?></a></span>
      <?php } ?>
      <span class="mr5 ml5 text-sm"><a href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot your password'); ?>?</a></span>
    </p>
  </form>
  <?php if (Config::get('general.invite') == 1) { ?>
    <?= Translate::get('no-invate-txt'); ?>
  <?php } ?>
  <p><?= Translate::get('login-use-condition'); ?>.</p>
  <p><?= Translate::get('info-login'); ?></p>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_path'); ?>">
</main>