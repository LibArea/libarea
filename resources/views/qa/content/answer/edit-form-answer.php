<main class="col-span-12 mb-col-12 edit-post">
  <div class="bg-white items-center justify-between br-box-gray br-rd5 p15 mb15">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= Translate::get('edit answer'); ?></span>

  <a class="mb5 block" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>">
    <?= $data['post']['post_title']; ?>
  </a>

  <form action="<?= getUrlByName('answer.edit.pr'); ?>" accept-charset="UTF-8" method="post">
    <?= csrf_field() ?>

    <?= Tpl::import('/_block/editor/editor', ['height'  => '300px', 'content' => $data['content']]); ?>

    <div class="pt5 clear">
      <input type="hidden" name="post_id" value="<?= $data['post']['post_id']; ?>">
      <input type="hidden" name="answer_id" value="<?= $data['answer_id']; ?>">
      <?= sumbit(Translate::get('edit')); ?>
    </div>
  </form>
</main>