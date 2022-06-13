<?= component('add-js-css'); 
 $post = $data['post']; ?>
<main>
  <div class="box">
    <h2><?= __('app.edit_' . $post['post_type']); ?></h2>

    <form class="max-w780" action="<?= url('content.change', ['type' => $post['post_type']]); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= component('edit-post', ['post' => $post, 'data' => $data]); ?>
    </form>

  </div>
</main>
<aside>
  <div class="box bg-beige">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.edit_' . $post['post_type']); ?>
  </div>
</aside>