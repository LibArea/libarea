<div class="wrap">
  <main class="admin white-box pt5 pr15 pb5 pl15">
    <a class="right" title="<?= lang('Add'); ?>" href="/admin/words/add">
      <i class="icon-plus middle"></i>
    </a>
    <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Stop words')); ?>

    <div class="words">
      <?php if (!empty($data['words'])) { ?>
        <?php foreach ($data['words'] as $key => $word) { ?>
          <div class="content-telo">
            <?= $word['stop_word']; ?> |
            <a data-id="<?= $word['stop_id']; ?>" data-type="word" class="type-ban lowercase size-13">
              <?= lang('Remove'); ?>
            </a>
          </div>
        <?php } ?>
      <?php } else { ?>
        <?= no_content('Stop words no'); ?>
      <?php } ?>
    </div>
  </main>
</div>