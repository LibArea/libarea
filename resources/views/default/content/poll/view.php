<main>
  <div class="flex justify-between mb20">
    <ul class="nav">
      <li><a href="<?= url('polls'); ?>"><?= __('app.all'); ?></a></li>
      <li class="active"><?= __('app.poll'); ?></li>
    </ul>
    <div><a class="btn btn-outline-primary btn-small" href="<?= url('content.add', ['type' => 'poll']) ?>">+ <?= __('app.add_poll'); ?></a></div>
  </div>
  <div class="content-body">
    <h2 class="title"><?= $data['question']['title']; ?></h2>

    <?php foreach ($data['answers'] as $answer) : ?>
      <style nonce="<?= $_SERVER['nonce']; ?>">
        .bg<?= $answer['id']; ?> {
          width: <?= ($answer['votes'] / $data['count']) * 100; ?>%;
          height: 20px;
          background: #4cc70e;
        }
      </style>
      <div data-id="<?= $data['question']['id']; ?>" data-answer="<?= $answer['id']; ?>" class="add-poll mb10">
        <div class="small bg<?= $answer['id']; ?>"><?php if ($answer['votes']) : ?><?= ($answer['votes'] / $data['count']) * 100; ?>%<?php endif; ?></div>
        <?= $answer['answer']; ?> <?php if ($answer['votes']) : ?>(<?= $answer['votes']; ?>) <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <div class="gray-600 mt15"><?= $data['question']['add_date']; ?>. Код: <code>{poll:<?= $data['question']['id']; ?>}</code></div>
  </div>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('app.being_developed'); ?>...
  </div>
</aside>