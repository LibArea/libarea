<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/facet.forma'));
?>
<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<main class="col-span-10 mb-col-12 edit-post">
  <div class="box-white">

    <h1 class="text-xl"><?= Translate::get('add'); ?> (<?= Translate::get($data['type']); ?>)</h1>

    <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_blog')) { ?>
      <form class="max-w780" action="<?= getUrlByName('facet.create'); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= $form->build_form(); ?>
        
        <input type="hidden" name="facet_type" value="<?= $data['type']; ?>">
        <?= $form->sumbit(Translate::get('add')); ?>
      </form>  
    <?php } else { ?>
      <?= Translate::get('limit-add-content-no'); ?>
    <?php } ?>
  </div>  
</main>