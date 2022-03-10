<?php $post   = $data['post']; ?>
<main class="col-span-10 mb-col-12">
  <div class="box-white">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500">
      <?= sprintf(Translate::get('edit.option'), Translate::get('page')); ?>
    </span>
    
    <form action="<?= getUrlByName('post.edit.pr'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

    <fieldset>
      <label for="post_title"><?= Translate::get('heading'); ?></label>
      <input minlength="6" maxlength="250" value="<?= $post['post_title']; ?>" type="text" required="" name="post_title">
      <div class="help">6 - 250 <?= Translate::get('characters'); ?></div>  
    </fieldset>
    
    <fieldset>
      <label for="post_slug"><?= Translate::get('Slug (URL)'); ?></label>
      <input minlength="3" maxlength="32" value="<?= $post['post_slug']; ?>" type="text" required="" name="post_slug">
      <div class="help">3 - 32 <?= Translate::get('characters'); ?></div>  
    </fieldset>

      <?= Tpl::import('/_block/form/select/blog', [
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => Translate::get('blogs'),
      ]); ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= Tpl::import('/_block/form/select/section', [
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'section',
          'title'         => Translate::get('section'),
          'required'      => false,
          'maximum'       => 1,
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php } ?>

      <?= Tpl::import('/_block/editor/editor', ['height'  => '300px', 'content' => $post['post_content'], 'type' => 'page-telo', 'id' => $post['post_id']]); ?>
      

      <?= Tpl::import('/_block/form/select/user', [
        'uid'           => $user,
        'user'          => $data['user'],
        'action'        => 'user',
        'type'          => 'user',
        'title'         => Translate::get('author'),
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <div class="mb20">
        <?php if ($post['post_draft'] == 1) { ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php } ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>