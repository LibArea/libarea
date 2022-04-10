<main>
  <div class="box-flex-white relative">
    <ul class="nav">
       <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.home')]); ?>
    </ul>

    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= __($data['sheet'] . '.info'); ?></div>
  </div>

  <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  <?php if ($user['scroll'] == 0) { ?>
    <div class="mb15">
      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
    </div>
  <?php } else { ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php } ?>
</main>

<aside>
  <?php if ($user['id'] == 0) { ?>
    <?= Tpl::insert('/_block/sidebar/login', ['user' => $user]); ?>
  <?php } ?>

  <?php if ($user['id'] > 0 && !empty($data['topics_user'])) { ?>
    <div class="box-white">
      <h3 class="uppercase-box"><?= __('reading'); ?></h3>
      <ul>
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
            $blog = '<sup class="red">b</span>';
            $url = getUrlByName('blog', ['slug' => $topic['facet_slug']]);
          }
        ?>
          <li class="mb10">
            <a href="<?= $url; ?>">
              <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base', 'logo', 'max'); ?>
              <span class="middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
            </a>
            <?php if ($user['id'] == $topic['facet_user_id']) { ?>
              <a class="right gray-600 mt5" title="<?= sprintf(__('add.option'), __('post')); ?>" href="<?= getUrlByName('content.add', ['type' => 'post']); ?>/<?= $topic['facet_id']; ?>">
                <i class="bi-plus-lg text-sm"></i>
              </a>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
      <?php if (count($data['topics_user']) > Config::get('facets.quantity_home')) { ?>
        <a class="gray-600 block mt5" title="<?= __('topics'); ?>" href="<?= getUrlByName('topics.my'); ?>">
          <?= __('see more'); ?> <i class="bi-chevron-double-right middle"></i>
        </a>
      <?php } ?>
    </div>
  <?php } else { ?>
    <div class="box-white">
      <h3 class="uppercase-box"><?= __('topics'); ?></h3>
      <?php foreach (Config::get('facets.default') as $key => $topic) { ?>
        <a class="flex items-center relative pb10 gray-600" href="<?= $topic['url']; ?>">
          <img class="img-base" src="<?= $topic['img']; ?>" alt="<?= $topic['name']; ?>">
          <?= $topic['name']; ?>
        </a>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="sticky top-sm">
    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="box-white">
        <ul class="last-content">
          <?php foreach ($data['latest_answers'] as $answer) { ?>
            <li>
              <a title="<?= $answer['login']; ?>" href="<?= getUrlByName('profile', ['login' => $answer['login']]); ?>">
                <?= Html::image($answer['avatar'], $answer['login'], 'ava-sm', 'avatar', 'small'); ?>
              </a>
              <span class="middle lowercase gray-600"><?= Html::langDate($answer['answer_date']); ?></span>
              <a class="last-content_telo" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
                <?= Html::fragment(Content::text($answer['answer_content'], 'line'), 98); ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
  </div>
</aside>