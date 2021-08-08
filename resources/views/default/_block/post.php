<?php if (!empty($posts)) { ?>
  <?php foreach ($posts as  $post) { ?>

    <div class="post-telo white-box">
      <div class="pt15 pr15 pb0 pl15 size-13 flex">
        <a class="gray-light" href="/u/<?= $post['login']; ?>">
          <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
          <span class="mr5 ml5">
            <?= $post['login']; ?>
          </span>
        </a>
        <span class="gray-light lowercase mr5 ml5">
          <?= $post['post_date'] ?>
        </span>
       <?php if ($data['sheet'] == 'preferences') { ?>
            <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-13 right">
                 <span class="mr5 ml5"> &#183; </span> <?= lang('Unsubscribe'); ?>
            </div>
       <?php } ?>
      </div>

      <?php if ($post['post_thumb_img']) { ?>
        <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
          <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb no-mob', 'thumbnails'); ?>
        </a>
      <?php } ?>

      <div class="post-body">
        <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
          <h2 class="title size-21 ml15 mt0 mb0"><?= $post['post_title']; ?>
            <?php if ($post['post_is_deleted'] == 1) { ?>
              <i class="light-icon-trash red"></i>
            <?php } ?>
            <?php if ($post['post_closed'] == 1) { ?>
              <i class="light-icon-lock"></i>
            <?php } ?>
            <?php if ($post['post_top'] == 1) { ?>
              <i class="light-icon-arrow-narrow-up red"></i>
            <?php } ?>
            <?php if ($post['post_lo'] > 0) { ?>
              <i class="light-icon-checks red"></i>
            <?php } ?>
            <?php if ($post['post_type'] == 1) { ?>
              <i class="light-icon-language green"></i>
            <?php } ?>
            <?php if ($post['post_translation'] == 1) { ?>
              <span class="translation size-13 italic lowercase"><?= lang('Translation'); ?></span>
            <?php } ?>
            <?php if ($post['post_tl'] > 0) { ?>
              <span class="trust-level italic size-13">tl<?= $post['post_tl']; ?></span>
            <?php } ?>
            <?php if ($post['post_merged_id'] > 0) { ?>
              <i class="light-icon-arrow-forward-up red"></i>
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
            <span class="mr5 ml5"></span>
            <a class="gray-light size-13" href="/domain/<?= $post['post_url_domain']; ?>">
              <i class="light-icon-link size-13 middle"></i> <?= $post['post_url_domain']; ?>
            </a>
          <?php } ?>
        </div>
        <div class="pl15">
          <div class="show_add_<?= $post['post_id']; ?>">
            <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
              <?= $post['post_content_preview']; ?>
              <span class="s_<?= $post['post_id']; ?> show_detail"></span>
            </div>
          </div>
        </div>

        <?php if ($post['post_content_img']) { ?>
          <div class="post-img">
            <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
              <?= post_img($post['post_content_img'], $post['post_title'], 'img-post', 'cover'); ?>
            </a>
          </div>
        <?php } ?>

        <div class="pt5 pr15 pb5 pl15 hidden lowercase">
          <?= votes($uid['id'], $post, 'post'); ?>
          <?php if ($post['post_answers_count'] != 0) { ?>
            <a class="right gray-light" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
              <?php if ($post['post_type'] == 0) { ?>
                <i class="light-icon-messages middle"></i>
                <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
              <?php } else { ?>
                <i class="light-icon-message middle"></i>
                <?= $post['post_answers_count']; ?> <?= $post['lang_num_answers']; ?>
              <?php } ?>
            </a>
          <?php } ?>
        </div>
        
      </div>
    </div>
  <?php } ?>

<?php } else { ?>
  <div class="no-content"><?= lang('There are no posts'); ?>...</div>
<?php } ?>