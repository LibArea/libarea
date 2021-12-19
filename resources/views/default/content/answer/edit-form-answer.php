<main class="col-span-12 mb-col-12 bg-white br-rd5 br-box-gray pt5 pr15 pb5 pl15">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    null,
    null,
    Translate::get('edit answer')
  ); ?>

  <a class="mb5 block" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>">
    <?= $data['post']['post_title']; ?>
  </a>

  <form action="<?= getUrlByName('answer.edit.pr'); ?>" accept-charset="UTF-8" method="post">
    <?= csrf_field() ?>

    <?= import('/_block/editor/editor', [
      'height'    => '350px',
      'preview'   => 'vertical',
      'lang'      => $uid['user_lang'],
      'content'   => $data['content'],
    ]); ?>

    <div class="pt5 clear">
      <input type="hidden" name="post_id" value="<?= $data['post']['post_id']; ?>">
      <input type="hidden" name="answer_id" value="<?= $data['answer_id']; ?>">
      <?= sumbit(Translate::get('edit')); ?>
    </div>
  </form>

</main>