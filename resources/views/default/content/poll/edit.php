<main>
  <ul class="nav">
    <li><a href="<?= url('polls'); ?>"><?= __('app.all'); ?></a></li>
    <li class="active"><?= __('app.edit_poll'); ?></li>
  </ul>
  <form action="<?= url('content.change', ['type' => 'poll']); ?>" id="myform" method="post">
    <?= csrf_field() ?>
    <fieldset class="max-w780">
      <input type="text" name="title" value="<?= $data['question']['poll_title']; ?>">
    </fieldset>
    <fieldset class="max-w300">
      <?php foreach ($data['answers'] as $value) : ?>
        <p><input type="text" id="in<?= $value['answer_id']; ?>" name="<?= $value['answer_id']; ?>" value="<?= $value['answer_title']; ?>"></p>
      <?php endforeach; ?>
    </fieldset>
    <fieldset>
      <input type="checkbox" name="closed" <?php if ($data['question']['poll_is_closed'] == 1) : ?>checked <?php endif; ?>> <?= __('app.poll_closed'); ?>
    </fieldset>
    <div class="flex gap items-center mt20">
      <input type="hidden" name="id" value="<?= $data['question']['poll_id']; ?>">
      <?= Html::sumbit(__('app.edit_poll')); ?>
    </div>
  </form>
</main>
<aside>
  <div class="box box-shadow-all text-sm">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.add_poll'); ?>
  </div>
</aside>