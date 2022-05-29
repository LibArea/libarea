<?php $url = url('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>
<main class="w-100">
  <div class="box justify-between">

    <a href="/"><?= __('app.home'); ?></a> /
    <span class="red"><?= __('app.edit_answer'); ?></span>

    <a class="mb5 block" href="<?= $url; ?>">
      <?= $data['post']['post_title']; ?>
    </a>

    <form action="<?= url('content.change', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>

      <?= insert('/_block/form/editor', ['height'  => '300px', 'content' => $data['content'], 'type' => 'answer', 'id' => $data['post']['post_id']]); ?>

      <div class="pt5 clear">
        <input type="hidden" name="answer_id" value="<?= $data['answer_id']; ?>">
        <?= Html::sumbit(__('app.edit')); ?>
      </div>
    </form>
</main>