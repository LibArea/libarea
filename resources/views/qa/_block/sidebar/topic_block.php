<div class="bg-white br-rd5 mb15 br-box-gray box-shadow-all p15">
  <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get($lang); ?></h3>
  <?php foreach ($data as $sub) { ?>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['facet_title']; ?>">
      <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w30 h30 mr10 br-box-gray'); ?>
      <?= $sub['facet_title']; ?>
    </a>
  <?php } ?>
</div>