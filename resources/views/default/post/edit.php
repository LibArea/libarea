<main class="bg-white col-span-12 mb-col-12 pt5 pr15 pb20 pl15 edit-post">
  <?= breadcrumb('/', lang('home'), getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]), $data['post']['post_title'], lang('edit post')); ?>

  <form action="/post/edit" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <?= includeTemplate('/_block/form/field-input', ['data' => [
      ['title' => lang('heading'), 'type' => 'text', 'name' => 'post_title', 'value' => $data['post']['post_title'], 'min' => 6, 'max' => 250, 'help' => '6 - 250 ' . lang('characters'), 'red' => 'red'],
    ]]); ?>

    <?= includeTemplate('/_block/form/select-topic-post', ['uid' => $uid, 'data' => $data, 'action' => 'edit', 'title' => lang('topics'), 'help' => lang('necessarily'), 'red' => 'red']); ?>

    <?php if ($data['post']['post_url']) { ?>
      <div class="mb20">
        <label class="block" for="post_title">URL:</label>
        <a target="_blank" rel="noreferrer ugc" href="<?= $data['post']['post_url']; ?>" class="size-14"><?= $data['post']['post_url']; ?></a>
      </div>
    <?php } ?>

    <?php if ($data['post']['post_content_img']) { ?>
      <div class="img-post-edit">
        <?= post_img($data['post']['post_content_img'], $data['post']['post_title'], 'img-post', 'cover'); ?>
        <input type="hidden" name="content_img" value="<?= $data['post']['post_content_img']; ?>">
        <a class="img-remove size-14" href="/post/img/<?= $data['post']['post_id']; ?>/remove">
          <?= lang('remove'); ?>
        </a>
      </div>
    <?php } ?>

    <?php if ($data['post']['post_thumb_img']) { ?>
      <?= post_img($data['post']['post_thumb_img'], $data['post']['post_title'], 'thumb right', 'thumbnails'); ?>
    <?php } ?>

    <div class="mb20 post">
      <div class="input-images"></div>
    </div>

    <?= includeTemplate('/_block/editor/post-editor', ['post_id' => $data['post']['post_id'], 'type' => 'post', 'conten' => $data['post']['post_content']]); ?>

    <?php if ($data['post']['post_draft'] == 1) { ?>
      <?= includeTemplate('/_block/form/field-radio', ['data' => [
        ['title' => lang('is this a draft?'), 'name' => 'post_draft', 'checked' => $data['post']['post_draft']],
      ]]); ?>
    <?php } ?>

    <?php if ($uid['user_trust_level'] > 0) { ?>
      <?= includeTemplate('/_block/form/select-content-tl', ['uid' => $uid, 'data' => $data['post']['post_tl']]); ?>
      <?= includeTemplate('/_block/form/field-radio', ['data' => [
        ['title' => lang('format Q&A?'), 'name' => 'post_type', 'checked' => $data['post']['post_type']],
        ['title' => lang('to close?'), 'name' => 'closed', 'checked' => $data['post']['post_closed']],
      ]]); ?>
    <?php } ?>

    <?= includeTemplate('/_block/form/field-radio', ['data' => [
      ['title' => lang('is this a translation?'), 'name' => 'translation', 'checked' => $data['post']['post_translation']],
    ]]); ?>

    <?php if ($uid['user_trust_level'] > 2) { ?>
      <?= includeTemplate('/_block/form/field-radio', ['data' => [
        ['title' => lang('raise?'), 'name' => 'top', 'checked' => $data['post']['post_top']],
      ]]); ?>
    <?php } ?>

    <?php if ($uid['user_trust_level'] > 4) { ?>
      <?= includeTemplate('/_block/form/select-content', ['type' => 'user', 'data' => $data, 'action' => 'edit', 'title' => lang('author')]); ?>
    <?php } ?>


    <?= includeTemplate('/_block/form/select-content', ['type' => 'post', 'data' => $data, 'action' => 'edit', 'title' => lang('related')]); ?>

    <div class="mb20">
      <?php if ($data['post']['post_draft'] == 1) { ?>
        <input type="hidden" name="draft" id="draft" value="1">
      <?php } ?>
      <input type="hidden" name="post_id" id="post_id" value="<?= $data['post']['post_id']; ?>">
      <input type="submit" class="button white br-rd5" name="submit" value="<?= lang('edit'); ?>" />
    </div>
  </form>
</main>