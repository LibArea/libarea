<main class="col-span-12 mb-col-12">
  <style nonce="<?= $_SERVER['nonce']; ?>">
    .profile-box {
      background: <?= $data['user']['user_color']; ?>;
      min-height: 90px;
    }

    .profile-box-cover {
      min-height: 310px;
    }

    .profile-box .w160 {
      width: 64px;
    }

    .bottom-20 {
      bottom: -20px;
    }
  </style>

  <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
    <div class="profile-box-cover relative" style="background-image: url(<?= user_cover_url($data['user']['user_cover_art']); ?>); background-position: 50% 50%;">
    <?php } else { ?>
      <div class="profile-box relative">
      <?php } ?>
      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w160 ml10 bottom-20 mt20 br-rd-5 absolute center'); ?>
      </div>

      <div class="flex flex-row gap-4 mt20 flex-auto">

        <div class="no-mob mt20 ml15 stats">

          <?php if ($uid['user_id'] > 0) { ?>
            <div class="pb15 mb15">
              <?php if ($uid['user_login'] == $data['user']['user_login']) { ?>
                <a class="button block br-rd-5 white center mb15" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/setting">
                  <?= lang('settings'); ?>
                </a>
              <?php } else { ?>
                <?php if ($data['button_pm'] === true) { ?>
                  <a class="button br-rd-5 white center size-14" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/mess">
                    <?= lang('write a message'); ?>
                  </a>
                <?php } ?>
              <?php } ?>
            </div>
          <?php } ?>

          <?php if ($data['user']['user_ban_list'] == 0) { ?>
            <?php if ($data['count']['count_posts'] > 0) { ?>
              <div class="mb5 size-14">
                <label class="required"><?= lang('posts-m'); ?>:</label>
                <span class="right ml5">
                  <a title="<?= lang('posts-m'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('posts.user', ['login' => $data['user']['user_login']]); ?>">
                    <?= $data['count']['count_posts']; ?>
                  </a>
                </span>
              </div>
            <?php } ?>
            <?php if ($data['count']['count_answers'] > 0) { ?>
              <div class="mb5 size-14">
                <label class="required"><?= lang('answers'); ?>:</label>
                <span class="right ml5">
                  <a title="<?= lang('answers'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('answers.user', ['login' => $data['user']['user_login']]); ?>">
                    <?= $data['count']['count_answers']; ?>
                  </a>
                </span>
              </div>
            <?php } else { ?>
              <div class="mb5 size-14 gray">
                <?= lang('answers'); ?>
                <span class="lowercase"><?= lang('no'); ?>...</span>
              </div>
            <?php }  ?>
            <?php if ($data['count']['count_comments'] > 0) { ?>
              <div class="mb5 size-14">
                <label class="required"><?= lang('comments'); ?>:</label>
                <span class="right ml5">
                  <a title="<?= lang('comments'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('comments.user', ['login' => $data['user']['user_login']]); ?>">
                    <?= $data['count']['count_comments']; ?>
                  </a>
                </span>
              </div>
            <?php } else { ?>
              <div class="mb5 size-14 gray">
                <?= lang('comments'); ?>
                <span class="lowercase"><?= lang('no'); ?>...</span>
              </div>
            <?php }  ?>

            <?php if ($data['topics']) { ?>
              <div class="uppercase mb5 mt15 size-14"><?= lang('reads'); ?></div>
              <span class="d">
                <?php foreach ($data['topics'] as  $topic) { ?>
                  <div class="mt5 mb5">
                    <a class="flex relative pt5 pb5 hidden gray" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>" title="<?= $topic['topic_title']; ?>">
                      <?= topic_logo_img($topic['topic_img'], 'small', $topic['topic_title'], 'w18 mr10'); ?>
                      <span class="bar-name size-14"><?= $topic['topic_title']; ?></span>
                    </a>
                  </div>
                <?php } ?>
              </span>
            <?php } ?>
          <?php } else { ?>
            ...
          <?php } ?>
        </div>

        <div class="mt10 ml60 mb-ml-10">
          <h1 class="size-24 mb20 flex">
            <?= $data['user']['user_login']; ?>
            <?php if ($data['user']['user_name']) { ?> / <?= $data['user']['user_name']; ?><?php } ?>
              <?php if ($data['user']['user_up_count'] > 0) { ?>
                <div class="flex">
                  <div class="up-id bi bi-heart red mr10 ml20 size-14"></div>
                  <div class="size-14 gray-light"><?= $data['user']['user_up_count']; ?></div>
                </div>
              <?php } ?>
          </h1>
          <blockquote class="mb20">
            <?= $data['user']['user_about']; ?>...
          </blockquote>
          <div class="mb20">
            <i class="bi bi-calendar-week middle"></i>
            <span class="middle">
              <span class="ts"><?= $data['user']['user_created_at']; ?></span> —
              <?= $data['user_trust_level']['trust_name']; ?> <sup class="date">TL<?= $data['user']['user_trust_level']; ?></sup>
            </span>
          </div>
          <h2 class="mb5 uppercase pt15 font-normal size-14">
            <?= lang('contacts'); ?>
          </h2>
        <?php foreach (Config::get('fields-profile') as $block) { ?>
            <?php if ($data['user'][$block['title']]) { ?>
                <div class="boxline">
                  <label for="name"><?= $block['lang']; ?>:</label>
                <?php if ($block['url']) { ?>  
                  <a href="<?php if ($block['addition']) { ?><?= $block['addition']; ?><?php } ?><?= $data['user'][$block['url']]; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user'][$block['title']]; ?></span>
                  </a>
                <?php } else { ?>        
                   <span class="mr5 ml5"><?= $data['user'][$block['title']]; ?></span>
                <?php } ?>
                </div>
             <?php } else { ?>
                <?php if ('user_location' == $block['title']) { ?>
                <div class="boxline">
                  <label for="name"><?= $block['lang']; ?>:</label>
                  ...
                </div>
                <?php } ?>
             <?php } ?>
        <?php } ?>
          <div class="relative mt15 pt5 pr15 pb5">
            <h3 class="mt0 mb5 uppercase pt5 font-normal size-14">
              <?= lang('badges'); ?>
            </h3>
            <div class="m0 size-31">
              <i title="<?= lang('medal for registration'); ?>" class="bi bi-gift blue"></i>
              <?php if ($data['user']['user_id'] < 50) { ?>
                <i title="<?= lang('joined in the early days'); ?>" class="bi bi-award green"></i>
              <?php } ?>
              <?php foreach ($data['badges'] as $badge) { ?>
                <?= $badge['badge_icon']; ?>
              <?php } ?>
            </div>
          </div>
          <?php if ($data['user']['user_my_post'] != 0) { ?>
            <h3 class="mb5 uppercase pt10 font-normal size-14">
              <?= lang('selected post'); ?>
            </h3>
            <div class="post-body mb15">
              <a class="title" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>">
                <?= $data['post']['post_title']; ?>
              </a>
              <?php if ($uid['user_id'] > 0) { ?>
                <?php if ($uid['user_login'] == $data['user']['user_login']) { ?>
                  <a class="del-post-profile ml10" data-post="<?= $data['post']['post_id']; ?>">
                    <i class="bi bi-trash red"></i>
                  </a>
                <?php } ?>
              <?php } ?>
              <div class="size-14 lowercase">
                <a class="gray" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
                  <?= user_avatar_img($data['user']['user_avatar'], 'small', $data['user']['user_login'], 'w18 mr5'); ?>
                  <?= $data['user']['user_login']; ?>
                </a>
                <span class="gray ml5"><?= $data['post']['post_date'] ?></span>
                <?php if ($data['post']['post_answers_count'] != 0) { ?>
                  <a class="gray right" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>">
                    <i class="bi bi-chat-dots middle"></i>
                    <?= $data['post']['post_answers_count']; ?>
                  </a>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
          
          <?php if (!empty($data['participation'][0]['topic_id'])) { ?>
             <h3 class="mb5 uppercase pt10 font-normal size-14">
              <?= lang('understands'); ?>
            </h3>
            <?php foreach ($data['participation'] as $part) { ?>
               <a class="bg-blue-100 bg-hover-300 white-hover pt5 pr10 pb5 pl10 mb5 br-rd-20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $part['topic_slug']]); ?>">
                <?= $part['topic_title']; ?>
                </a>
            <?php } ?>
          <?php } ?>

          <?php if ($uid['user_trust_level'] > 4) { ?>
            <div class="pt5 pr15 pb5">
              <h3 class="mt0 mb10 uppercase pt10 size-14"><?= lang('admin'); ?></h3>
              <div class="mb5">
                <?php if ($uid['user_trust_level'] != 5) { ?>
                <?php if ($data['isBan']) { ?>
                  <span class="type-ban gray size-15 mb5 block" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
                    <i class="bi bi-person-x-fill red middle mr5"></i>
                    <span class="red size-14"><?= lang('unban'); ?></span>
                  </span>
                <?php } else { ?>
                  <span class="type-ban size-14 gray size-15 mb5 block" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
                    <i class="bi bi-person-x middle mr5"></i>
                    <?= lang('ban it'); ?>
                  </span>
                <?php } ?>
                <?php } ?>
                <a class="gray size-15 mb5 block" href="<?= getUrlByName('admin.user.edit', ['id' => $data['user']['user_id']]); ?>">
                  <i class="bi bi-gear middle mr5"></i>
                  <span class="middle"><?= lang('edit'); ?></span>
                </a>
                <a class="gray size-15 block" href="<?= getUrlByName('admin.badges.user.add', ['id' => $data['user']['user_id']]); ?>">
                  <i class="bi bi-award middle mr5"></i>
                  <span class="middle"><?= lang('reward the user'); ?></span>
                </a>
                <?php if ($data['user']['user_whisper']) { ?>
                  <div class="tips size-14 pt15 pb10 gray-light">
                    <i class="bi bi-info-square green mr5"></i>
                    <?= $data['user']['user_whisper']; ?>
                  </div>
                <?php } ?>
                <hr>
                <span class="gray">id<?= $data['user']['user_id']; ?> | <?= $data['user']['user_email']; ?></span>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>