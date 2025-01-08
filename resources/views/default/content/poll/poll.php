<?php
$answer_votes = 0;
$count = $poll['count'] ? $poll['count'] : 1;
?>

<?php if ($poll['question']) : ?>
  <div class="poll">
    <h2 class="title mb10">
      <?= $poll['question']['poll_title']; ?>
      <?php if ($container->user()->admin()) : ?>
        <sup><a href="<?= url('poll.form.edit', ['id' => $poll['question']['poll_id']]) ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a></sup>
      <?php endif; ?>
      <?php if ($poll['question']['poll_is_closed']) : ?>
        <svg class="icon green">
          <use xlink:href="/assets/svg/icons.svg#lock"></use>
        </svg>
      <?php endif; ?>
    </h2>

    <?php if ($container->user()->active()) : ?>

      <?php
      foreach ($poll['answers'] as $value) :
        $num = $value['answer_votes'] / $count;
        $answer_votes += $value['answer_votes'];
      ?>

        <?php if ($poll['isVote'] || $poll['question']['poll_is_closed'] == 1) : ?>
          <div class="mb10 max-w-md">
            <div class="poll-count">
              <strong><?= round($num * 100, 1); ?>%</strong>
              <div><?= $value['answer_votes']; ?></div>
            </div>
            <div class="poll-result">
              <div class="poll-label"><?= $value['answer_title']; ?>
                <?php if ($poll['isVote']['vote_answer_id'] == $value['answer_id']) : ?>
                  <svg class="icon red right">
                    <use xlink:href="/assets/svg/icons.svg#selected"></use>
                  </svg>
                <?php endif; ?>
              </div>
              <progress class="progress" value="<?= ceil($num * 100); ?>" max="100">
                <?= $num * 100; ?>%
              </progress>
            </div>
          </div>
        <?php else : ?>
          <div data-id="<?= $poll['question']['poll_id']; ?>" data-answer="<?= $value['answer_id']; ?>" class="add-poll mb10 max-w-md gray">
            <label><input type="checkbox"><?= $value['answer_title']; ?></label>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>

    <?php else : ?>

      <?php foreach ($poll['answers'] as $value) :
        $num = $value['answer_votes'] / $count;
        $answer_votes += $value['answer_votes'];
      ?>
        <div class="mb10 max-w-md">
          <div class="poll-count">
            <strong><?= round($num * 100, 1); ?>%</strong>
            <div><?= $value['answer_votes']; ?></div>
          </div>
          <div class="poll-result">
            <div class="poll-label"><?= $value['answer_title']; ?>
              <?php if ($poll['isVote']['vote_answer_id'] ?? false == $value['answer_id']) : ?>
                <svg class="icon red right">
                  <use xlink:href="/assets/svg/icons.svg#selected"></use>
                </svg>
              <?php endif; ?>
            </div>
            <progress class="progress" value="<?= ceil($num * 100); ?>" max="100">
              <?= $num * 100; ?>%
            </progress>
          </div>
        </div>
      <?php endforeach; ?>

    <?php endif; ?>

    <div class="gray-600 text-sm mt15">
      <?= __('app.total_votes'); ?>: <?= $answer_votes; ?> • <span class="lowercase"><?= langDate($poll['question']['poll_date']); ?></span>
    </div>
  </div>
<?php endif; ?>