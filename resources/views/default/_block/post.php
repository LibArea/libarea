<?php if (!empty($data['posts'])) { ?>
  <?php foreach ($data['posts'] as $post) { ?>
    <?php $post_url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="border-box-1 bg-white p20 mb15 br-rd-5 article_<?= $post['post_id']; ?>">
        <?php if ($data['sheet'] == 'subscribed') { ?>
          <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-14 right">
            <?= lang('unsubscribe'); ?>
          </div>
        <?php } ?>
    
      <div class="flex mb15">
        <a class="flex flex-center" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
          <div class="mr5">
            <?= user_avatar_img($post['user_avatar'], 'max', $post['user_login'], 'w44 br-rd-50'); ?>
          </div>
          <div class="ml5">
            <div class="black">
              <?= $post['user_login']; ?>
            </div>
            <div class="gray-light size-14">
              <?= $post['post_date'] ?>
            </div>
          </div>
        </a>
      </div>
 
      <div class="flex flex-row flex-auto">
        <div class="w-full w-auto pc-mr-20">
          <a href="<?= $post_url; ?>">
            <h2 class="font-normal black size-24 mt0 mb0"><?= $post['post_title']; ?>
              <?php if ($post['post_is_deleted'] == 1) { ?>
                <i class="icon-trash-empty blue"></i>
              <?php } ?>
              <?php if ($post['post_closed'] == 1) { ?>
                <i class="icon-lock gray"></i>
              <?php } ?>
              <?php if ($post['post_top'] == 1) { ?>
                <i class="icon-pin-outline blue"></i>
              <?php } ?>
              <?php if ($post['post_lo'] > 0) { ?>
                <i class="icon-diamond blue"></i>
              <?php } ?>
              <?php if ($post['post_type'] == 1) { ?>
                <i class="icon-help green"></i>
              <?php } ?>
              <?php if ($post['post_translation'] == 1) { ?>
                <span class="pt5 pr10 pb5 pl10 gray-light bg-yellow-100 br-rd-3 size-14 italic lowercase">
                  <?= lang('translation'); ?>
                </span>
              <?php } ?>
              <?php if ($post['post_tl'] > 0) { ?>
                <span class="pt5 pr10 pb5 pl10 gray-light bg-orange-100 br-rd-3 italic size-14">
                  tl<?= $post['post_tl']; ?>
                </span>
              <?php } ?>
              <?php if ($post['post_merged_id'] > 0) { ?>
                <i class="icon-link-ext blue"></i>
              <?php } ?>
            </h2>
          </a>
          <div class="">
            <a class="gray-light size-14" href="<?= getUrlByName('space', ['slug' => $post['space_slug']]); ?>" title="<?= $post['space_name']; ?>">
              <span class="w10 h10 inline mr5" style="background-color: <?= $post['space_color']; ?>;"></span>
              <?= $post['space_name']; ?>
            </a>
            <?= html_topic($post['topic_list'], 'gray-light size-14 ml15'); ?>
            <?php if ($post['post_url_domain']) { ?>
              <a class="gray-light size-14 middle ml10" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <i class="icon-link"></i> <?= $post['post_url_domain']; ?>
              </a>
            <?php } ?>
          </div>

          <div class="show_add_<?= $post['post_id']; ?>">
            <div data-post_id="<?= $post['post_id']; ?>" class="size-18 showpost mt10 mb5 gray-light">
              <?= $post['post_content_preview']; ?>
              <span class="s_<?= $post['post_id']; ?> show_detail"></span>
            </div>
          </div>

        </div>
        <?php if ($post['post_thumb_img']) { ?>
          <div class="home-img mt15 flex-auto">
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb no-mob right', 'thumbnails'); ?>
            </a>
          </div>
        <?php } ?>

        <?php if ($post['post_content_img']) { ?>
          <div class="home-img mt15 flex-auto">
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= post_img($post['post_content_img'], $post['post_title'], 'home-img', 'cover'); ?>
            </a>
          </div>
        <?php } ?>

      </div>

      <div class="flex flex-row items-center justify-between pt10">
        <div class="flex flex-row items-center">
          <?= votes($uid['user_id'], $post, 'post'); ?>
          <?php if ($post['post_answers_count'] != 0) { ?>
            <a class="flex gray-light-2 ml15" href="<?= $post_url; ?>">
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
        <div class="flex flex-row items-center">
          <?= favorite_post($uid['user_id'], $post['post_id'], $post['favorite_tid']); ?>
        </div>
      </div>

    </div>
  <?php } ?>
<?php } else { ?>
  <?= includeTemplate('/_block/no-content', ['lang' => 'there are no posts']); ?>
<?php } ?>