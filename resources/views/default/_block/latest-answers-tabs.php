<?php if (!empty($latest_answers)) : ?>
  <div class="p15 wrapper">
    <div class="mb15">
      <ul class="nav small">
        <li class="tab-button active" data-id="home">
          <?= __('app.answers'); ?>
        </li>
        <li class="tab-button" data-id="more_comment">
          <?= __('app.comments'); ?>
        </li>
      </ul>
    </div>
    <ul class="last-content content-tabs active" id="home">
      <?php foreach ($latest_answers as $answer) : ?>
        <li>
          <div class="gray-600 text-sm">
            <a class="flex gray items-center" title="<?= $answer['login']; ?>" href="<?= url('profile', ['login' => $answer['login']]); ?>">
              <?= Img::avatar($answer['avatar'], $answer['login'], 'img-sm mr5', 'small'); ?>
              <span class="mr5"><?= $answer['login']; ?></span>
            </a>
            <span class="lowercase"><?= Html::langDate($answer['answer_date']); ?></span>
          </div>
          <a class="last-content_telo" href="<?= url('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
            <?= Content::fragment(Content::text($answer['answer_content'], 'line'), 98); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <ul class="last-content content-tabs more_go" id="more_comment">
    </ul>
  </div>
<?php endif; ?>