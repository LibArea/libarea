<?php

use App\Bootstrap\Services\Auth\RegType; ?>

<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<form action="<?= url('admin.user.edit', ['id' => $data['user']['id']], method: 'post'); ?>" method="post">
  <?= $container->csrf()->field(); ?>

  <img width="325" height="115" class="right" src="<?= Img::cover($data['user']['cover_art'], 'user'); ?>">
  <?= Img::avatar($data['user']['avatar'], $data['user']['login'], 'avatar', 'max'); ?>

  <fieldset>
    <label for="post_title">
      Id<?= $data['user']['id']; ?> |
      <a target="_blank" rel="noopener noreferrer" href="<?= url('profile', ['login' => $data['user']['login']]); ?>">
        <?= $data['user']['login']; ?>
      </a>
      <?php if ($data['user']['is_deleted'] == 1) : ?>
        (<span class="red"><?= __('admin.deleted'); ?></span>)
      <?php endif; ?>
    </label>
    <?php if ($data['user']['trust_level'] != RegType::REGISTERED_ADMIN) : ?>
      <?php if ($data['user']['ban_list']) : ?>
        <span class="type-ban" data-id="<?= $data['user']['id']; ?>" data-type="user">
          <span class="red"><?= __('admin.unban'); ?></span>
        </span>
      <?php else : ?>
        <span class="type-ban" data-id="<?= $data['user']['id']; ?>" data-type="user">
          <span class="green">+ <?= __('admin.ban'); ?></span>
        </span>
      <?php endif; ?>
    <?php else : ?>
      ---
    <?php endif; ?>
  </fieldset>
  <fieldset>
    <label for="post_title"><?= __('admin.registration'); ?>:</label>
    <span class="gray-600">
      <?= $data['user']['created_at']; ?> |
      <?= $data['user']['reg_ip']; ?>
      <?php if ($data['user']['duplicat_ip_reg'] > 1) : ?>
        <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
      <?php endif; ?>
      (ed. <?= $data['user']['updated_at']; ?>) |
      <svg class="icon">
        <use xlink:href="/assets/svg/icons.svg#eye"></use>
      </svg> <?= $data['user']['hits_count']; ?>
    </span>
  </fieldset>

  <hr>
  <fieldset>
    <?php if ($data['user']['limiting_mode'] == 1) : ?>
      <span class="red"><?= __('admin.dumb_mode'); ?>!</span><br>
    <?php endif; ?>
    <input type="checkbox" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 1) : ?>checked <?php endif; ?>> <?= __('admin.dumb_mode'); ?>?
  </fieldset>

  <fieldset>
    <input type="checkbox" name="scroll" <?php if ($data['user']['scroll'] == 1) : ?>checked <?php endif; ?>> <?= __('app.endless_scroll'); ?>
  </fieldset>

  <hr>
  <fieldset>
    <a class="text-sm" href="<?= url('admin.badges.user.add', ['id' => $data['user']['id']]); ?>">
      + <?= __('admin.reward_user'); ?>
    </a>
  </fieldset>
  <fieldset>
    <label for="badge_icon"><?= __('admin.badges'); ?></label>
    <?php if ($data['user']['badges']) : ?>
      <div class="text-2xl">
        <?php foreach ($data['user']['badges'] as $badge) : ?>
          <div class="mb5">
            <?= $badge['badge_icon']; ?>
            <a class="remove-badge text-sm lowercase" data-id="<?= $badge['bu_id']; ?>" data-uid="<?= $data['user']['id']; ?>">
              - <?= __('admin.remove'); ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      ---
    <?php endif; ?>
  </fieldset>
  <fieldset>
    <label for="whisper"><?= __('admin.whisper'); ?></label>
    <input type="text" name="whisper" value="<?= $data['user']['whisper']; ?>">
  </fieldset>
  <hr>
  <fieldset>
    <label for="email">E-mail<sup class="red">*</sup></label>
    <input type="text" name="email" value="<?= $data['user']['email']; ?>" required>
  </fieldset>
  <fieldset>
    <input type="checkbox" name="activated" <?php if ($data['user']['activated'] == 1) : ?>checked <?php endif; ?>> <?= __('admin.activated'); ?>?
  </fieldset>
  <hr>
  <?php if (RegType::REGISTERED_ADMIN != $data['user']['trust_level']) : ?>
    <fieldset>
      <label for="trust_level">TL</label>
      <select name="trust_level">
        <?php for ($i = 1; $i <= 4; $i++) : ?>
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
    <label for="login"><?= __('admin.nickname'); ?>: /@***<sup class="red">*</sup></label>
    <input type="text" name="login" value="<?= $data['user']['login']; ?>" required>
  </fieldset>
  <fieldset>
    <label for="name"><?= __('admin.name'); ?></label>
    <input type="text" name="name" value="<?= $data['user']['name']; ?>">
  </fieldset>
  <fieldset>
    <label for="about"><?= __('admin.about'); ?></label>
    <textarea class="add" name="about"><?= $data['user']['about']; ?></textarea>
  </fieldset>

  <h3><?= __('admin.contacts'); ?></h3>


  <?php foreach (config('setting', 'contacts') as $block) : ?>
    <fieldset class="max-w-sm">
      <label for="post_title"><?= __($block['lang']); ?></label>
      <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
      <?php if ($block['help']) : ?>
        <div class="help"><?= $block['help']; ?></div>
      <?php endif; ?>
    </fieldset>
  <?php endforeach; ?>

  <fieldset>
    <input type="hidden" name="nickname" id="nickname" value="">
    <input type="hidden" name="user_id" value="<?= $data['user']['id']; ?>">
    <?= Html::sumbit(__('app.edit')); ?>
  </fieldset>

</form>

</main>

<?= insertTemplate('footer'); ?>