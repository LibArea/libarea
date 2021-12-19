<main class="col-span-12 mb-col-12 bg-white br-rd5 br-box-gray p15">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    '/info',
    Translate::get('info'),
    Translate::get('access restricted')
  ); ?>

  <h1 class="mt0 mb10 size-24 font-normal"><?= Translate::get('access restricted'); ?></h1>
  <div class="italic"><?= Translate::get('the profile is being checked'); ?>...</div>
</main>
<?= import('/_block/wide-footer'); ?>