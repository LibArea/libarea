<?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
  <div class="profile-box-cover" style="background-image: url(<?= user_cover_url($data['user']['user_cover_art']); ?>); background-position: 50% 50%;">
    <div class="wrap">
    <?php } else { ?>
      <style nonce="<?= $_SERVER['nonce']; ?>">
        .profile-box {
          background: <?= $data['user']['user_color']; ?>;
          min-height: 90px;
        }
      </style>
      <div class="profile-box">
        <div class="wrap">
        <?php } ?>
        <div class="profile-ava stats center">
          <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'ava'); ?>
        </div>
        </div>
      </div>

      <div class="wrap">
        <main>
          <div class="hidden white-box pt5 pr15 pb5 pl15">
            <div class="profile-header-telo">
              <h1 class="profile">
                <?= $data['user']['user_login']; ?>
                <?php if ($data['user']['user_name']) { ?> / <?= $data['user']['user_name']; ?><?php } ?>
              </h1>
            </div>

            <div class="left mt10 stats<?php if ($data['user']['user_cover_art'] == 'cover_art.jpeg') { ?> no-cover<?php } ?>">

              <?php if ($uid['user_id'] > 0) { ?>
                <div class="mt0">
                  <?php if ($uid['user_login'] == $data['user']['user_login']) { ?>
                    <a class="button block center mb15" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/setting">
                      <?= lang('Settings'); ?>
                    </a>
                  <?php } else { ?>
                    <?php if ($data['button_pm'] === true) { ?>
                      <a class="button block center size-13 mb15" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/mess">
                        <?= lang('Write a message'); ?>
                      </a>
                    <?php } ?>
                  <?php } ?>
                </div>
              <?php } ?>

              <?php if ($data['user']['user_ban_list'] == 0) { ?>
                <?php if ($data['count']['count_posts'] > 0) { ?>
                  <div class="mb5 size-15">
                    <label class="required"><?= lang('Posts-m'); ?>:</label>
                    <span class="right">
                      <a title="<?= lang('Posts-m'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('posts.user', ['login' => $data['user']['user_login']]); ?>">
                        <?= $data['count']['count_posts']; ?>
                      </a>
                    </span>
                  </div>
                <?php } ?>
                <?php if ($data['count']['count_answers'] > 0) { ?>
                  <div class="mb5 size-15">
                    <label class="required"><?= lang('Answers'); ?>:</label>
                    <span class="right">
                      <a title="<?= lang('Answers'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('answers.user', ['login' => $data['user']['user_login']]); ?>">
                        <?= $data['count']['count_answers']; ?>
                      </a>
                    </span>
                  </div>
                <?php } ?>
                <?php if ($data['count']['count_comments'] > 0) { ?>
                  <div class="mb5 size-15">
                    <label class="required"><?= lang('Comments'); ?>:</label>
                    <span class="right">
                      <a title="<?= lang('Comments'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('comments.user', ['login' => $data['user']['user_login']]); ?>">
                        <?= $data['count']['count_comments']; ?>
                      </a>
                    </span>
                  </div>
                <?php } ?>

                <?php if ($data['spaces_user']) { ?>
                  <div class="uppercase mb5 mt15 size-13"><?= lang('Created by'); ?></div>
                  <span class="d">
                    <?php foreach ($data['spaces_user'] as  $space) { ?>
                      <div class="mt5 mb5">
                        <a class="flex relative pt5 pb5 hidden gray" href="<?= getUrlByName('space', ['slug' => $space['space_slug']]); ?>" title="<?= $space['space_name']; ?>">
                          <?= spase_logo_img($space['space_img'], 'small', $space['space_name'], 'space-logo mr5'); ?>
                          <span class="bar-name size-13"><?= $space['space_name']; ?></span>
                        </a>
                      </div>
                    <?php } ?>
                  </span>
                <?php } ?>
              <?php } else { ?>
                ...
              <?php } ?>
            </div>

            <div class="box profile-telo left">
              <div class="mb20">
                <blockquote>
                  <?= $data['user']['user_about']; ?>...
                </blockquote>
              </div>
              <div class="mb20">
                <i class="icon-calendar middle"></i>
                <span class="middle">
                  <span class="ts"><?= $data['user']['user_created_at']; ?></span> —
                  <?= $data['user_trust_level']['trust_name']; ?> <sup class="date">TL<?= $data['user']['user_trust_level']; ?></sup>
                </span>
              </div>
              <h2 class="mb5 uppercase pt15 size-13"><?= lang('Contacts'); ?></h2>
              <?php if ($data['user']['user_website']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('URL'); ?>:</label>
                  <a href="<?= $data['user']['user_website']; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user']['user_website']; ?></span>
                  </a>
                </div>
              <?php } ?>
              <?php if ($data['user']['user_location']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('City'); ?>:</label>
                  <span class="mr5 ml5"><?= $data['user']['user_location']; ?></span>
                </div>
              <?php } else { ?>
                <div class="boxline">
                  <label for="name"><?= lang('City'); ?>:</label>
                  <span class="mr5 ml5">...</span>
                </div>
              <?php } ?>
              <?php if ($data['user']['user_public_email']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('E-mail'); ?>:</label>
                  <a href="mailto:<?= $data['user']['user_public_email']; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user']['user_public_email']; ?></span>
                  </a>
                </div>
              <?php } ?>
              <?php if ($data['user']['user_skype']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('Skype'); ?>:</label>
                  <a class="mr5 ml5" href="skype:<?= $data['user']['user_skype']; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user']['user_skype']; ?></span>
                  </a>
                </div>
              <?php } ?>
              <?php if ($data['user']['user_twitter']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('Twitter'); ?>:</label>
                  <a href="https://twitter.com/<?= $data['user']['user_twitter']; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user']['user_twitter']; ?></span>
                  </a>
                </div>
              <?php } ?>
              <?php if ($data['user']['user_telegram']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('Telegram'); ?>:</label>
                  <a href="tg://resolve?domain=<?= $data['user']['user_telegram']; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user']['user_telegram']; ?></span>
                  </a>
                </div>
              <?php } ?>
              <?php if ($data['user']['user_vk']) { ?>
                <div class="boxline">
                  <label for="name"><?= lang('VK'); ?>:</label>
                  <a href="https://vk.com/<?= $data['user']['user_vk']; ?>" rel="noopener nofollow ugc">
                    <span class="mr5 ml5"><?= $data['user']['user_vk']; ?></span>
                  </a>
                </div>
              <?php } ?>

              <?php if ($data['user']['user_my_post'] != 0) { ?>
                <h3 class="mb5 uppercase pt15 size-13"><?= lang('Selected Post'); ?></h3>

                <div class="post-body mb15">
                  <a class="title" href="<?= getUrlByName('post', ['id' => $data['onepost']['post_id'], 'slug' => $data['onepost']['post_slug']]); ?>">
                    <?= $data['onepost']['post_title']; ?>
                  </a>
                  <?php if ($uid['user_id'] > 0) { ?>
                    <?php if ($uid['user_login'] == $data['user']['user_login']) { ?>
                      <a class="del-post-profile ml10" data-post="<?= $data['onepost']['post_id']; ?>">
                        <i class="icon-trash-empty red"></i>
                      </a>
                    <?php } ?>
                  <?php } ?>
                  <div class="size-13 lowercase">
                    <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
                      <?= user_avatar_img($data['user']['user_avatar'], 'small', $data['user']['user_login'], 'ava mr5'); ?>
                      <?= $data['user']['user_login']; ?>
                    </a>

                    <span class="gray ml5"><?= $data['onepost']['post_date'] ?></span>

                    <a class="gray ml5" href="<?= getUrlByName('space', ['slug' => $data['onepost']['space_slug']]); ?>" title="<?= $data['onepost']['space_name']; ?>">
                      <?= $data['onepost']['space_name']; ?>
                    </a>

                    <?php if ($data['onepost']['post_answers_count'] != 0) { ?>
                      <a class="gray right" href="<?= getUrlByName('post', ['id' => $data['onepost']['post_id'], 'slug' => $data['onepost']['post_slug']]); ?>">
                        <i class="icon-comment-empty middle"></i>
                        <?= $data['onepost']['post_answers_count']; ?>
                      </a>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </main>
        <aside>
          <div class="relative white-box pt5 pr15 pb10 pl15">
            <h3 class="mt0 mb5 uppercase pt5 size-13"><?= lang('Badges'); ?></h3>
            <div class="profile-badge">
              <?php if ($data['user']['user_id'] < 50) { ?>
                <i title="<?= lang('Joined in the early days'); ?>" class="icon-award green"></i>
              <?php } ?>
              <?php foreach ($data['badges'] as $badge) { ?>
                <?= $badge['badge_icon']; ?>
              <?php } ?>
            </div>
          </div>
          <?php if ($uid['user_trust_level'] > 4) { ?>
            <div class="white-box pt5 pr15 pb5 pl15">
              <h3 class="mt0 mb10 uppercase pt10 size-13"><?= lang('Admin'); ?></h3>
              <div class="mb5">
                <a class="gray size-15 mb5 block" href="<?= getUrlByName('admin.user.edit', ['id' => $data['user']['user_id']]); ?>">
                  <i class="icon-cog-outline middle"></i>
                  <span class="middle"><?= lang('Edit'); ?></span>
                </a>
                <a class="gray size-15 block" href="<?= getUrlByName('admin.badges.user.add', ['id' => $data['user']['user_id']]); ?>">
                  <i class="icon-award middle"></i>
                  <span class="middle"><?= lang('Reward the user'); ?></span>
                </a>
                <hr>
                <span class="gray">id<?= $data['user']['user_id']; ?> | <?= $data['user']['user_email']; ?></span>
              </div>
            </div>
          <?php } ?>
        </aside>
      </div>