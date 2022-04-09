<?php $post   = $data['post']; ?>
<main class="w-100">
  <div class="bg-white items-center justify-between p15 mb15">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red">
      <?= sprintf(__('edit.option'), __('page')); ?>
    </span>
    
    <form action="<?= getUrlByName('content.change', ['type' => 'page']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

    <fieldset>
      <label for="post_title"><?= __('heading'); ?></label>
      <input minlength="6" maxlength="250" value="<?= $post['post_title']; ?>" type="text" required="" name="post_title">
      <div class="help">6 - 250 <?= __('characters'); ?></div>  
    </fieldset>
    
    <fieldset>
      <label for="post_slug"><?= __('Slug (URL)'); ?></label>
      <input minlength="3" maxlength="32" value="<?= $post['post_slug']; ?>" type="text" required="" name="post_slug">
      <div class="help">3 - 32 <?= __('characters'); ?></div>  
    </fieldset>

      <?= Tpl::insert('/_block/form/select/blog', [
        'user'         => $user,
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => __('blogs'),
      ]); ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= Tpl::insert('/_block/form/select/section', [
          'user'           => $user,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'section',
          'title'         => __('section'),
          'required'      => false,
          'maximum'       => 1,
          'help'          => __('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php } ?>

      <?= Tpl::insert('/_block/editor/editor', ['height'  => '300px', 'content' => $post['post_content'], 'type' => 'page-telo', 'id' => $post['post_id']]); ?>

      <?= Tpl::insert('/_block/form/select/user', [
        'uid'           => $user,
        'user'          => $data['user'],
        'action'        => 'user',
        'type'          => 'user',
        'title'         => __('author'),
        'help'          => __('necessarily'),
      ]); ?>

      <div class="mb20">
        <?php if ($post['post_draft'] == 1) { ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php } ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
        <?= Html::sumbit(__('edit')); ?>
      </div>
    </form>
  </div>
</main>