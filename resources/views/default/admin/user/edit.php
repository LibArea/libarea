<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), '/admin/users', lang('users'), lang('edit user')); ?>

  <div class="box badges">
    <form action="/admin/user/edit/<?= $data['user']['user_id']; ?>" method="post">
      <?= csrf_field() ?>
      <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
        <a class="right size-13" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/delete/cover">
          <?= lang('remove'); ?>
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
              <span class="red"><?= lang('unban'); ?></span>
            </span>
          <?php } else { ?>
            <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
              <span class="green">+ <?= lang('ban it'); ?></span>
            </span>
          <?php } ?>
        <?php } else { ?>
          ---
        <?php } ?>
      </div>
      <div class="boxline">
        <i class="bi bi-eye"></i> <?= $data['user']['user_hits_count']; ?>
      </div>
      <div class="boxline">
        <label class="form-label" for="post_title"><?= lang('sign up'); ?></label>
        <?= $data['user']['user_created_at']; ?> |
        <?= $data['user']['user_reg_ip']; ?>
        <?php if ($data['user']['duplicat_ip_reg'] > 1) { ?>
          <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
        <?php } ?>
        (<?= lang('ed') ?>. <?= $data['user']['user_updated_at']; ?>)
      </div>
      <hr>
      <div class="boxline">
        <?php if ($data['user']['user_limiting_mode'] == 1) { ?>
          <span class="red"><?= lang('dumb mode'); ?>!</span><br>
        <?php } ?>
        <label class="form-label" for="post_content">
          <?= lang('dumb mode'); ?>?
        </label>
        <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('no'); ?>
        <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('yes'); ?>
      </div>
      <hr>
      <div class="boxline">
        <?php if ($data['count']['count_posts'] != 0) { ?>
          <label class="required"><?= lang('posts-m'); ?>:</label>
          <a target="_blank" rel="noopener noreferrer" title="<?= lang('posts-m'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('posts.user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['count']['count_posts']; ?>
          </a> <br>
        <?php } ?>
        <?php if ($data['count']['count_answers'] != 0) { ?>
          <label class="required"><?= lang('answers'); ?>:</label>
          <a target="_blank" rel="noopener noreferrer" title="<?= lang('answers'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('answers.user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['count']['count_answers']; ?>
          </a> <br>
        <?php } else { ?>
          ---
        <?php } ?>
        <?php if ($data['count']['count_comments'] != 0) { ?>
          <label class="required"><?= lang('comments'); ?>:</label>
          <a target="_blank" rel="noopener noreferrer" title="<?= lang('comments'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('comments.user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['count']['count_comments']; ?>
          </a> <br>
        <?php } else { ?>
          ---
        <?php } ?>
        <?php if ($data['spaces_user']) { ?>
          <label class="form-label mt10"><?= lang('created by'); ?>:</label>
          <?php foreach ($data['spaces_user'] as  $space) { ?>
            <div class="mb5">
              <?= spase_logo_img($space['space_img'], 'small', $space['space_name'], 'w24'); ?>
              <a class="size-14" href="<?= getUrlByName('space', ['slug' => $space['space_slug']]); ?>">
                <?= $space['space_name']; ?>
              </a>
            </div>
          <?php } ?>
        <?php } else { ?>
          ---
        <?php } ?>
      </div>
      <hr>
      <div class="boxline">
        <a class="size-14" href="/admin/badges/user/add/<?= $data['user']['user_id']; ?>">
          + <?= lang('reward the user'); ?>
        </a>
      </div>
      <div class="boxline">
        <label class="form-label" for="post_title"><?= lang('badges'); ?></label>
        <?php if ($data['user']['badges']) { ?>
          <?php foreach ($data['user']['badges'] as $badge) { ?>
            <?= $badge['badge_icon']; ?>
          <?php } ?>
        <?php } else { ?>
          ---
        <?php } ?>
      </div>
      <div class="boxline">
        <label class="form-label" for="post_title"><?= lang('whisper'); ?></label>
        <input class="form-input" type="text" name="whisper" value="<?= $data['user']['user_whisper']; ?>">
      </div>
      <hr>
      <div class="boxline">
        <label class="form-label" for="post_title">E-mail</label>
        <input class="form-input" type="text" name="email" value="<?= $data['user']['user_email']; ?>" required>
      </div>
      <div class="boxline">
        <label class="form-label" for="post_content"><?= lang('email activated'); ?>?</label>
        <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('no'); ?>
        <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('yes'); ?>
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
        <label class="form-label" for="post_title"><?= lang('nickname'); ?>: /u/***</label>
        <input class="form-input" type="text" name="login" value="<?= $data['user']['user_login']; ?>" required>
      </div>
      <div class="boxline">
        <label class="form-label" for="post_title"><?= lang('name'); ?></label>
        <input class="form-input" type="text" name="name" value="<?= $data['user']['user_name']; ?>">
      </div>
      <div class="boxline">
        <label class="form-label" for="post_title"><?= lang('about me'); ?></label>
        <textarea class="add" name="about"><?= $data['user']['user_about']; ?></textarea>
      </div>

      <h3><?= lang('contacts'); ?></h3>
      <?php foreach (Config::arr('fields-profile') as $block) { ?>
        <div class="boxline">
          <label class="form-label" for="post_title"><?= $block['lang']; ?></label>
          <input class="form-input" maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
          <?php if ($block['help']) { ?>
            <div class="size-14 gray-light-2"><?= $block['help']; ?></div>
          <?php } ?>
        </div>
      <?php } ?>

      <input type="submit" class="button block br-rd-5 white" name="submit" value="<?= lang('edit'); ?>" />
    </form>
  </div>
</main>