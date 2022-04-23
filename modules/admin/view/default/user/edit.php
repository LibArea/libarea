<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box bg-white">
  <form action="<?= getUrlByName('admin.user.change', ['id' => $data['user']['id']]); ?>" method="post">
    <?= csrf_field() ?>
    <?php if ($data['user']['cover_art'] != 'cover_art.jpeg') : ?>
      <a class="right text-sm" href="<?= getUrlByName('delete.cover', ['login' => $data['user']['login']]); ?>">
        <?= __('remove'); ?>
      </a>
      <br>
    <?php endif; ?>
    <img width="325" class="right" src="<?= Html::coverUrl($data['user']['cover_art'], 'user'); ?>">
    <?= Html::image($data['user']['avatar'], $data['user']['login'], 'avatar', 'avatar', 'max'); ?>

    <fieldset>
      <label for="post_title">
        Id<?= $data['user']['id']; ?> |
        <a target="_blank" rel="noopener noreferrer" href="<?= getUrlByName('profile', ['login' => $data['user']['login']]); ?>">
          <?= $data['user']['login']; ?>
        </a>
      </label>
      <?php if ($data['user']['trust_level'] != UserData::REGISTERED_ADMIN) : ?>
        <?php if ($data['user']['ban_list']) : ?>
          <span class="type-ban" data-id="<?= $data['user']['id']; ?>" data-type="user">
            <span class="red"><?= __('unban'); ?></span>
          </span>
        <?php else : ?>
          <span class="type-ban" data-id="<?= $data['user']['id']; ?>" data-type="user">
            <span class="green">+ <?= __('ban.it'); ?></span>
          </span>
        <?php endif; ?>
      <?php else : ?>
        ---
      <?php endif; ?>
    </fieldset>
    <fieldset>
      <i class="bi-eye"></i> <?= $data['user']['hits_count']; ?>
    </fieldset>
    <fieldset>
      <label for="post_title"><?= __('registration'); ?></label>
      <?= $data['user']['created_at']; ?> |
      <?= $data['user']['reg_ip']; ?>
      <?php if ($data['user']['duplicat_ip_reg'] > 1) : ?>
        <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
      <?php endif; ?>
      (<?= __('ed') ?>. <?= $data['user']['updated_at']; ?>)
    </fieldset>
    <hr>
   <fieldset>
      <?php if ($data['user']['limiting_mode'] == 1) : ?>
        <span class="red"><?= __('dumb.mode'); ?>!</span><br>
      <?php endif; ?>
      <label for="limiting_mode">
        <?= __('dumb.mode'); ?>?
      </label>
      <input type="radio" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 0) : ?>checked<?php endif; ?> value="0"> <?= __('no'); ?>
      <input type="radio" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 1) : ?>checked<?php endif; ?> value="1"> <?= __('yes'); ?>
    </fieldset>
    
    <fieldset>
      <label for="scroll">
        <?= __('endless.scroll'); ?>
      </label>
      <input type="radio" name="scroll" <?php if ($data['user']['scroll'] == 0) : ?>checked<?php endif; ?> value="0"> <?= __('no'); ?>
      <input type="radio" name="scroll" <?php if ($data['user']['scroll'] == 1) : ?>checked<?php endif; ?> value="1"> <?= __('yes'); ?>
    </fieldset>

    <hr>
    <fieldset>
      <?php if ($data['count']['count_posts'] != 0) : ?>
        <label class="required"><?= __('posts'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= __('posts'); ?> <?= $data['user']['login']; ?>" href="<?= getUrlByName('profile.posts', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_posts']; ?>
        </a> <br>
      <?php endif; ?>
      <?php if ($data['count']['count_answers'] != 0) : ?>
        <label class="required"><?= __('answers'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= __('answers'); ?> <?= $data['user']['login']; ?>" href="<?= getUrlByName('profile.answers', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_answers']; ?>
        </a> <br>
      <?php else : ?>
        ---
      <?php endif; ?>
      <?php if ($data['count']['count_comments'] != 0) : ?>
        <label class="required"><?= __('comments'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= __('comments'); ?> <?= $data['user']['login']; ?>" href="<?= getUrlByName('profile.comments', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_comments']; ?>
        </a> <br>
      <?php else : ?>
        ---
      <?php endif; ?>
    </fieldset>
    <hr>
    <fieldset>
      <a class="text-sm" href="<?= getUrlByName('admin.badges.user.add', ['id' => $data['user']['id']]); ?>">
        + <?= __('reward.user'); ?>
      </a>
    </fieldset>
    <fieldset>
      <label for="badge_icon"><?= __('badges'); ?></label>
      <?php if ($data['user']['badges']) : ?>
        <div class="text-2xl">
          <?php foreach ($data['user']['badges'] as $badge) : ?>
            <div class="mb5">
              <?= $badge['badge_icon']; ?>
              <span class="remove-badge text-sm lowercase" data-id="<?= $badge['bu_id']; ?>" data-uid="<?= $data['user']['id']; ?>">
                - <?= __('remove'); ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        ---
      <?php endif; ?>
    </fieldset>
    <fieldset>
      <label for="whisper"><?= __('whisper'); ?></label>
      <input type="text" name="whisper" value="<?= $data['user']['whisper']; ?>">
    </fieldset>
    <hr>
    <fieldset>
      <label for="email">E-mail<sup class="red">*</sup></label>
      <input type="text" name="email" value="<?= $data['user']['email']; ?>" required>
    </fieldset>
    <fieldset>
      <label for="activated"><?= __('email.activated'); ?>?</label>
      <input type="radio" name="activated" <?php if ($data['user']['activated'] == 0) : ?>checked<?php endif; ?> value="0"> <?= __('no'); ?>
      <input type="radio" name="activated" <?php if ($data['user']['activated'] == 1) : ?>checked<?php endif; ?> value="1"> <?= __('yes'); ?>
    </fieldset>
    <hr>
    <?php if (UserData::REGISTERED_ADMIN != $data['user']['trust_level']) : ?>
      <fieldset>
        <label for="trust_level">TL</label>
        <select name="trust_level">
          <?php for ($i = 0; $i <= 10; $i++) :
              if ($i == UserData::USER_FIFTH_LEVEL + 1) : break; endif;
              ?>
            <option <?php if ($data['user']['trust_level'] == $i) : ?>selected<?php endif; ?> value="<?= $i; ?>">
              <?= $i; ?>
            </option>
          <?php endfor; ?>
        </select>
      </fieldset>
    <?php else : ?>
      <input type="hidden" name="trust_level" value="10">
    <?php endif; ?>
    <fieldset>
      <label for="login"><?= __('nickname'); ?>: /u/**<sup class="red">*</sup></label>
      <input type="text" name="login" value="<?= $data['user']['login']; ?>" required>
    </fieldset>
    <fieldset>
      <label for="name"><?= __('name'); ?></label>
      <input type="text" name="name" value="<?= $data['user']['name']; ?>">
    </fieldset>
    <fieldset>
      <label for="about"><?= __('about.me'); ?></label>
      <textarea class="add" name="about"><?= $data['user']['about']; ?></textarea>
    </fieldset>

    <h3><?= __('contacts'); ?></h3>
    <?php foreach (Config::get('/form/user-setting') as $block) : ?>
      <fieldset>
        <label for="title"><?= $block['lang']; ?></label>
        <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
        <?php if ($block['help']) : ?>
          <div class="help"><?= $block['help']; ?></div>
        <?php endif; ?>
      </fieldset>
    <?php endforeach; ?>

    <?= Html::sumbit(__('edit')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>