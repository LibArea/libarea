<?php $profile = $data['profile']; ?>

<div class="mb-none">
  <?= $profile['about']; ?>
  <div class="gray-600 mb15">
    <svg class="icons">
      <use xlink:href="/assets/svg/icons.svg#calendar"></use>
    </svg>
    <span class="middle lowercase text-sm">
      <?= Html::langDate($profile['created_at']); ?>
      <sup class="ml5"><?= __('app.tl' . $profile['trust_level'] . '.title'); ?></sup>
    </span>
  </div>

  <div class="box bg-lightgray">
    <h3 class="uppercase-box"><?= __('app.contacts'); ?></h3>
    <?php foreach (config('user/profile') as $block) : ?>
      <?php if ($profile[$block['title']]) : ?>
        <div class="mt5">
          <?= $block['lang']; ?>:
          <?php if ($block['url']) : ?>
            <a href="<?php if ($block['addition']) : ?><?= $block['addition']; ?><?php endif; ?><?= $profile[$block['url']]; ?>" rel="noopener nofollow ugc">
              <span class="mr5 ml5"><?= $profile[$block['title']]; ?></span>
            </a>
          <?php else : ?>
            <span class="mr5 ml5"><?= $profile[$block['title']]; ?></span>
          <?php endif; ?>
        </div>
      <?php else : ?>
        <?php if ('location' == $block['title']) : ?>
          <div class="mb20">
            <?= $block['lang']; ?>: ...
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <?php if ($data['blogs']) : ?>
    <div class="box bg-lightgray">
      <h3 class="uppercase-box"><?= __('app.created_by'); ?></h3>
      <?php foreach ($data['blogs'] as $blog) : ?>
        <div class="w-100 mb-w100 mb15 flex flex-row">
          <a class="mr10" href="<?= url($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
            <?= Html::image($blog['facet_img'], $blog['facet_title'], 'img-lg', 'logo', 'max'); ?>
          </a>
          <div class="ml5 w-100">
            <a class="black" href="<?= url($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
              <?= $blog['facet_title']; ?>
            </a>
            <div class="text-sm gray-600">
              <?= Content::fragment(Content::text($blog['facet_short_description'], 'line'), 68); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($data['topics']) : ?>
    <div class="box bg-lightgray">
      <h3 class="uppercase-box"><?= __('app.is_reading'); ?></h3>
      <?php foreach ($data['topics'] as  $topic) : ?>
        <div class="mt5 mb5">
          <a class="flex relative items-center pt5 pb5 hidden gray" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
            <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'small'); ?>
            <span class="bar-name text-sm"><?= $topic['facet_title']; ?></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($data['participation'][0]['facet_id'])) : ?>
    <div class="box bg-lightgray">
      <h3 class="uppercase-box"><?= __('app.understands'); ?></h3>
      <?php foreach ($data['participation'] as $part) : ?>
        <a class="tag" href="<?= url('topic', ['slug' => $part['facet_slug']]); ?>">
          <?= $part['facet_title']; ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="box bg-lightgray">
    <h3 class="uppercase-box"><?= __('app.badges'); ?></h3>
    <div class="text-3xl">
      <span title="<?= __('app.medal_reg'); ?>"><svg class="icons icon-base sky">
          <use xlink:href="/assets/svg/icons.svg#gift"></use>
        </svg></span>
      <?php if ($profile['id'] < 50) : ?>
        <span title="<?= __('app.first_days'); ?>"><svg class="icons icon-base green">
            <use xlink:href="/assets/svg/icons.svg#award"></use>
          </svg></span>
      <?php endif; ?>
      <?php foreach ($data['badges'] as $badge) : ?>
        <?= $badge['badge_icon']; ?>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if (UserData::checkAdmin()) : ?>
    <div class="box bg-lightgray">
      <h3 class="uppercase-box"><?= __('app.admin'); ?></h3>
      <div class="mt5">
        <?php if ($profile['trust_level'] != UserData::REGISTERED_ADMIN) : ?>
          <?php if ($profile['ban_list'] == 1) : ?>
            <span class="type-ban gray mb5 block" data-id="<?= $profile['id']; ?>" data-type="user">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#user"></use>
              </svg>
              <span class="red text-sm"><?= __('app.unban'); ?></span>
            </span>
          <?php else : ?>
            <span class="type-ban text-sm gray mb5 block" data-id="<?= $profile['id']; ?>" data-type="user">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#flag"></use>
              </svg>
              <?= __('app.ban_it'); ?>
            </span>
          <?php endif; ?>
        <?php endif; ?>
        <a class="gray mb5 block" href="<?= url('admin.user.edit', ['id' => $profile['id']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#settings"></use>
          </svg>
          <span class="middle"><?= __('app.edit'); ?></span>
        </a>
        <a class="gray block" href="<?= url('admin.badges.user.add', ['id' => $profile['id']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#award"></use>
          </svg>
          <span class="middle"><?= __('app.reward_user'); ?></span>
        </a>
        <?php if ($profile['whisper']) : ?>
          <div class="tips text-sm gray-600">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#info"></use>
            </svg>
            <?= $profile['whisper']; ?>
          </div>
        <?php endif; ?>
        <hr>
        <span class="gray">id<?= $profile['id']; ?> | Tl<?= $profile['trust_level']; ?> | <?= $profile['email']; ?></span>
      </div>
    </div>
  <?php endif; ?>
</div>