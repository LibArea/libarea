<main class="col-span-12 mb-col-12 bg-white br-rd5 border-box-1 pt5 pr15 pb5 pl15">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    '/info',
    Translate::get('info'),
    Translate::get('access restricted')
  ); ?>

  <h1><?= Translate::get('access restricted'); ?></h1>
  <p><i><?= Translate::get('the profile is being checked'); ?>...</i></p>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>