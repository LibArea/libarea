<div class="wrap">
  <main class="w-100">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/post/' . $data['post']['post_id'] . '/' . $data['post']['post_slug'], $data['post']['post_title'], lang('Edit post')); ?>

      <div class="box edit-post">
        <form action="/post/edit" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="form">
            <div class="boxline">
              <label class="form-label" for="post_title"><?= lang('Heading'); ?><sup class="red">*</sup></label>
              <input class="form-input" minlength="6" maxlength="250" type="text" value="<?= htmlspecialchars($data['post']['post_title']); ?>" name="post_title" />
              <div class="box_h gray">6 - 250 <?= lang('characters'); ?></div>
            </div>
            <?php if ($uid['user_trust_level'] == 5) { ?>
              <div class="boxline">
                <label class="form-label" for="post_title">URL</label>
                <?php if ($data['post']['post_url']) { ?>
                  <?= $data['post']['post_url']; ?>
                <?php } else { ?>
                  ...
                <?php } ?>
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
          </div>
          <div class="boxline">
            <?php includeTemplate('/_block/post-editor', ['post' => $data['post']]); ?>
          </div>
          <div class="form">
            <?php if ($data['post']['post_draft'] == 1) { ?>
              <div class="boxline">
                <label class="form-label" for="post_content"><?= lang('Draft'); ?>?</label>
                <input type="radio" name="post_draft" <?php if ($data['post']['post_draft'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                <input type="radio" name="post_draft" <?php if ($data['post']['post_draft'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
              </div>
            <?php } ?>
            <?php if ($uid['user_trust_level'] > 0) { ?>
              <div class="boxline">
                <label class="form-label" for="post_content"><?= lang('For'); ?> TL</label>
                <select class="form-input" name="post_tl">
                  <?php for ($i = 0; $i <= $uid['user_trust_level']; $i++) {  ?>
                    <option <?php if ($data['post']['post_tl'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="boxline">
                <label class="form-label" for="post_content"><?= lang('Format'); ?></label>
                <input type="radio" name="post_type" <?php if ($data['post']['post_type'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('Discussion'); ?>
                <input type="radio" name="post_type" <?php if ($data['post']['post_type'] == 1) { ?>checked<?php } ?> value="1"> Q&A
              </div>
              <div class="boxline">
                <label class="form-label" for="post_content"><?= lang('To close'); ?>?</label>
                <input type="radio" name="closed" <?php if ($data['post']['post_closed'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                <input type="radio" name="closed" <?php if ($data['post']['post_closed'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
              </div>
            <?php } ?>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Translation'); ?>?</label>
              <input type="radio" name="translation" <?php if ($data['post']['post_translation'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
              <input type="radio" name="translation" <?php if ($data['post']['post_translation'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
            </div>
            <?php if ($uid['user_trust_level'] > 2) { ?>
              <div class="boxline">
                <label class="form-label" for="post_content"><?= lang('Raise'); ?>?</label>
                <input type="radio" name="top" <?php if ($data['post']['post_top'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                <input type="radio" name="top" <?php if ($data['post']['post_top'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
              </div>
            <?php } ?>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Space'); ?></label>
              <select class="form-input" name="space_id">
                <?php foreach ($data['space'] as $sp) { ?>
                  <option <?php if ($data['post']['space_id'] == $sp['space_id']) { ?> selected<?php } ?> value="<?= $sp['space_id']; ?>"> <?= $sp['space_name']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <?php if ($data['post']['post_draft'] == 1) { ?>
              <input type="hidden" name="draft" id="draft" value="1">
            <?php } ?>
            <?php if ($uid['user_trust_level'] > 4) { ?>
              <div class="boxline">
                <label class="form-label" for="post_content"><?= lang('Author'); ?></label>
                <?php includeTemplate('/_block/select-content', ['content' => 'user', 'data' => $data, 'action' => 'edit']); ?>
              </div>
            <?php } ?>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Topics'); ?></label>
              <?php includeTemplate('/_block/select-content', ['content' => 'topic', 'data' => $data, 'action' => 'edit']); ?>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Related'); ?></label>
              <?php includeTemplate('/_block/select-content', ['content' => 'post', 'data' => $data, 'action' => 'edit']); ?>
            </div>
          </div>
          <div class="boxline">
            <input type="hidden" name="post_id" id="post_id" value="<?= $data['post']['post_id']; ?>">
            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
          </div>
        </form>
      </div>
    </div>
  </main>
</div>