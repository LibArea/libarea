<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => []
  ]
); ?>

<div class="bg-white br-box-gray p15">
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
</div>
</main>