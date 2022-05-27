<?php $team = $data['team']; ?>

<main>
  <div class="box">
    <a href="<?= url('teams'); ?>"><?= __('team.home'); ?></a> /
    <span class="red"><?= __('team.edit'); ?></span>

    <form class="max-w780" action="<?= url('content.change', ['type' => 'team']); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('team.heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" value="<?= $team['team_name']; ?>" name="name">
        <div class="help">6 - 250 <?= __('team.characters'); ?></div>
      </fieldset>

      <?php insert('/_block/form/textarea', [
        'title'     => __('team.description'),
        'type'      => 'text',
        'name'      => 'content',
        'content'   => $team['team_content'],
        'min'       => 6,
        'max'       => 555,
        'help'      => '5 - 555 ' . __('team.characters'),
        'user'       => $user
      ]); ?>

      <?= insert('/_block/form/select/users-team', ['users' => $data['users']]); ?>

      <input type="hidden" name="id" value="<?= $team['team_id']; ?>">
      <p><?= Html::sumbit(__('team.add')); ?></p>
    </form>

  </div>
</main>
