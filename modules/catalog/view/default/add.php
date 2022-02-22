<?php
echo includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$breadcrumb = (new Breadcrumbs())->base(getUrlByName('web'), Translate::get('home'));
$breadcrumb->addCrumb(Translate::get('site.add'), 'red');
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/catalog.site'));
?>

<div class="grid grid-cols-12 gap-4">
  <main>
    <?= $breadcrumb->render('breadcrumbs'); ?>

    <form id="addUrl" class="max-w640">
      <?= csrf_field() ?>

      <?= includeTemplate('/view/default/_block/category', ['data' => ['topic' => false], 'action' => 'add']); ?>

      <?= $form->build_form(); ?>

      <?= $form->sumbit(Translate::get('add')); ?>
    </form>
  </main>
  <aside>
    <div class="box-white box-shadow-all text-sm">
      <h3 class="uppercase-box"><?= Translate::get('help'); ?></h3>
      <?= Translate::get('add.site.help'); ?>
      <div>
  </aside>
</div>

<?php $url = $user['trust_level'] == UserData::REGISTERED_ADMIN ? 'web' : 'web.user.sites'; ?>
<?= includeTemplate('/view/default/_block/ajax', ['url' => 'web.create', 'redirect' => $url, 'id' => 'form#addUrl']); ?>

<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>