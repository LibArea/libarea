<?php
$form = new Forms();
$form->html_form(UserData::getUserTl(), config('form/facet.forma'));
?>

<main class="col-two">
  <div class="box">
    <h1 class="text-xl"><?= __('app.add'); ?> (<?= __('app.' . $data['type']); ?>)</h1>

    <?php if (UserData::getRegType(config('trust-levels.tl_add_blog'))) : ?>
      <form class="max-w780" action="<?= url('content.create', ['type' => $data['type']]); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= $form->build_form(); ?>

        <?= $form->sumbit(__('app.add')); ?>
      </form>
    <?php else : ?>
      <?= __('app.limit_content'); ?>
    <?php endif; ?>
  </div>
</main>