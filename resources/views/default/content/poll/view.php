<main>
  <div class="flex justify-between mb20">
    <ul class="nav">
      <li><a href="<?= url('polls'); ?>"><?= __('app.all'); ?></a></li>
      <li class="active"><?= __('app.poll'); ?></li>
    </ul>
    <div><a class="btn btn-outline-primary btn-small" href="<?= url('content.add', ['type' => 'poll']) ?>">+ <?= __('app.add_poll'); ?></a></div>
  </div>
  <div class="content-body">
    <h2 class="title">
      <?= $data['question']['poll_title']; ?>
      <sup><a href="<?= url('content.edit', ['type' => 'poll', 'id' => $data['question']['poll_id']]) ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg>
        </a></sup>
    </h2>

    <?php foreach ($data['answers'] as $value) :
      $count = $data['count'] ? $data['count'] : 1;
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
      <?php if ($data['isVote']) : ?>
        <div class="mb10 max-w780">
          <?= $value['answer_title']; ?>
          <div class="flex justify-between white text-sm pr5 pl5 bg<?= $value['answer_id']; ?>">
            <?php if ($value['answer_votes']) : ?><?= $num * 100; ?>%<?php endif; ?>
            <span class="white"><?= $value['answer_votes']; ?></span>
          </div>
        </div>
      <?php else : ?>
        <div data-id="<?= $data['question']['poll_id']; ?>" data-answer="<?= $value['answer_id']; ?>" class="add-poll mb10 max-w780">
          <?= $value['answer_title']; ?>
          <div class="white text-sm pr5 pl5 bg<?= $value['answer_id']; ?>"></div>
        </div>
      <?php endif; ?>

    <?php endforeach; ?>

    <div class="gray-600 mt15"><?= $data['question']['poll_date']; ?>. Код: <code>{poll:<?= $data['question']['poll_id']; ?>}</code></div>

  </div>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('app.being_developed'); ?>...
  </div>
</aside>