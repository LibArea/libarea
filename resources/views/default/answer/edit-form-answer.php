<div class="wrap">
  <main class="w-100 white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), null, null, lang('Edit answer')); ?>
    <a class="mb5 block" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>"><?= $data['post']['post_title']; ?></a>
    <div class="answer_addentry">
      <?= returnBlock('editor/answer-edit-editor', ['post_id' => $data['post']['post_id'], 'answer_id' => $data['answer_id'], 'content' => $data['answer_content'], 'type' => 'answer', 'width100' => 'yes']); ?>
    </div>
  </main>
</div>