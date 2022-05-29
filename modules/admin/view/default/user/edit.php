<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box bg-white">
  <form action="<?= url('admin.user.change', ['id' => $data['user']['id']]); ?>" method="post">
    <?= csrf_field() ?>
    <?php if ($data['user']['cover_art'] != 'cover_art.jpeg') : ?>
      <a class="right text-sm" href="<?= url('delete.cover', ['login' => $data['user']['login']]); ?>">
        <?= __('admin.remove'); ?>
      </a>
      <br>
    <?php endif; ?>
    <img width="325" class="right" src="<?= Html::coverUrl($data['user']['cover_art'], 'user'); ?>">
    <?= Html::image($data['user']['avatar'], $data['user']['login'], 'avatar', 'avatar', 'max'); ?>

    <fieldset>
      <label for="post_title">
        Id<?= $data['user']['id']; ?> |
        <a target="_blank" rel="noopener noreferrer" href="<?= url('profile', ['login' => $data['user']['login']]); ?>">
          <?= $data['user']['login']; ?>
        </a>
      </label>
      <?php if ($data['user']['trust_level'] != UserData::REGISTERED_ADMIN) : ?>
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
      <i class="bi-eye"></i> <?= $data['user']['hits_count']; ?>
    </fieldset>
    <fieldset>
      <label for="post_title"><?= __('admin.registration'); ?></label>
      <?= $data['user']['created_at']; ?> |
      <?= $data['user']['reg_ip']; ?>
      <?php if ($data['user']['duplicat_ip_reg'] > 1) : ?>
        <sup class="red">(<?= $data['user']['duplicat_ip_reg']; ?>)</sup>
      <?php endif; ?>
      (ed. <?= $data['user']['updated_at']; ?>)
    </fieldset>
    <hr>
   <fieldset>
      <?php if ($data['user']['limiting_mode'] == 1) : ?>
        <span class="red"><?= __('admin.dumb_mode'); ?>!</span><br>
      <?php endif; ?>
      <label for="limiting_mode">
        <?= __('admin.dumb_mode'); ?>?
      </label>
      <input type="radio" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 0) : ?>checked<?php endif; ?> value="0"> <?= __('admin.no'); ?>
      <input type="radio" name="limiting_mode" <?php if ($data['user']['limiting_mode'] == 1) : ?>checked<?php endif; ?> value="1"> <?= __('admin.yes'); ?>
    </fieldset>
    
    <fieldset>
      <label for="scroll">
        <?= __('admin.scroll'); ?>
      </label>
      <input type="radio" name="scroll" <?php if ($data['user']['scroll'] == 0) : ?>checked<?php endif; ?> value="0"> <?= __('admin.no'); ?>
      <input type="radio" name="scroll" <?php if ($data['user']['scroll'] == 1) : ?>checked<?php endif; ?> value="1"> <?= __('admin.yes'); ?>
    </fieldset>

    <hr>
    <fieldset>
      <?php if ($data['count']['count_posts'] != 0) : ?>
        <label class="required"><?= __('admin.posts'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= __('admin.posts'); ?> <?= $data['user']['login']; ?>" href="<?= url('profile.posts', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_posts']; ?>
        </a> <br>
      <?php endif; ?>
      <?php if ($data['count']['count_answers'] != 0) : ?>
        <label class="required"><?= __('admin.answers'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= __('admin.answers'); ?> <?= $data['user']['login']; ?>" href="<?= url('profile.answers', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_answers']; ?>
        </a> <br>
      <?php else : ?>
        ---
      <?php endif; ?>
      <?php if ($data['count']['count_comments'] != 0) : ?>
        <label class="required"><?= __('admin.comments'); ?>:</label>
        <a target="_blank" rel="noopener noreferrer" title="<?= __('admin.comments'); ?> <?= $data['user']['login']; ?>" href="<?= url('profile.comments', ['login' => $data['user']['login']]); ?>">
          <?= $data['count']['count_comments']; ?>
        </a> <br>
      <?php else : ?>
        ---
      <?php endif; ?>
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
              <span class="remove-badge text-sm lowercase" data-id="<?= $badge['bu_id']; ?>" data-uid="<?= $data['user']['id']; ?>">
                - <?= __('admin.remove'); ?>
              </span>
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
      <label for="activated"><?= __('admin.activated'); ?>?</label>
      <input type="radio" name="activated" <?php if ($data['user']['activated'] == 0) : ?>checked<?php endif; ?> value="0"> <?= __('admin.no'); ?>
      <input type="radio" name="activated" <?php if ($data['user']['activated'] == 1) : ?>checked<?php endif; ?> value="1"> <?= __('admin.yes'); ?>
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
      <label for="login"><?= __('admin.nickname'); ?>: /u/**<sup class="red">*</sup></label>
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
    
<?php
$setting = [
  [
    'url'       => 'website',
    'addition'  => false,
    'title'     => 'website',
    'lang'      => __('app.url'),
    'help'      => 'https://site.ru',
    'name'      => 'website'
  ], [
    'url'       => false,
    'addition'  => false,
    'title'     => 'location',
    'lang'      => __('app.city'),
    'help'      => __('app.for_example') . ': Moscow',
    'name'      => 'location'
  ], [
    'url'       => 'public_email',
    'addition'  => 'mailto:',
    'title'     => 'public_email',
    'lang'      => 'Email',
    'help'      => '**@**.ru',
    'name'      => 'public_email'
  ], [
    'url'       => 'skype',
    'addition'  => 'skype:',
    'title'     => 'skype',
    'lang'      => 'Skype',
    'help'      => 'skype:<b>NICK</b>',
    'name'      => 'skype'
  ], [
    'url'       => 'telegram',
    'addition'  => 'tg://resolve?domain=',
    'title'     => 'telegram',
    'lang'      => 'Telegram',
    'help'      => 'tg://resolve?domain=<b>NICK</b>',
    'name'      => 'telegram'
  ], [
    'url'       => 'vk',
    'addition'  => 'https://vk.com/',
    'title'     => 'vk',
    'lang'      => 'Vk',
    'help'      => 'https://vk.com/<b>NICK / id</b>',
    'name'      => 'vk'
  ],
];

?>

<?php foreach ($setting as $block) : ?>
  <fieldset class="max-w300">
    <label for="post_title"><?= $block['lang']; ?></label>
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
</div>
</main>

<?= includeTemplate('/view/default/footer'); ?>