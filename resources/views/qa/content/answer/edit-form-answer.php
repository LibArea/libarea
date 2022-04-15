<main class="w-100">
  <div class="box-flex justify-between">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red"><?= __('edit.answer'); ?></span>

  <a class="mb5 block" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>">
    <?= $data['post']['post_title']; ?>
  </a>

  <form action="<?= getUrlByName('content.change', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
    <?= csrf_field() ?>

    <?= Tpl::insert('/_block/editor/editor', ['height'  => '300px', 'content' => $data['content'], 'type' => 'answer', 'id' => $data['post']['post_id']]); ?>

    <div class="pt5 clear">
      <input type="hidden" name="answer_id" value="<?= $data['answer_id']; ?>">
      <?= Html::sumbit(__('edit')); ?>
    </div>
  </form>
</main>