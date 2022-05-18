<?php $url = url('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>
<main class="col-two">
  <div class="bg-white items-center justify-between br-gray br-rd5 p15 mb15">

    <a href="/"><?= __('app.home'); ?></a> /
    <span class="red"><?= __('app.edit_answer'); ?></span>

    <a class="mb5 block" href="<?= $url; ?>">
      <?= $data['post']['post_title']; ?>
    </a>

    <form id="editAnswer" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>

      <?= insert('/_block/form/editor', ['height'  => '300px', 'content' => $data['content'], 'type' => 'answer', 'id' => $data['post']['post_id']]); ?>

      <div class="pt5 clear">
        <input type="hidden" name="answer_id" value="<?= $data['answer_id']; ?>">
        <?= Html::sumbit(__('app.edit')); ?>
      </div>
    </form>
</main>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('content.change', ['type' => 'answer']),
    'redirect'  => $url . '#answer_' . $data['answer_id'],
    'success'   => __('msg.successfully'),
    'id'        => 'form#editAnswer'
  ]
); ?>