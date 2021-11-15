<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/menu-admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <a class="right mr15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('admin.words.add'); ?>">
    <i class="bi bi-plus-lg middle"></i>
  </a>

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('stop words')
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