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
      <svg class="icon">
        <use xlink:href="/assets/svg/icons.svg#calendar"></use>
      </svg>
      <span class="middle lowercase text-sm">
        <?= langDate($profile['created_at']); ?>
      </span>
    </div>

    <?php foreach (config('profile', 'sidebar') as $block) : ?>
      <?php if ($profile[$block['title']]) : ?>
        <div class="mt5">
          <?php if ($block['url']) : ?>
		    <?php if ($counts > 3) : ?>
			  <span class="gray-600"><?= __($block['lang']); ?>:</span>
              <a href="<?php if ($block['addition']) : ?><?= $block['addition']; ?><?php endif; ?><?= $profile[$block['url']]; ?>" rel="noopener nofollow ugc">
                <span class="mr5 ml5"><?= $profile[$block['title']]; ?></span>
              </a>
			<?php endif; ?>
          <?php else : ?>
		    <span class="gray-600"><?= __($block['lang']); ?>:</span>
            <span class="mr5 ml5"><?= $profile[$block['title']]; ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <?php if ($data['blogs']) : ?>
    <div class="box">
      <h4 class="uppercase-box"><?= __('app.created_by'); ?></h4>
      <?php foreach ($data['blogs'] as $blog) : ?>
        <div class="w-100 mb15 flex flex-row">
          <a class="mr10" href="<?= url($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
            <?= Img::image($blog['facet_img'], $blog['facet_title'], 'img-lg', 'logo', 'max'); ?>
          </a>
          <div class="ml5 w-100">
            <a class="black" href="<?= url($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
              <?= $blog['facet_title']; ?>
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
            <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'small'); ?>
            <span class="bar-name text-sm"><?= $topic['facet_title']; ?></span>
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
            <?= $part['facet_title']; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="box br-lightgray">
    <h4 class="uppercase-box"><?= __('app.badges'); ?></h4>
    <div class="text-3xl">
      <span title="<?= __('app.medal_reg'); ?>"><svg class="icon sky">
          <use xlink:href="/assets/svg/icons.svg#gift"></use>
        </svg></span>
      <?php if ($profile['id'] < 50) : ?>
        <span title="<?= __('app.first_days'); ?>"><svg class="icon green">
            <use xlink:href="/assets/svg/icons.svg#award"></use>
          </svg></span>
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
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#settings"></use>
          </svg>
          <span class="middle"><?= __('app.edit'); ?></span>
        </a>
        <a class="gray block mb5" href="<?= url('admin.badges.user.add', ['id' => $profile['id']]); ?>">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#award"></use>
          </svg>
          <span class="middle"><?= __('app.reward_user'); ?></span>
        </a>
        <?php if ($profile['whisper']) : ?>
          <div class="tips text-sm gray-600">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#info"></use>
            </svg>
            <?= $profile['whisper']; ?>
          </div>
        <?php endif; ?>
        <?php if ($profile['trust_level'] != 10) : ?>
          <?php if ($profile['ban_list'] == 1) : ?>
            <span class="type-ban gray mb5 block" data-id="<?= $profile['id']; ?>" data-type="user">
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#user"></use>
              </svg>
              <span class="red text-sm"><?= __('app.unban'); ?></span>
            </span>
          <?php else : ?>
            <span class="type-ban text-sm gray mb5 block" data-id="<?= $profile['id']; ?>" data-type="user">
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#x-octagon"></use>
              </svg>
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