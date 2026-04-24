<?php 
  $profile = $data['profile']; 
  $counts = $data['counts']['count_posts'] + $data['counts']['count_comments'];
?>

<div class="mb-none">
  <?php if ($counts > 3) : ?>
    <div class="box blockquote-profile">
      <?php if ($profile['about'] == 'Riddle...') : ?>
        <?= __('app.riddle'); ?>...
      <?php else : ?>
	    <?= markdown($profile['about'] ??  __('app.riddle')); ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="box">
    <div class="gray-600 mt5">
	  <?= icon('icons', 'calendar'); ?>
      <span class="middle lowercase text-sm">
        <?= langDate($profile['created_at']); ?>
      </span>
    </div>

    <?php insert('/content/user/profile/profile-fields', ['profile' => $profile, 'counts' => $counts]); ?>
  </div>

  <?php if ($data['blogs']) : ?>
    <div class="box">
      <h4 class="uppercase-box"><?= __('app.created_by'); ?></h4>
      <?php foreach ($data['blogs'] as $blog) : ?>
        <div class="w-100 mb15 flex flex-row">
          <a class="mr10" href="<?= url($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
            <?= Img::image($blog['facet_img'], htmlEncode($blog['facet_title']), 'img-lg', 'logo', 'max'); ?>
          </a>
          <div class="ml5 w-100">
            <a class="black" href="<?= url($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
              <?= htmlEncode($blog['facet_title']); ?>
            </a>
            <div class="text-sm gray-600">
              <?= fragment($blog['facet_short_description'], 68); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($data['topics']) : ?>
    <div class="box">
      <h4 class="uppercase-box"><?= __('app.is_reading'); ?></h4>
      <?php foreach ($data['topics'] as  $topic) : ?>
        <div class="mt5 mb10">
          <a class="flex relative items-center hidden gray" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
            <?= Img::image($topic['facet_img'], htmlEncode($topic['facet_title']), 'img-base mr5', 'logo', 'small'); ?>
            <span class="bar-name text-sm"><?= htmlEncode($topic['facet_title']); ?></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($data['participation'][0]['facet_id'])) : ?>
    <div class="box br-lightgray">
      <h4 class="uppercase-box"><?= __('app.understands'); ?></h4>
      <div class="flex flex-wrap gap lowercase">
        <?php foreach ($data['participation'] as $part) : ?>
          <a class="gray-600 text-sm" href="<?= url('topic', ['slug' => $part['facet_slug']]); ?>">
            <?= htmlEncode($part['facet_title']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="box br-lightgray">
    <h4 class="uppercase-box"><?= __('app.badges'); ?></h4>
    <div class="text-3xl">
      <span title="<?= __('app.medal_reg'); ?>">
	    <?= icon('icons', 'gift', 24, 'icon sky'); ?>
      </span>
      <?php if ($profile['id'] < 50) : ?>
        <span title="<?= __('app.first_days'); ?>">
		  <?= icon('icons', 'lock', 24, 'icon green'); ?>
		 </span>
      <?php endif; ?>
      <?php foreach ($data['badges'] as $badge) : ?>
        <?= $badge['badge_icon']; ?>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if ($container->user()->admin()) : ?>
    <div class="box bg-yellow">
      <h4 class="uppercase-box"><?= __('app.admin'); ?></h4>
      <div class="mt5">
        <a class="gray mb5 block" href="<?= url('admin.user.edit.form', ['id' => $profile['id']]); ?>">
		  <?= icon('icons', 'settings'); ?>
          <span class="middle"><?= __('app.edit'); ?></span>
        </a>
        <a class="gray block mb5" href="<?= url('admin.badges.user.add', ['id' => $profile['id']]); ?>">
		  <?= icon('icons', 'award'); ?>
          <span class="middle"><?= __('app.reward_user'); ?></span>
        </a>
        <?php if ($profile['whisper']) : ?>
          <div class="tips text-sm gray-600">
		    <?= icon('icons', 'info'); ?>
            <?= $profile['whisper']; ?>
          </div>
        <?php endif; ?>
        <?php if ($profile['trust_level'] != 10) : ?>
          <?php if ($profile['ban_list'] == 1) : ?>
            <span class="type-ban gray mb5 block" data-id="<?= $profile['id']; ?>" data-type="user">
			  <?= icon('icons', 'user'); ?>
              <span class="red text-sm"><?= __('app.unban'); ?></span>
            </span>
          <?php else : ?>
            <span class="type-ban text-sm gray mb5 block" data-id="<?= $profile['id']; ?>" data-type="user">
			  <?= icon('icons', 'x-octagon'); ?>
              <span class="middle"><?= __('app.ban_it'); ?></span>
            </span>
          <?php endif; ?>
        <?php endif; ?>
        <hr>
        <span class="gray">id<?= $profile['id']; ?> | Tl<?= $profile['trust_level']; ?> | <?= $profile['email']; ?></span>
      </div>
    </div>
  <?php endif; ?>
</div>