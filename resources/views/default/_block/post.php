<?php if (!empty($data['posts'])) { ?>
  <?php foreach ($data['posts'] as $post) { ?>

    <div class="white-box">
      <div class="pt15 pr15 pb0 pl15 size-13 flex">
        <a class="gray-light" href="/u/<?= $post['user_login']; ?>">
          <?= user_avatar_img($post['user_avatar'], 'small', $post['user_login'], 'ava'); ?>
          <span class="mr5 ml5">
            <?= $post['user_login']; ?>
          </span>
        </a>
        <span class="gray-light lowercase mr5 ml5">
          <?= $post['post_date'] ?>
        </span>
        <?php if ($data['sheet'] == 'subscribed') { ?>
          <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-13 right">
            <span class="mr5 ml5"> &#183; </span> <?= lang('Unsubscribe'); ?>
          </div>
        <?php } ?>
      </div>

      <?php if ($post['post_thumb_img']) { ?>
        <a title="<?= $post['post_title']; ?>" href="<?= post_url($post); ?>">
          <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb no-mob right', 'thumbnails'); ?>
        </a>
      <?php } ?>

      <div class="post-body">
        <a href="<?= post_url($post); ?>">
          <h2 class="title size-24 ml15 mt0 mb0"><?= $post['post_title']; ?>
            <?php if ($post['post_is_deleted'] == 1) { ?>
              <i class="icon-trash red"></i>
            <?php } ?>
            <?php if ($post['post_closed'] == 1) { ?>
              <i class="icon-lock gray"></i>
            <?php } ?>
            <?php if ($post['post_top'] == 1) { ?>
              <i class="icon-pin-outline red"></i>
            <?php } ?>
            <?php if ($post['post_lo'] > 0) { ?>
              <i class="icon-diamond red"></i>
            <?php } ?>
            <?php if ($post['post_type'] == 1) { ?>
              <i class="icon-help green"></i>
            <?php } ?>
            <?php if ($post['post_translation'] == 1) { ?>
              <span class="translation size-13 italic lowercase"><?= lang('Translation'); ?></span>
            <?php } ?>
            <?php if ($post['post_tl'] > 0) { ?>
              <span class="trust-level italic size-13">tl<?= $post['post_tl']; ?></span>
            <?php } ?>
            <?php if ($post['post_merged_id'] > 0) { ?>
              <i class="icon-link-ext red"></i>
            <?php } ?>
          </h2>
        </a>
        <div class="flex ml15">
          <a class="gray-light size-13" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
            <span class="post-space-color" style="background-color: <?= $post['space_color']; ?>;"></span>
            <?= $post['space_name']; ?>
          </a>
          <?= html_topic($post['topic_list'], 'gray-light size-13 ml15'); ?>
          <?php if ($post['post_url_domain']) { ?>
            <a class="gray-light size-13 middle ml10" href="/domain/<?= $post['post_url_domain']; ?>">
              <i class="icon-link"></i> <?= $post['post_url_domain']; ?>
            </a>
          <?php } ?>
        </div>
        <div class="pl15">
          <div class="show_add_<?= $post['post_id']; ?>">
            <div data-post_id="<?= $post['post_id']; ?>" class="showpost mt10 mb5">
              <?= $post['post_content_preview']; ?>
              <span class="s_<?= $post['post_id']; ?> show_detail"></span>
            </div>
          </div>
        </div>

        <?php if ($post['post_content_img']) { ?>
          <div class="post-img">
            <a title="<?= $post['post_title']; ?>" href="<?= post_url($post); ?>">
              <?= post_img($post['post_content_img'], $post['post_title'], 'img-post', 'cover'); ?>
            </a>
          </div>
        <?php } ?>

        <div class="pt5 pr10 pb5 mt5 pl10 hidden lowercase flex justify-content-between">
          <?= votes($uid['user_id'], $post, 'post'); ?>
          <?php if ($post['post_answers_count'] != 0) { ?>
            <a class="flex gray-light" href="<?= post_url($post); ?>">
              <?php if ($post['post_type'] == 0) { ?>
                <i class="icon-commenting-o mr5"></i>
                <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
              <?php } else { ?>
                <i class="icon-commenting-o mr5"></i>
                <?= $post['post_answers_count']; ?> <?= $post['lang_num_answers']; ?>
              <?php } ?>
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>

<?php } else { ?>
  <?= no_content('There are no posts'); ?>
<?php } ?>