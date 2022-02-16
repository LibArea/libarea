<?php 
echo includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$breadcrumb = (new Breadcrumbs())->base('/', Translate::get('home'));
$breadcrumb->addCrumb(Translate::get('site.add'), 'red');
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/site.add'));
?>

<main class="col-span-12 mb-col-12">
  <div class="box max-w780">
    <?= $breadcrumb->render('breadcrumbs'); ?>

    <form id="addUrl">
      <?= csrf_field() ?>

      <?= Tpl::insert('/_block/form/select/select', [
        'data'      => ['topic' => false],
        'type'      => 'category',
        'action'    => 'add',
        'title'     => Translate::get('facets'),
        'help'      => Translate::get('necessarily'),
        'red'       => 'red'
      ]); ?>

      <?= $form->build_form(); ?>

      <?= $form->sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>

<?= includeTemplate('/view/default/_block/ajax', ['url' => 'web.create', 'redirect' => 'web', 'id' => 'form#addUrl']); ?>

<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>