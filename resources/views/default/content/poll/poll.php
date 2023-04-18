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

  <style nonce="<?= $_SERVER['nonce']; ?>">
    .bg<?= $value['answer_id']; ?> {
      width: <?= $num  * 100; ?>%;
      min-width: 17px;
      height: 20px;
      background: #4cc70e;
    }
  </style>
  <?php if ($poll['isVote']) : ?>
    <div class="mb10 max-w780">
      <?= $value['answer_title']; ?>
      <div class="flex justify-between white text-sm pr5 pl5 bg<?= $value['answer_id']; ?>">
        <?php if ($value['answer_votes']) : ?><?= $num * 100; ?>%<?php endif; ?>
        <span class="white"><?= $value['answer_votes']; ?></span>
      </div>
    </div>
  <?php else : ?>
    <div data-id="<?= $poll['question']['poll_id']; ?>" data-answer="<?= $value['answer_id']; ?>" class="add-poll mb10 max-w780">
      <?= $value['answer_title']; ?>
      <div class="white text-sm pr5 pl5 bg<?= $value['answer_id']; ?>"></div>
    </div>
  <?php endif; ?>

<?php endforeach; ?>

<div class="gray-600 text-sm mt15"><?= $poll['question']['poll_date']; ?></div>
</div>