<main class="col-two">
  <div class="box-flex ml10">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.home')]); ?>
    </ul>
  </div>

  <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>

  <div class="mt10 mb15">
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </div>
</main>

<aside>
  <?php if ($user['id'] == 0) : ?>
    <?= Tpl::insert('/_block/sidebar/login', ['user' => $user]); ?>
  <?php endif; ?>

  <?php if (UserData::checkActiveUser() && !empty($data['topics_user'])) : ?>
    <div class="box bg-violet text-sm">
      <h3 class="uppercase-box"><?= __('reading'); ?></h3>

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
      foreach ($topics as $key => $topic) :
        $n++;
        if ($n > Config::get('facets.quantity_home')) break;
        $url = getUrlByName('topic', ['slug' => $topic['facet_slug']]);
        $blog = '';
        if ($topic['facet_type'] == 'blog') {
          $blog = '<sup class="red">b</span>';
          $url = getUrlByName('blog', ['slug' => $topic['facet_slug']]);
        }
      ?>
        <div class="flex relative pt5 pb5 items-center justify-between hidden">
          <a class="gray-600" href="<?= $url; ?>">
            <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base', 'logo', 'max'); ?>
            <span class="ml5 middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
          </a>
          <?php if ($user['id'] == $topic['facet_user_id']) : ?>
            <a class="right gray-600 mt5" title="<?= sprintf(__('add.option'), __('post')); ?>" href="<?= getUrlByName('content.add', ['type' => 'post']); ?>/<?= $topic['facet_id']; ?>">
              <i class="bi-plus-lg text-sm"></i>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <?php if (count($data['topics_user']) > Config::get('facets.quantity_home')) : ?>
        <a class="gray block mt5" title="<?= __('topics'); ?>" href="<?= getUrlByName('topics.my'); ?>">
          <?= __('see more'); ?> <i class="bi-chevron-double-right middle"></i>
        </a>
      <?php endif; ?>
    </div>
  <?php else : ?>
    <div class="box bg-violet text-sm">
      <div class="uppercase gray mt5 mb5">
        <?= __('topics'); ?>
      </div>
      <?php foreach (Config::get('facets.default') as $key => $topic) : ?>
        <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= $topic['url']; ?>">
          <img class="img-base" src="<?= $topic['img']; ?>" alt="<?= $topic['name']; ?>">
          <span class="ml5"><?= $topic['name']; ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="sticky top0">
    <?php if (!empty($data['latest_answers'])) : ?>
      <div class="box bg-violet">
        <ul class="last-content">
          <?php foreach ($data['latest_answers'] as $answer) : ?>
            <li>
              <a title="<?= $answer['login']; ?>" href="<?= getUrlByName('profile', ['login' => $answer['login']]); ?>">
                <?= Html::image($answer['avatar'], $answer['login'], 'ava-sm', 'avatar', 'small'); ?>
              </a>
              <span class="middle lowercase gray-600"><?= Html::langDate($answer['answer_date']); ?></span>
              <a class="last-content_telo" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
                <?= Html::fragment(Content::text($answer['answer_content'], 'line'), 98); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</aside>