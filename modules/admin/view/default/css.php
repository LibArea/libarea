<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="bg-white p15 br-box-gray justify-between">
  <h4 class="mt5 mb5"><?= Translate::get('topics'); ?>:</h4>
  <a href="#" class="tag">.tag</a>
  <a href="#" class="tags-xs">.tags-xs</a>

  <h4 class="mt15"><?= Translate::get('color'); ?>:</h4>
  <div class="mb10 hidden">
    <?php $i = 0;
    foreach ($data['bg'] as $bg) { ?>
      <?php if ($i % 10 == 0) echo "</div><div class=\"mb30 hidden\">"; ?>
      <div class="br-box-gray relative br-rd3 w160 h80 center inline <?= $bg; ?> pb0 mb15">
        <div class="mt15 w-100 bottom0 pt5 pb5 absolute bg-stone-500 text-sm white">
          <?= $bg; ?>
        </div>
      </div>
    <?php $i += 1;
    } ?>
  </div>

  <p>
    <?= Translate::get('bg.info'); ?>:
    <i class="bg-amber-100">.bg-res-400</i> / <i class="bg-amber-100">.res-400</i>.
  </p>

</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>