<main>

  <ul class="nav">
    <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
  </ul>

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
    <div class="box bg-violet">
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
          <li class="mb20">
            <a href="<?= $url; ?>">
              <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
              <span class="middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
            </a>
            <?php if (UserData::getUserId() == $topic['facet_user_id']) : ?>
              <a class="right gray-600 mt5" title="<?= __('app.add_post'); ?>" href="<?= url('content.add', ['type' => 'post']); ?>/<?= $topic['facet_id']; ?>">
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
          <?php foreach ($data['topics'] as $key => $recomm) : ?>
            <li class="mb20">
              <a href="<?= url('topic', ['slug' => $recomm['facet_slug']]); ?>">
                <?= Html::image($recomm['facet_img'], $recomm['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
                <?= $recomm['facet_title']; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <div class="sticky top-sm">
    <?= insert('/_block/sidebar/latest-answers-tabs', ['latest_answers' => $data['latest_answers']]); ?>
  </div>
</aside>