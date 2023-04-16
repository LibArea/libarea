<main>
  <h2 class="title"><?= __('app.polls'); ?></h2>
  <?php if (!empty($data['polls'])) : ?>

    <?php foreach ($data['polls'] as $poll) : ?>
      <div class="content-body">
        <a class="title" href="#"><?= $poll['question']; ?></a>
        <div class="gray-600"><?= $poll['add_date']; ?></div>
      </div>
    <?php endforeach; ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url('polls')); ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('help.poll_info'); ?>
  </div>
</aside>