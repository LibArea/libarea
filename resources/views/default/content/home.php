<?php if ($user['id'] == 0) { ?>
  <box class="col-span-12 bg-white br-box-gray center">
    <h1><?= Config::get('meta.banner_title'); ?></h1>
    <div class="mb5"><?= Config::get('meta.banner_desc'); ?>...</div>
  </box>
<?php } ?>

<div class="col-span-2 justify-between mb-none">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $user,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="box-flex bg-white p15">
    <ul class="flex flex-row list-none m0 p0 center">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => $data['type'] . '.feed',
            'url'   => '/',
            'title' => Translate::get('feed'),
            'icon'  => 'bi bi-sort-down'
          ],
          [
            'tl'    => 1,
            'id'    => $data['type'] . '.all',
            'url'   => getUrlByName('main.all'),
            'title' => Translate::get('all'),
            'icon'  => 'bi bi-app'
          ],
          [
            'id'    => $data['type'] . '.top',
            'url'   => getUrlByName('main.top'),
            'title' => Translate::get('top'),
            'icon'  => 'bi bi-bar-chart'
          ],
        ]
      ); ?>

    </ul>
    <div data-template="feed" class="tippy gray-400">
       <i class="bi bi-info-square"></i>
    </div>
    <div id="feed" style="display: none;">
      <div class="text-xm gray-600 p5 center"><?= Translate::get($data['sheet'] . '.info'); ?></div>
    </div>
  </div>

  <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>

  <div class="mb15">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </div>
</main>

<aside class="col-span-3 mb-col-12 relative mb-none">
  <?php if ($user['id'] == 0) { ?>
    <?= Tpl::import('/_block/sidebar/login'); ?>
  <?php } ?>

  <?php if ($user['id'] > 0 && !empty($data['topics_user'])) { ?>
    <box class="br-box-gray bg-white text-sm">
      <h3 class="uppercase-box"><?= Translate::get('reading'); ?></h3>
      <ul class="p0 m0 list-none text-sm">
      <?php
      $my = [];
      $other = [];
      foreach ($data['topics_user'] as $topic) {
        if ($topic['facet_user_id'] == $user['id']) {
          $my[] = $topic;
        } else {
          $other[] = $topic;
        }
      }
      $topics = array_merge($my, $other);
      $n = 0;
      foreach ($topics as $key => $topic) {
        $n++;
        if ($n > Config::get('facets.quantity_home')) break;
        $url = getUrlByName('topic', ['slug' => $topic['facet_slug']]);
        $blog = '';
        if ($topic['facet_type'] == 'blog') {
          $blog = '<sup class="red-500">b</span>';
          $url = getUrlByName('blog', ['slug' => $topic['facet_slug']]);
        }
      ?>
        <li class="mb10">
          <a href="<?= $url; ?>">
            <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w30 h30 mr5'); ?>
            <span class="ml5 middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
          </a>
          <?php if ($user['id'] == $topic['facet_user_id']) { ?>
            <a class="right gray-400 mt5" title="<?= sprintf(Translate::get('add.option'), Translate::get('post')); ?>" href="<?= getUrlByName('post.add'); ?>/<?= $topic['facet_id']; ?>">
              <i class="bi bi-plus-lg text-sm"></i>
            </a>
          <?php } ?>
        </li>
      <?php } ?>
      </ul>
      <?php if (count($data['topics_user']) > Config::get('facets.quantity_home')) { ?>
        <a class="gray-400 block mt5" title="<?= Translate::get('topics'); ?>" href="<?= getUrlByName('topics.my'); ?>">
          <?= Translate::get('see more'); ?> <i class="bi bi-chevron-double-right middle"></i>
        </a>
      <?php } ?>
    </box>
  <?php } else { ?>
    <box class="br-box-gray bg-white text-sm">
      <div class="uppercase gray mt5 mb5">
        <?= Translate::get('topics'); ?>
      </div>
      <?php foreach (Config::get('facets.default') as $key => $topic) { ?>
        <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= $topic['url']; ?>">
          <img class="w30 h30 mr5 br-box-gray" src="<?= $topic['img']; ?>" alt="<?= $topic['name']; ?>">
          <span class="ml5"><?= $topic['name']; ?></span>
        </a>
      <?php } ?>
    </box>
  <?php } ?>

  <div class="sticky top70">
    <?php if (!empty($data['latest_answers'])) { ?>
      <box class="br-box-gray bg-white">
        <?php foreach ($data['latest_answers'] as $answer) { ?>
          <div class="mb15 text-sm gray-400">
            <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'w20 h20 br-rd-50 mr5'); ?>
            <span class="middle lowercase"><?= $answer['answer_date']; ?></span>
            <a class="black block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
              <?= $answer['answer_content']; ?>...
            </a>
          </div>
        <?php } ?>
      </box>
    <?php } ?>

    <?= Tpl::import('/_block/sidebar/lang', ['lang' => []]); ?>
  </div>
</aside>
</container>