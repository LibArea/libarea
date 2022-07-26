<?php if (!empty($latest_answers)) : ?>
  <div class="p15">
    <div class="mb15">
      <ul class="nav small">
        <li class="active">
          <a class="gray" href="#"><span><?= __('app.answers'); ?></span></a>
        </li>
        <!--li>
          <a class="gray" href="#"><span>'app.comments'</span></a>
        </li-->
      </ul>
    </div>
    <ul class="last-content">
      <?php foreach ($latest_answers as $answer) : ?>
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