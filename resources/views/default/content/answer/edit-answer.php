<?= component('add-js-css');
$post = $data['post'];
$url = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
$content = preg_replace('/</', '', $data['answer']['answer_content']); ?>

<main>
  <a href="/"><?= __('app.home'); ?></a> / <span class="gray-600"><?= __('app.edit_answer'); ?>:</span>
  <a class="mb5 block" href="<?= $url; ?>"><?= $post['post_title']; ?></a>

  <form class="max-w780" action="<?= url('content.change', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
    <?= csrf_field() ?>

    <?= insert('/_block/form/editor', ['height'  => '300px', 'content' => $content, 'type' => 'answer', 'id' => $post['post_id']]); ?>

    <?php if (UserData::checkAdmin()) : ?>
      <?= insert('/_block/form/select/user', [
        'user'          => $data['user'],
        'action'        => 'user',
        'type'          => 'user',
        'title'         => __('app.author'),
        'help'          => __('app.necessarily'),
      ]); ?>
    <?php endif; ?>

    <input type="hidden" name="answer_id" value="<?= $data['answer']['answer_id']; ?>">
    <?= Html::sumbit(__('app.edit')); ?>
  </form>
</main>