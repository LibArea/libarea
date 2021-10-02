<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <a class="right" title="<?= lang('add'); ?>" href="/admin/words/add">
    <i class="bi bi-plus-lg middle"></i>
  </a>
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('stop words')); ?>

  <div class="words mt20">
    <?php if (!empty($data['words'])) { ?>
      <?php foreach ($data['words'] as $key => $word) { ?>
        <div class="content-telo">
          <?= $word['stop_word']; ?> |
          <a data-id="<?= $word['stop_id']; ?>" data-type="word" class="type-ban lowercase size-13">
            <?= lang('remove'); ?>
          </a>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'stop words no']); ?>
    <?php } ?>
  </div>
</main>