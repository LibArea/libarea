<main>
  <div class="box">
    <ul class="nav">
      <li><a href="<?= url('polls'); ?>"><?= __('app.all'); ?></a></li>
      <li class="active"><?= __('app.edit_poll'); ?></li>
    </ul>
    <form action="<?= url('edit.poll', method: 'post'); ?>" id="myform" method="post">
      <?= $container->csrf()->field(); ?>
      <fieldset class="max-w-md">
        <input type="text" name="title" value="<?= htmlEncode($data['question']['poll_title']); ?>">
      </fieldset>
      <fieldset class="max-w-md">
        <?php foreach ($data['answers'] as $value) : ?>
          <div class="flex gap mb10">
            <input type="text" id="in<?= $value['answer_id']; ?>" name="<?= $value['answer_id']; ?>" value="<?= htmlEncode($value['answer_title']); ?>">
            <sup class="del-voting-option gray-600" data-id="<?= $value['answer_id']; ?>">x</sup>
          </div>
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
  </div>
</main>
<aside>
  <div class="box shadow text-sm">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.add_poll'); ?>
  </div>
</aside>