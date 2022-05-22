<main class="col-two">
  <div class="box-flex ml10">
    <ul class="nav">
      <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
    </ul>
  </div>

  <?= insert('/content/post/post', ['data' => $data]); ?>

  <?php if (UserData::getUserScroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php else : ?>
    <div class="mb15">
      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
    </div>
  <?php endif; ?>
</main>

<aside>
  <?php if (!UserData::checkActiveUser()) : ?>
    <?= insert('/_block/sidebar/login'); ?>
  <?php endif; ?>

  <?php if (UserData::checkActiveUser() && !empty($data['topics_user'])) : ?>
    <div class="box bg-violet">
      <h3 class="uppercase-box"><?= __('app.reading'); ?></h3>
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
                <i class="bi-plus-lg text-sm"></i>
              </a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php if (count($data['topics_user']) > config('facets.quantity_home')) : ?>
        <a class="gray-600 block mt5" title="<?= __('app.topics'); ?>" href="<?= url('topics.my'); ?>">
          <?= __('app.see_more'); ?> <i class="bi-chevron-double-right middle"></i>
        </a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  
  <?php if (is_array($data['topics'])) : ?>
    <div class="box bg-violet">
      <h3 class="uppercase-box"><?= __('app.recommended'); ?></h3>
      <?php foreach ($data['topics'] as $key => $recomm) : ?>
        <a class="flex items-center relative pb10 gray-600" href="<?= url('topic', ['slug' => $recomm['facet_slug']]); ?>">
          <?= Html::image($recomm['facet_img'], $recomm['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
          <?= $recomm['facet_title']; ?>
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
              <a title="<?= $answer['login']; ?>" href="<?= url('profile', ['login' => $answer['login']]); ?>">
                <?= Html::image($answer['avatar'], $answer['login'], 'img-sm', 'avatar', 'small'); ?>
              </a>
              <span class="middle lowercase gray-600"><?= Html::langDate($answer['answer_date']); ?></span>
              <a class="last-content_telo" href="<?= url('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
                <?= Html::fragment(Content::text($answer['answer_content'], 'line'), 98); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</aside>