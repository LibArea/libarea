<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/facet.forma'));
?>

<main class="col-span-12">
  <div class="box-white">

    <h1 class="text-xl"><?= Translate::get('add'); ?> (<?= Translate::get($data['type']); ?>)</h1>

    <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_blog')) { ?>
      <form class="max-w780" action="<?= getUrlByName('content.create', ['type' => $data['type']); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= $form->build_form(); ?>
        
        <?= $form->sumbit(Translate::get('add')); ?>
      </form>  
    <?php } else { ?>
      <?= Translate::get('limit.add.content.no'); ?>
    <?php } ?>
  </div>  
</main>