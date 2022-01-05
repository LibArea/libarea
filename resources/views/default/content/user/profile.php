<?php $user = $data['user']; ?>
<main class="col-span-12 mb-col-12 mb20">
  <style nonce="<?= $_SERVER['nonce']; ?>">
    .profile-box {
      background: <?= $user['user_color']; ?>;
      min-height: 90px;
    }
    .profile-box-cover { min-height: 310px; }
    .profile-box .w160 { width: 64px; }
    .bottom-20 { bottom: -20px; }
  </style>

  <?php if ($user['user_cover_art'] != 'cover_art.jpeg') { ?>
    <div class="profile-box-cover relative" style="background-image: url(<?= cover_url($user['user_cover_art'], 'user'); ?>); background-position: 50% 50%;">
    <?php } else { ?>
      <div class="profile-box relative">
      <?php } ?>
      <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w160 ml10 bottom-20 mt20 br-rd5 absolute center'); ?>
      </div>

      <div class="flex flex-row gap-4 mt20 flex-auto">
        <div class="no-mob mt20 ml15 stats">

          <?php if ($uid['user_id'] > 0) { ?>
            <div class="pb15 mb15">
              <?php if ($uid['user_login'] == $user['user_login']) { ?>
                <a class="btn btn-primary" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>/setting">
                  <?= Translate::get('settings'); ?>
                </a>
              <?php } else { ?>
                <?php if ($data['button_pm'] === true) { ?>
                  <a class="btn btn-primary" href="<?= getUrlByName('user.send.messages', ['login' => $user['user_login']]); ?>">
                    <?= Translate::get('write a message'); ?>
                  </a>
                <?php } ?>
              <?php } ?>
            </div>
          <?php } ?>

          <?php if ($user['user_ban_list'] == 0) { ?>
            <?php if ($data['count']['count_posts'] > 0) { ?>
              <div class="mb5 text-sm">
                <label class="required"><?= Translate::get('posts'); ?>:</label>
                <span class="right ml5">
                  <a title="<?= Translate::get('posts'); ?> <?= $user['user_login']; ?>" href="<?= getUrlByName('posts.user', ['login' => $user['user_login']]); ?>">
                    <?= $data['count']['count_posts']; ?>
                  </a>
                </span>
              </div>
            <?php } ?>
            <?php if ($data['count']['count_answers'] > 0) { ?>
              <div class="mb5 text-sm">
                <label class="required"><?= Translate::get('answers'); ?>:</label>
                <span class="right ml5">
                  <a title="<?= Translate::get('answers'); ?> <?= $user['user_login']; ?>" href="<?= getUrlByName('answers.user', ['login' => $user['user_login']]); ?>">
                    <?= $data['count']['count_answers']; ?>
                  </a>
                </span>
              </div>
            <?php } else { ?>
              <div class="mb5 text-sm gray">
                <?= Translate::get('answers'); ?>
                <span class="lowercase"><?= Translate::get('no'); ?>...</span>
              </div>
            <?php }  ?>
            <?php if ($data['count']['count_comments'] > 0) { ?>
              <div class="mb5 text-sm">
                <label class="required"><?= Translate::get('comments'); ?>:</label>
                <span class="right ml5">
                  <a title="<?= Translate::get('comments'); ?> <?= $user['user_login']; ?>" href="<?= getUrlByName('comments.user', ['login' => $user['user_login']]); ?>">
                    <?= $data['count']['count_comments']; ?>
                  </a>
                </span>
              </div>
            <?php } else { ?>
              <div class="mb5 text-sm gray">
                <?= Translate::get('comments'); ?>
                <span class="lowercase"><?= Translate::get('no'); ?>...</span>
              </div>
            <?php }  ?>

            <?php if ($data['topics']) { ?>
              <div class="uppercase gray mb5 mt15 text-sm"><?= Translate::get('is reading'); ?></div>
              <span class="d">
                <?php foreach ($data['topics'] as  $topic) { ?>
                  <div class="mt5 mb5">
                    <a class="flex relative pt5 pb5 hidden gray" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>" title="<?= $topic['facet_title']; ?>">
                      <?= facet_logo_img($topic['facet_img'], 'small', $topic['facet_title'], 'w18 mr10'); ?>
                      <span class="bar-name text-sm"><?= $topic['facet_title']; ?></span>
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
          <h1 class="text-2xl font-normal mb20 flex">
            <?= $user['user_login']; ?>
            <?php if ($user['user_name']) { ?> / <?= $user['user_name']; ?><?php } ?>
              <?php if ($user['user_up_count'] > 0) { ?>
                <div class="flex">
                  <div class="up-id bi bi-heart red-500 mr10 ml20 text-sm"></div>
                  <div class="text-sm gray-600"><?= $user['user_up_count']; ?></div>
                </div>
              <?php } ?>
          </h1>
          <blockquote class="mb20">
            <?= $user['user_about']; ?>...
          </blockquote>
          <div class="mb20 gray-400">
            <i class="bi bi-calendar-week middle"></i>
            <span class="middle lowercase text-sm">
              <?= $user['user_created_at']; ?> —
              <?= Translate::get('tl' . $user['user_trust_level'] . '.title'); ?> 
              <sup class="date">TL<?= $user['user_trust_level']; ?></sup>
            </span>
          </div>
          <h2 class="mb5 uppercase gray pt15 font-normal text-sm">
            <?= Translate::get('contacts'); ?>
          </h2>
          <?php foreach (Config::get('fields-profile') as $block) { ?>
            <?php if ($user[$block['title']]) { ?>
              <div class="mb20">
                <label for="name"><?= $block['lang']; ?>:</label>
                <?php if ($block['url']) { ?>
                  <a href="<?php if ($block['addition']) { ?><?= $block['addition']; ?><?php } ?><?= $user[$block['url']]; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $user[$block['title']]; ?></span>
                  </a>
                <?php } else { ?>
                  <span class="mr5 ml5"><?= $user[$block['title']]; ?></span>
                <?php } ?>
              </div>
            <?php } else { ?>
              <?php if ('user_location' == $block['title']) { ?>
                <div class="mb20">
                  <label for="name"><?= $block['lang']; ?>:</label>
                  ...
                </div>
              <?php } ?>
            <?php } ?>
          <?php } ?>
          <div class="relative mt15 pt5 pr15 pb5">
            <h3 class="mt0 mb5 uppercase gray pt5 font-normal text-sm">
              <?= Translate::get('badges'); ?>
            </h3>
            <div class="m0 text-3xl">
              <i title="<?= Translate::get('medal for registration'); ?>" class="bi bi-gift sky-500"></i>
              <?php if ($user['user_id'] < 50) { ?>
                <i title="<?= Translate::get('joined in the early days'); ?>" class="bi bi-award green-600"></i>
              <?php } ?>
              <?php foreach ($data['badges'] as $badge) { ?>
                <?= $badge['badge_icon']; ?>
              <?php } ?>
            </div>
          </div>
          <?php if ($user['user_my_post'] != 0) { ?>
            <?php $post = $data['post']; ?>
            <h3 class="mb5 uppercase gray pt10 font-normal text-sm">
              <?= Translate::get('selected post'); ?>
            </h3>
            <div class="post-body mb15">
              <a class="black dark-gray-300" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
                <?= $post['post_title']; ?>
              </a>
              <?php if ($uid['user_id'] > 0) { ?>
                <?php if ($uid['user_login'] == $user['user_login']) { ?>
                  <a class="del-post-profile ml10" data-post="<?= $post['post_id']; ?>">
                    <i class="bi bi-trash red-500"></i>
                  </a>
                <?php } ?>
              <?php } ?>
              <div class="text-sm lowercase">
                <a class="gray" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
                  <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'w18 mr5'); ?>
                  <?= $user['user_login']; ?>
                </a>
                <span class="gray ml5"><?= $post['post_date'] ?></span>
                <?php if ($post['post_answers_count'] != 0) { ?>
                  <a class="gray right" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
                    <i class="bi bi-chat-dots middle"></i>
                    <?= $post['post_answers_count']; ?>
                  </a>
                <?php } ?>
              </div>
            </div>
          <?php } ?>

          <?php if (!empty($data['participation'][0]['facet_id'])) { ?>
            <h3 class="mb5 uppercase gray pt10 font-normal text-sm">
              <?= Translate::get('understands'); ?>
            </h3>
            <?php foreach ($data['participation'] as $part) { ?>
              <a class="bg-blue-100 bg-hover-green white-hover pt5 pr10 pb5 pl10 mb5 br-rd20 sky-500 inline text-sm" href="<?= getUrlByName('topic', ['slug' => $part['facet_slug']]); ?>">
                <?= $part['facet_title']; ?>
              </a>
            <?php } ?>
          <?php } ?>

          <?php if (UserData::checkAdmin()) { ?>
            <div class="pt5 pr15 pb5">
              <h3 class="mt0 mb10 uppercase gray font-normal pt10 text-sm"><?= Translate::get('admin'); ?></h3>
              <div class="mb5">
                <?php if ($user['user_trust_level'] != UserData::REGISTERED_ADMIN) { ?>
                  <?php if ($data['isBan']) { ?>
                    <span class="type-ban gray mb5 block" data-id="<?= $user['user_id']; ?>" data-type="user">
                      <i class="bi bi-person-x-fill red-500 middle mr5"></i>
                      <span class="red-500 text-sm"><?= Translate::get('unban'); ?></span>
                    </span>
                  <?php } else { ?>
                    <span class="type-ban text-sm gray mb5 block" data-id="<?= $user['user_id']; ?>" data-type="user">
                      <i class="bi bi-person-x middle mr5"></i>
                      <?= Translate::get('ban it'); ?>
                    </span>
                  <?php } ?>
                <?php } ?>
                
                <a class="gray mb5 block" href="<?= getUrlByName('admin.user.edit', ['id' => $user['user_id']]); ?>">
                  <i class="bi bi-gear middle mr5"></i>
                  <span class="middle"><?= Translate::get('edit'); ?></span>
                </a>
                <a class="gray block" href="<?= getUrlByName('admin.badges.user.add', ['id' => $user['user_id']]); ?>">
                  <i class="bi bi-award middle mr5"></i>
                  <span class="middle"><?= Translate::get('reward the user'); ?></span>
                </a>
                <?php if ($user['user_whisper']) { ?>
                  <div class="tips text-sm pt15 pb10 gray-600">
                    <i class="bi bi-info-square green-600 mr5"></i>
                    <?= $user['user_whisper']; ?>
                  </div>
                <?php } ?>
                <hr>
                <span class="gray">id<?= $user['user_id']; ?> | <?= $user['user_email']; ?></span>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
</main>
</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>