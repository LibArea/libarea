<main>
  <div class="box">
    <div class="flex justify-between mb20">
      <ul class="nav">
        <li class="active"><?= __('app.polls'); ?></li>
      </ul>
      <div><a class="btn btn-outline-primary btn-small" href="<?= url('poll.form.add') ?>">+ <?= __('app.add_poll'); ?></a></div>
    </div>
    <?php if (!empty($data['polls'])) : ?>

      <?php foreach ($data['polls'] as $value) : ?>
        <div class="content-body">
          <a class="title" href="<?= url('poll', ['id' => $value['poll_id']]); ?>"><?= $value['poll_title']; ?></a>
          <div class="gray-600"><?= $value['poll_date']; ?></div>
        </div>
      <?php endforeach; ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url('polls')); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('help.poll_info'); ?>
  </div>
</aside>