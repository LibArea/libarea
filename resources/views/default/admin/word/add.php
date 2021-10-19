<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), '/admin/words', lang('stop words'), lang('add a stop word')); ?>

  <div class="box badges">
    <form action="/admin/word/add" method="post">
      <?= csrf_field() ?>
      <div class="boxline max-w780">
        <label class="block" or="post_title"><?= lang('stop word'); ?></label>
        <input type="text" class="form-input" name="word">
      </div>
      <input type="submit" class="button block br-rd-5 white" name="submit" value="<?= lang('add'); ?>" />
    </form>
  </div>
</main>