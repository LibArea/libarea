<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box-white">
  <form action="/admin/user/edit/<?= $data['user']['id']; ?>" method="post">
    <?= csrf_field() ?>
    <?php if ($data['user']['cover_art'] != 'cover_art.jpeg') { ?>
      <a class="right text-sm" href="<?= getUrlByName('delete.cover', ['login' => $data['user']['login']]); ?>">
        <?= Translate::get('remove'); ?>
      </a>
      <br>
    <?php } ?>
    <img width="325" class="right" src="<?= Html::coverUrl($data['user']['cover_art'], 'user'); ?>">
    <?= Html::image($data['user']['avatar'], $data['user']['login'], 'avatar', 'avatar', 'max'); ?>

    <fieldset>
      <label for="post_title">
        Id<?= $data['user']['id']; ?> |
        <a target="_blank" rel="noopener noreferrer" href="<?= getUrlByName('profile', ['login' => $data['user']['login']]); ?>">
          <?= $data['user']['login']; ?>
        </a>
      </label>
      <?php if ($data['user']['trust_level'] != UserData::REGISTERED_ADMIN) { ?>
        <?php if ($data['user']['ban_list']) { ?>
          <span class="type-ban" data-id="<?= $data['user']['id']; ?>" data-type="user">
            <span class="red"><?= Translate::get('unban'); ?></span>
          </span>
        <?php } else { ?>
          <span class="type-ban" data-id="<?= $data['user']['id']; ?>" data-type="user">
            <span class="green">+ <?= Translate::get('ban it'); ?></span>
          </span>
        <?php } ?>
      <?php } else { ?>
        ---
      <?php } ?>
    </fieldset>
    <fieldset>
      <i class="bi-eye"></i> <?= $data['user']['hits_count']; ?>
    </fieldset>
    <fieldset>
      <label for="post_title"><?= Translate::get('registration'); ?></label>
      <?= $data['user']['created_at']; ?> |
      <?= $data['user']['reg_ip']; ?>
      <?php if ($data['user']['duplicat_ip_reg'] > 1) { ?>
        <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
      <?php } ?>
      (<?= Translate::get('ed') ?>. <?= $data['user']['updated_at']; ?>)
    </fieldset>
    <hr>
   <fieldset>
      <?php if ($data['user']['limiting_mode'] == 1) { ?>
        <span class="red"><?= Translate::get('dumb mode'); ?>!</span><br>
      <?php } ?>
      <label for="limiting_mode">
        <?= Translate::get('dumb mode'); ?>?
      </label>
      <input type="radio" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
      <input type="radio" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
    </fieldset>
    <hr>
    <fieldset>
      <?php if ($data['count']['count_posts'] != 0) { ?>
        <label class="required"><?= Translate::get('posts'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= Translate::get('posts'); ?> <?= $data['user']['login']; ?>" href="<?= getUrlByName('profile.posts', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_posts']; ?>
        </a> <br>
      <?php } ?>
      <?php if ($data['count']['count_answers'] != 0) { ?>
        <label class="required"><?= Translate::get('answers'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= Translate::get('answers'); ?> <?= $data['user']['login']; ?>" href="<?= getUrlByName('profile.answers', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_answers']; ?>
        </a> <br>
      <?php } else { ?>
        ---
      <?php } ?>
      <?php if ($data['count']['count_comments'] != 0) { ?>
        <label class="required"><?= Translate::get('comments'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= Translate::get('comments'); ?> <?= $data['user']['login']; ?>" href="<?= getUrlByName('profile.comments', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_comments']; ?>
        </a> <br>
      <?php } else { ?>
        ---
      <?php } ?>
    </fieldset>
    <hr>
    <fieldset>
      <a class="text-sm" href="/admin/badges/user/add/<?= $data['user']['id']; ?>">
        + <?= Translate::get('reward the user'); ?>
      </a>
    </fieldset>
    <fieldset>
      <label for="badge_icon"><?= Translate::get('badges'); ?></label>
      <?php if ($data['user']['badges']) { ?>
        <div class="text-2xl">
          <?php foreach ($data['user']['badges'] as $badge) { ?>
            <div class="mb5">
              <?= $badge['badge_icon']; ?>
              <span class="remove-badge text-sm lowercase" data-id="<?= $badge['bu_id']; ?>" data-uid="<?= $data['user']['id']; ?>">
                - <?= Translate::get('remove'); ?>
              </span>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        ---
      <?php } ?>
    </fieldset>
    <fieldset>
      <label for="whisper"><?= Translate::get('whisper'); ?></label>
      <input type="text" name="whisper" value="<?= $data['user']['whisper']; ?>">
    </fieldset>
    <hr>
    <fieldset>
      <label for="email">E-mail<sup class="red">*</sup></label>
      <input type="text" name="email" value="<?= $data['user']['email']; ?>" required>
    </fieldset>
    <fieldset>
      <label for="activated"><?= Translate::get('email.activated'); ?>?</label>
      <input type="radio" name="activated" <?php if ($data['user']['activated'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
      <input type="radio" name="activated" <?php if ($data['user']['activated'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
    </fieldset>
    <hr>
    <fieldset>
      <label for="trust_level">TL</label>
      <select name="trust_level">
        <?php for ($i = 0; $i <= 10; $i++) { ?>
          <option <?php if ($data['user']['trust_level'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>">
            <?= $i; ?>
          </option>
        <?php } ?>
      </select>
    </fieldset>
    <fieldset>
      <label for="login"><?= Translate::get('nickname'); ?>: /u/**<sup class="red">*</sup></label>
      <input type="text" name="login" value="<?= $data['user']['login']; ?>" required>
    </fieldset>
    <fieldset>
      <label for="name"><?= Translate::get('name'); ?></label>
      <input type="text" name="name" value="<?= $data['user']['name']; ?>">
    </fieldset>
    <fieldset>
      <label for="about"><?= Translate::get('about me'); ?></label>
      <textarea class="add" name="about"><?= $data['user']['about']; ?></textarea>
    </fieldset>

    <h3><?= Translate::get('contacts'); ?></h3>
    <?php foreach (Config::get('/form/user-setting') as $block) { ?>
      <fieldset>
        <label for="title"><?= $block['lang']; ?></label>
        <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
        <?php if ($block['help']) { ?>
          <div class="help"><?= $block['help']; ?></div>
        <?php } ?>
      </fieldset>
    <?php } ?>

    <?= Html::sumbit(Translate::get('edit')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>