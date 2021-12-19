<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'user_id' => $uid['user_id'],
    'add'     => true,
    'pages'   => false
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['words'])) { ?>
    <?php foreach ($data['words'] as $key => $word) { ?>
      <div class="content-telo">
        <?= $word['stop_word']; ?> |
        <a data-id="<?= $word['stop_id']; ?>" data-type="word" class="type-ban lowercase size-13">
          <?= Translate::get('remove'); ?>
        </a>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('stop words no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
</main>