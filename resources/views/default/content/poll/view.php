<main>
  <div class="box">
    <div class="flex justify-between mb20">
      <ul class="nav">
        <li><a href="<?= url('polls'); ?>"><?= __('app.all'); ?></a></li>
        <li class="active"><?= __('app.poll'); ?></li>
      </ul>
      <div><a class="btn btn-outline-primary btn-small" href="<?= url('poll.form.add') ?>">+ <?= __('app.add_poll'); ?></a></div>
    </div>
    <?= insert('/content/poll/poll', ['poll' => $data['poll']]); ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('app.being_developed'); ?>...
  </div>
</aside>