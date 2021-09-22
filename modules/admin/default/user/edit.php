<div class="wrap">
  <main class="admin white-box pt5 pr15 pb10 pl15">
    <?= breadcrumb('/admin', lang('Admin'), '/admin/users', lang('Users'), lang('Edit user')); ?>

    <div class="box badges">
      <form action="/admin/user/edit/<?= $data['user']['user_id']; ?>" method="post">
        <?= csrf_field() ?>
        <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
          <a class="right size-13" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/delete/cover">
            <?= lang('Remove'); ?>
          </a>
          <br>
        <?php } ?>
        <img width="325" class="right" src="<?= user_cover_url($data['user']['user_cover_art']); ?>">
        <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'avatar'); ?>

        <div class="boxline">
          <label class="form-label" for="post_title">
            Id<?= $data['user']['user_id']; ?> |
            <a target="_blank" rel="noopener noreferrer" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
              <?= $data['user']['user_login']; ?>
            </a>
          </label>
          <?php if ($data['user']['user_trust_level'] != 5) { ?>
            <?php if ($data['user']['isBan']) { ?>
              <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
                <span class="red"><?= lang('Unban'); ?></span>
              </span>
            <?php } else { ?>
              <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
                <span class="green">+ <?= lang('Ban it'); ?></span>
              </span>
            <?php } ?>
          <?php } else { ?>
            ---
          <?php } ?>
        </div>
        <hr>
        <div class="boxline">
          <?php if ($data['user']['user_limiting_mode'] == 1) { ?>
            <span class="red"><?= lang('Dumb mode'); ?>!</span><br>
          <?php } ?>
          <label class="form-label" for="post_content">
            <?= lang('Dumb mode'); ?>?
          </label>
          <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
          <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
        </div>
        <hr>
        <div class="boxline">
          <?php if ($data['posts_count'] != 0) { ?>
            <label class="required"><?= lang('Posts-m'); ?>:</label>
            <a target="_blank" rel="noopener noreferrer" title="<?= lang('Posts-m'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('posts.user', ['login' => $data['user']['user_login']]); ?>">
              <?= $data['posts_count']; ?>
            </a>
            <br>
          <?php } ?>
          <?php if ($data['answers_count'] != 0) { ?>
            <label class="required"><?= lang('Answers'); ?>:</label>
            <a target="_blank" rel="noopener noreferrer" title="<?= lang('Answers'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('answers.user', ['login' => $data['user']['user_login']]); ?>">
              <?= $data['answers_count']; ?>
            </a>
            <br>
          <?php } else { ?>
           ---
          <?php } ?>
          <?php if ($data['comments_count'] != 0) { ?>
            <label class="required"><?= lang('Comments'); ?>:</label>
            <a target="_blank" rel="noopener noreferrer" title="<?= lang('Comments'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('comments.user', ['login' => $data['user']['user_login']]); ?>">
              <?= $data['comments_count']; ?>
            </a>
            <br>
          <?php } else { ?>
           ---
          <?php } ?>
          <?php if ($data['spaces_user']) { ?>
            <br>
            <label class="required"><?= lang('Created by'); ?>:</label>
            <br>
            <span class="d">
              <?php foreach ($data['spaces_user'] as  $space) { ?>
                <div class="profile-space">
                  <?= spase_logo_img($space['space_img'], 'small', $space['space_name'], 'ava-24'); ?>
                  <a href="<?= getUrlByName('space', ['slug' => $space['space_slug']]); ?>"><?= $space['space_name']; ?></a>
                </div>
              <?php } ?>
            </span>
          <?php } else { ?>
           ---
          <?php } ?>
        </div>
        <hr>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= lang('Badge'); ?></label>
          <a class="lowercase" href="/admin/badges/user/add/<?= $data['user']['user_id']; ?>">
            <?= lang('Reward the user'); ?>
          </a>
        </div>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= lang('Badges'); ?></label>
          <?php if ($data['user']['badges']) { ?>
            <?php foreach ($data['user']['badges'] as $badge) { ?>
              <?= $badge['badge_icon']; ?>
            <?php } ?>
          <?php } else { ?>
            ---
          <?php } ?>
        </div>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= lang('Whisper'); ?></label>
          <input class="form-input" type="text" name="whisper" value="<?= $data['user']['user_whisper']; ?>">
        </div>
        <div class="boxline">
          <label for="post_title"><?= lang('Views'); ?></label>
          <?= $data['user']['user_hits_count']; ?>
        </div>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= lang('Sign up'); ?></label>
          <?= $data['user']['user_created_at']; ?> |
          <?= $data['user']['user_reg_ip']; ?>
          <?php if ($data['user']['duplicat_ip_reg'] > 1) { ?>
            <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
          <?php } ?>
          (<?= lang('ed')?>. <?= $data['user']['user_updated_at']; ?>)
        </div>
        <hr>
        <div class="boxline">
          <label class="form-label" for="post_title">E-mail</label>
          <input class="form-input" type="text" name="email" value="<?= $data['user']['user_email']; ?>" required>
        </div>
        <div class="boxline">
          <label class="form-label" for="post_content"><?= lang('Email activated'); ?>?</label>
          <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
          <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
        </div>
        <hr>
        <div class="boxline">
          <label class="form-label" for="post_title">TL</label>
          <select name="trust_level">
            <?php for ($i = 0; $i <= 5; $i++) {  ?>
              <option <?php if ($data['user']['user_trust_level'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>">
                <?= $i; ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="boxline">
          <label for="post_title"><?= lang('Nickname'); ?>:</label>
          /u/***
          <input class="form-input" type="text" name="login" value="<?= $data['user']['user_login']; ?>" required>
        </div>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= lang('Name'); ?></label>
          <input class="form-input" type="text" name="name" value="<?= $data['user']['user_name']; ?>">
        </div>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= lang('About me'); ?></label>
          <textarea class="add" name="about"><?= $data['user']['user_about']; ?></textarea>
        </div>

        <h3><?= lang('Contacts'); ?></h3>
        <?php field('input', [
          ['title' => lang('URL'), 'type' => 'text', 'name' => 'website', 'value' => $data['user']['user_website'], 'max' => 150, 'help' => 'https://site.ru'],
          ['title' => lang('City'), 'type' => 'text', 'name' => 'location', 'value' => $data['user']['user_location'], 'max' => 150, 'help' => 'Moscow...'],
          ['title' => lang('E-mail'), 'type' => 'text', 'name' => 'public_email', 'value' => $data['user']['user_public_email'], 'max' => 150, 'help' => '**@**.ru'],
          ['title' => lang('Skype'), 'type' => 'text', 'name' => 'skype', 'value' => $data['user']['user_skype'], 'max' => 150, 'help' => 'skype:<b>NICK</b>'],
          ['title' => lang('Twitter'), 'type' => 'text', 'name' => 'twitter', 'value' => $data['user']['user_twitter'], 'max' => 150, 'help' => 'https://twitter.com/<b>NICK</b>'],
          ['title' => lang('Telegram'), 'type' => 'text', 'name' => 'telegram', 'value' => $data['user']['user_telegram'], 'max' => 150, 'help' => 'tg://resolve?domain=<b>NICK</b>'],
          ['title' => lang('VK'), 'type' => 'text', 'name' => 'vk', 'value' => $data['user']['user_vk'], 'max' => 150, 'help' => 'https://vk.com/<b>NICK / id</b>'],
        ]); ?>

        <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
      </form>
    </div>
  </main>
</div>