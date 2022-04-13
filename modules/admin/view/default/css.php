<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>
<div class="box">
  <h4><?= __('topics'); ?>:</h4>
  <a href="#" class="tags">.tags</a>
  <a href="#" class="tags-xs">.tags-xs</a>

  <h4 class="mt15"><?= __('buttons'); ?>:</h4>
  <p><i class="btn btn-outline-primary">btn btn-outline-primary</i></p>
  <p><i class="btn btn-small btn-outline-primary">btn btn-small btn-outline-primary</i></p>
  <p><i class="btn btn-primary">btn btn-primary</i></p>
  <p><i class="btn btn-small btn-primary">btn btn-small btn-primary</i></p>

  <p>
    <?= __('being.developed'); ?>...
  </p>

</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>