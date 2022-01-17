<main class="col-span-9 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-rd5 p15 mb15">
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
            'auth'  => true,
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
      <div class="text-xm gray-500 p5 dark-gray-300 center"><?= Translate::get($data['sheet'] . '.info'); ?></div>
    </div>
  </div>

  <?= Tpl::import('/_block/post', ['data' => $data, 'user' => $user]); ?>

  <div class="mt10 mb15">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </div>
</main>

<aside class="col-span-3 mb-col-12 relative mb-none">
  <?php if ($user['id'] == 0) { ?>
    <?= Tpl::import('/_block/sidebar/login'); ?>
  <?php } ?>

  <?php if ($user['id'] > 0 && !empty($data['topics_user'])) { ?>
    <div class="br-box-gray box-shadow-all p15 mb15 br-rd5 bg-white text-sm">
      <div class="uppercase gray mb5 dark-gray-300">
        <?= Translate::get('reading'); ?>
      </div>

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
        <div class="flex relative pt5 pb5 items-center justify-between hidden">
          <a class="gray-600" href="<?= $url; ?>">
            <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w30 h30 mr5'); ?>
            <span class="ml5 middle dark-gray-300"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
          </a>
          <?php if ($user['id'] == $topic['facet_user_id']) { ?>
            <a class="right sky-500" title="<?= Translate::get('add post'); ?>" href="<?= getUrlByName('post.add'); ?>/<?= $topic['facet_id']; ?>">
              <i class="bi bi-plus-lg text-sm"></i>
            </a>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if (count($data['topics_user']) > Config::get('facets.quantity_home')) { ?>
        <a class="gray block dark-gray-300 mt5" title="<?= Translate::get('topics'); ?>" href="<?= getUrlByName('topics.my'); ?>">
          <?= Translate::get('see more'); ?> <i class="bi bi-chevron-double-right middle"></i>
        </a>
      <?php } ?>
    </div>
  <?php } else { ?>
    <div class="br-box-gray box-shadow-all p15 mb15 br-rd5 bg-white text-sm">
      <div class="uppercase gray mt5 mb5">
        <?= Translate::get('topics'); ?>
      </div>
      <?php foreach (Config::get('facets.default') as $key => $topic) { ?>
        <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= $topic['url']; ?>">
          <img class="w30 h30 mr5 br-box-gray" src="<?= $topic['img']; ?>" alt="<?= $topic['name']; ?>">
          <span class="ml5"><?= $topic['name']; ?></span>
        </a>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="sticky top20">
    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm br-box-gray box-shadow-all p5 pr15 pb5 pl15 bg-white br-rd5">
        <?php foreach ($data['latest_answers'] as $answer) { ?>
          <div class="mt15 mr0 mb15 ml0">
            <div class="text-sm gray-400">
              <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'w20 h20 br-rd-50 mr5'); ?>
              <span class="middle"><?= $answer['answer_date']; ?></span>
            </div>
            <a class="black dark-gray-300" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
              <?= $answer['answer_content']; ?>...
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= Tpl::import('/_block/sidebar/lang'); ?>
  </div>
</aside>
</div>