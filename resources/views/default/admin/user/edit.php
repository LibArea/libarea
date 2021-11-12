<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    getUrlByName('admin.users'),
    Translate::get('users'),
    Translate::get('edit user')
  ); ?>

  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15">
    <form action="/admin/user/edit/<?= $data['user']['user_id']; ?>" method="post">
      <?= csrf_field() ?>
      <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
        <a class="right size-13" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>/delete/cover">
          <?= Translate::get('remove'); ?>
        </a>
        <br>
      <?php } ?>
      <img width="325" class="right" src="<?= user_cover_url($data['user']['user_cover_art']); ?>">
      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'avatar'); ?>

      <div class="mb20">
        <label class="block" for="post_title">
          Id<?= $data['user']['user_id']; ?> |
          <a target="_blank" rel="noopener noreferrer" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['user']['user_login']; ?>
          </a>
        </label>
        <?php if ($data['user']['user_trust_level'] != 5) { ?>
          <?php if ($data['user']['isBan']) { ?>
            <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
              <span class="red"><?= Translate::get('unban'); ?></span>
            </span>
          <?php } else { ?>
            <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
              <span class="green">+ <?= Translate::get('ban it'); ?></span>
            </span>
          <?php } ?>
        <?php } else { ?>
          ---
        <?php } ?>
      </div>
      <div class="mb20">
        <i class="bi bi-eye"></i> <?= $data['user']['user_hits_count']; ?>
      </div>
      <div class="mb20">
        <label class="block" for="post_title"><?= Translate::get('sign up'); ?></label>
        <?= $data['user']['user_created_at']; ?> |
        <?= $data['user']['user_reg_ip']; ?>
        <?php if ($data['user']['duplicat_ip_reg'] > 1) { ?>
          <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
        <?php } ?>
        (<?= Translate::get('ed') ?>. <?= $data['user']['user_updated_at']; ?>)
      </div>
      <hr>
      <div class="mb20">
        <?php if ($data['user']['user_limiting_mode'] == 1) { ?>
          <span class="red"><?= Translate::get('dumb mode'); ?>!</span><br>
        <?php } ?>
        <label class="block" for="post_content">
          <?= Translate::get('dumb mode'); ?>?
        </label>
        <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
        <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
      </div>
      <hr>
      <div class="mb20">
        <?php if ($data['count']['count_posts'] != 0) { ?>
          <label class="required"><?= Translate::get('posts'); ?>:</label>
          <a target="_blank" rel="noopener noreferrer" title="<?= Translate::get('posts'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('posts.user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['count']['count_posts']; ?>
          </a> <br>
        <?php } ?>
        <?php if ($data['count']['count_answers'] != 0) { ?>
          <label class="required"><?= Translate::get('answers'); ?>:</label>
          <a target="_blank" rel="noopener noreferrer" title="<?= Translate::get('answers'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('answers.user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['count']['count_answers']; ?>
          </a> <br>
        <?php } else { ?>
          ---
        <?php } ?>
        <?php if ($data['count']['count_comments'] != 0) { ?>
          <label class="required"><?= Translate::get('comments'); ?>:</label>
          <a target="_blank" rel="noopener noreferrer" title="<?= Translate::get('comments'); ?> <?= $data['user']['user_login']; ?>" href="<?= getUrlByName('comments.user', ['login' => $data['user']['user_login']]); ?>">
            <?= $data['count']['count_comments']; ?>
          </a> <br>
        <?php } else { ?>
          ---
        <?php } ?>
      </div>
      <hr>
      <div class="mb20">
        <a class="size-14" href="/admin/badges/user/add/<?= $data['user']['user_id']; ?>">
          + <?= Translate::get('reward the user'); ?>
        </a>
      </div>
      <div class="mb20">
        <label class="block" for="post_title"><?= Translate::get('badges'); ?></label>
        <?php if ($data['user']['badges']) { ?>
          <?php foreach ($data['user']['badges'] as $badge) { ?>
            <?= $badge['badge_icon']; ?>
          <?php } ?>
        <?php } else { ?>
          ---
        <?php } ?>
      </div>
      <div class="mb20">
        <label class="block" for="post_title"><?= Translate::get('whisper'); ?></label>
        <input class="w-100 h30" type="text" name="whisper" value="<?= $data['user']['user_whisper']; ?>">
      </div>
      <hr>
      <div class="mb20">
        <label class="block" for="post_title">E-mail<sup class="red">*</sup></label>
        <input class="w-100 h30" type="text" name="email" value="<?= $data['user']['user_email']; ?>" required>
      </div>
      <div class="mb20">
        <label class="block" for="post_content"><?= Translate::get('email activated'); ?>?</label>
        <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
        <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
      </div>
      <hr>
      <div class="mb20">
        <label class="block" for="post_title">TL</label>
        <select name="trust_level">
          <?php for ($i = 0; $i <= 5; $i++) {  ?>
            <option <?php if ($data['user']['user_trust_level'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>">
              <?= $i; ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="mb20">
        <label class="block" for="post_title"><?= Translate::get('nickname'); ?>: /u/**<sup class="red">*</sup></label>
        <input class="w-100 h30" type="text" name="login" value="<?= $data['user']['user_login']; ?>" required>
      </div>
      <div class="mb20">
        <label class="block" for="post_title"><?= Translate::get('name'); ?><sup class="red">*</sup></label>
        <input class="w-100 h30" type="text" name="name" value="<?= $data['user']['user_name']; ?>">
      </div>
      <div class="mb20">
        <label class="block" for="post_title"><?= Translate::get('about me'); ?></label>
        <textarea class="add" name="about"><?= $data['user']['user_about']; ?></textarea>
      </div>

      <h3><?= Translate::get('contacts'); ?></h3>
      <?php foreach (Config::get('fields-profile') as $block) { ?>
        <div class="mb20">
          <label class="block" for="post_title"><?= $block['lang']; ?></label>
          <input class="w-100 h30" maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
          <?php if ($block['help']) { ?>
            <div class="size-14 gray-light-2"><?= $block['help']; ?></div>
          <?php } ?>
        </div>
      <?php } ?>

      <?= sumbit(Translate::get('edit')); ?>
    </form>
  </div>
</main>