<main>
  <div class="flex justify-between mb20">
    <ul class="nav">
      <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
    </ul>
  </div>

  <?= insert('/content/post/post', ['data' => $data]); ?>
  <?php if (UserData::getUserScroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php else : ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  <?php endif; ?>
</main>

<aside>
  <?php if (!UserData::checkActiveUser()) : ?>
    <?= insert('/_block/sidebar/login'); ?>
  <?php endif; ?>

  <?php if (UserData::checkActiveUser() && !empty($data['topics_user'])) : ?>
    <div class="box bg-lightgray">
      <h3 class="uppercase-box"><?= __('app.reading'); ?>
        <?php if (count($data['topics_user']) > config('facets.quantity_home')) : ?>
          <a class="gray-600 text-sm" title="<?= __('app.topics'); ?>" href="<?= url('topics.my'); ?>">...</a>
        <?php endif; ?>
      </h3>
      <ul>
        <?php
        $my = [];
        $other = [];
        foreach ($data['topics_user'] as $topic) :
          if ($topic['facet_user_id'] == UserData::getUserId()) :
            $my[] = $topic;
          else :
            $other[] = $topic;
          endif;
        endforeach;
        $topics = array_merge($my, $other);
        $n = 0;
        foreach ($topics as $key => $topic) :
          $n++;
          if ($n > config('facets.quantity_home')) break;
          $url = url('topic', ['slug' => $topic['facet_slug']]);
          $blog = '';
          if ($topic['facet_type'] == 'blog') :
            $blog = '<sup class="red">b</span>';
            $url = url('blog', ['slug' => $topic['facet_slug']]);
          endif;
        ?>
          <li class="mt15 flex items-center justify-between">
            <a href="<?= $url; ?>">
              <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
              <span class="middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
            </a>
            <?php if (UserData::getUserId() == $topic['facet_user_id']) : ?>
              <a class="gray-600 bg-white mt5" title="<?= __('app.add_post'); ?>" href="<?= url('content.add', ['type' => 'post']); ?>/<?= $topic['facet_id']; ?>">
                <svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#plus"></use>
                </svg>
              </a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (is_array($data['topics'])) : ?>
    <?php if (count($data['topics']) > 0) : ?>
      <div class="box bg-lightgray">
        <h3 class="uppercase-box"><?= __('app.recommended'); ?></h3>
        <ul>
          <?php foreach ($data['topics'] as $recomm) : ?>
            <li class="mt15">
              <a href="<?= url('topic', ['slug' => $recomm['facet_slug']]); ?>">
                <?= Html::image($recomm['facet_img'], $recomm['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
                <?= $recomm['facet_title']; ?>
              </a>
              <?php if (UserData::getUserId()) : ?>
                <div data-id="<?= $recomm['facet_id']; ?>" data-type="facet" class="focus-id right inline text-sm red center mt5 mr5">
                  <?= __('app.read'); ?>
                </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (is_array($data['items'])) : ?>
      <div class="box bg-lightgray">
        <h3 class="uppercase-box"><?= __('app.websites'); ?></h3>
        <ul>
          <?php foreach ($data['items'] as $item) : ?>
            <li class="mt15">
              <a href="<?= url('website', ['slug' => $item['item_domain']]); ?>">
                <?= $item['item_title']; ?>  <span class="green"><?= $item['item_domain']; ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
  <?php endif; ?>

  <div class="sticky top-sm">
    <?php if (!empty($data['latest_answers'])) : ?>
      <div class="p15">
        <h3 class="uppercase-box"><?= __('app.answers'); ?></h3>
        <ul class="last-content">
          <?php foreach ($data['latest_answers'] as $answer) : ?>
            <li>
              <a title="<?= $answer['login']; ?>" href="<?= url('profile', ['login' => $answer['login']]); ?>">
                <?= Html::image($answer['avatar'], $answer['login'], 'img-sm mr5', 'avatar', 'small'); ?>
              </a>
              <span class="middle lowercase gray-600"><?= Html::langDate($answer['answer_date']); ?></span>
              <a class="last-content_telo" href="<?= url('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
                <?= Content::fragment(Content::text($answer['answer_content'], 'line'), 98); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</aside>