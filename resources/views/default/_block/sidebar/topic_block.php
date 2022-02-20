<div class="box-white text-sm">
  <h3 class="uppercase-box"><?= Translate::get($lang); ?></h3>
  <ul>
  <?php foreach ($data as $sub) { ?>
    <li class="mb10">
      <a class="gray-600" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['facet_title']; ?>">
        <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'img-base'); ?>
        <?= $sub['facet_title']; ?>
      </a>
    </li>
  <?php } ?>
  </ul>
</div>