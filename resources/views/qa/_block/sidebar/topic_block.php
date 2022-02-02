<div class="box-white box-white bg-violet-50">
  <h3 class="uppercase-box"><?= Translate::get($lang); ?></h3>
  <?php foreach ($data as $sub) { ?>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['facet_title']; ?>">
      <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w30 h30 mr10 br-box-gray'); ?>
      <?= $sub['facet_title']; ?>
    </a>
  <?php } ?>
</div>