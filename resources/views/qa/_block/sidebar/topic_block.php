<div class="box bg-violet">
  <h3 class="uppercase-box"><?= __($lang); ?></h3>
  <?php foreach ($data as $sub) : ?>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= url('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['facet_title']; ?>">
      <?= Html::image($sub['facet_img'], $sub['facet_title'], 'img-base', 'logo', 'max'); ?>
      <?= $sub['facet_title']; ?>
    </a>
  <?php endforeach; ?>
</div>