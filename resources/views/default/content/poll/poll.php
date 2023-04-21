<?php if ($poll['question']) : ?>
  <div class="content-body">
    <h2 class="title">
      <?= $poll['question']['poll_title']; ?>
      <?php if (UserData::checkAdmin()) : ?>
        <sup><a href="<?= url('content.edit', ['type' => 'poll', 'id' => $poll['question']['poll_id']]) ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a></sup>
      <?php endif; ?>
    </h2>

    <?php foreach ($poll['answers'] as $value) :
      $count = $poll['count'] ? $poll['count'] : 1;
      $num = $value['answer_votes'] / $count;
    ?>

      <?php if ($poll['isVote']) : ?>
        <div class="mb10 max-w780">
          <div class="poll-count">
            <strong><?= round($num * 100, 1); ?>%</strong>
            <div><?= $value['answer_votes']; ?></div>
          </div>
          <div class="poll-result">
            <div class="poll-label"><?= $value['answer_title']; ?>
              <?php if ($poll['isVote']['vote_answer_id'] == $value['answer_id']) : ?>
                <svg class="icons red right">
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
        <div data-id="<?= $poll['question']['poll_id']; ?>" data-answer="<?= $value['answer_id']; ?>" class="add-poll mb10 max-w780 gray">
          <label><input type="checkbox"><?= $value['answer_title']; ?></label>
        </div>
      <?php endif; ?>

    <?php endforeach; ?>

    <div class="gray-600 text-sm mt15">
      <?= $poll['question']['poll_date']; ?>
      <?php if ($poll['isVote']) : ?> | <?= $poll['isVote']['vote_date']; ?><?php endif; ?>
    </div>
  </div>
<?php endif; ?>