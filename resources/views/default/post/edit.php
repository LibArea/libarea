<div class="wrap">
  <main class="w-100 white-box pt5 pr15 pb5 pl15 edit-post">
    <?= breadcrumb('/', lang('Home'), post_url($data['post']), $data['post']['post_title'], lang('Edit post')); ?>

    <form action="/post/edit" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?php field_input(array(
        array('title' => lang('Heading'), 'type' => 'text', 'name' => 'post_title', 'value' => $data['post']['post_title'], 'min' => 6, 'max' => 250, 'help' => '6 - 250 ' . lang('characters')),
        )); ?>

      <?php select_space(['spaces' => $data['space'], 'space_id' => $data['post']['space_id']]); ?>

      <?php if ($data['post']['post_url']) { ?>
        <div class="boxline">
          <label class="form-label" for="post_title">URL:</label>
          <a target="_blank" rel="noreferrer ugc" href="<?= $data['post']['post_url']; ?>" class="size-13"><?= $data['post']['post_url']; ?></a>
        </div>
      <?php } ?>

      <?php if ($data['post']['post_content_img']) { ?>
        <div class="img-post-edit">
          <?= post_img($data['post']['post_content_img'], $data['post']['post_title'], 'img-post', 'cover'); ?>
          <input type="hidden" name="content_img" value="<?= $data['post']['post_content_img']; ?>">
          <a class="img-remove size-13" href="/post/img/<?= $data['post']['post_id']; ?>/remove">
            <?= lang('Remove'); ?>
          </a>
        </div>
      <?php } ?>

      <?php if ($data['post']['post_thumb_img']) { ?>
        <?= post_img($data['post']['post_thumb_img'], $data['post']['post_title'], 'thumb right', 'thumbnails'); ?>
      <?php } ?>

      <div class="boxline post">
        <div class="boxline">
          <div class="input-images"></div>
        </div>
      </div>

      <?php editor(['post' => $data['post']]); ?>

      <?php if ($data['post']['post_draft'] == 1) { ?>
        <?php field_radio(array(
          array('title' => lang('Is this a draft?'), 'name' => 'post_draft', 'checked' => $data['post']['post_draft']),
        )); ?>
      <?php } ?>

      <?php if ($uid['user_trust_level'] > 0) { ?>
        <?php select_tl(['uid' => $uid, 'data' => $data['post']['post_tl']]); ?>
        <?php field_radio(array(
          array('title' => lang('Format Q&A?'), 'name' => 'post_type', 'checked' => $data['post']['post_type']),
          array('title' => lang('To close?'), 'name' => 'closed', 'checked' => $data['post']['post_closed']),
        )); ?>
      <?php } ?>

      <?php field_radio(array(
        array('title' => lang('Is this a translation?'), 'name' => 'translation', 'checked' => $data['post']['post_translation']),
      )); ?>

      <?php if ($uid['user_trust_level'] > 2) { ?>
        <?php field_radio(array(
          array('title' => lang('Raise?'), 'name' => 'top', 'checked' => $data['post']['post_top']),
        )); ?>
      <?php } ?>

      <?php if ($uid['user_trust_level'] > 4) { ?>
        <?php select(['type' => 'user', 'data' => $data, 'action' => 'edit', 'title' => lang('Author')]); ?>
      <?php } ?>

      <?php select(['type' => 'topic', 'data' => $data, 'action' => 'edit', 'title' => lang('Topics')]); ?>
      <?php select(['type' => 'post', 'data' => $data, 'action' => 'edit', 'title' => lang('Related')]); ?>

      <div class="boxline">
        <?php if ($data['post']['post_draft'] == 1) { ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php } ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post']['post_id']; ?>">
        <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
      </div>
    </form>
  </main>
</div>