<main class="col-span-12 mb-col-12 bg-white br-rd5 border-box-1 pt5 pr15 pb5 pl15">
  <?= breadcrumb('/', lang('home'), null, null, lang('edit answer')); ?>
  <a class="mb5 block" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>"><?= $data['post']['post_title']; ?></a>
  <div class="answer_addentry">
    <?= includeTemplate('/_block/editor/answer-edit-editor', ['post_id' => $data['post']['post_id'], 'answer_id' => $data['answer_id'], 'content' => $data['answer_content'], 'type' => 'answer', 'width100' => 'yes']); ?>
  </div>
</main>