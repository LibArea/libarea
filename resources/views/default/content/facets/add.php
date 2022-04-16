<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/facet.forma'));
?>

<main class="col-two">
  <div class="box">
    <h1 class="text-xl"><?= __('add'); ?> (<?= __($data['type']); ?>)</h1>

    <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_blog')) : ?>
      <form class="max-w780" action="<?= getUrlByName('content.create', ['type' => $data['type']]); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= $form->build_form(); ?>

        <?= $form->sumbit(__('add')); ?>
      </form>
    <?php else : ?>
      <?= __('limit.add.content.no'); ?>
    <?php endif; ?>
  </div>
</main>