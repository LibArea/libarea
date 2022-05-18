<main>
  <div class="box">
    <h2><?= __('app.add_post'); ?></h2>

    <form class="max-w780" id="addPost" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= component('add-post', ['data' => $data]); ?>
    </form>

  </div>
</main>
<aside>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.add_post'); ?>
  </div>
</aside>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('content.create', ['type' => 'post']),
    'redirect'  => '/',
    'success'   => __('msg.successfully'),
    'id'        => 'form#addPost'
  ]
); ?>