<?= Tpl::insert('/header', ['data' => $data, 'meta' => $meta]); ?>
<?php $team = $data['team']; ?>

<main>
  <div class="box">
    <a href="<?= url('teams'); ?>"><?= __('team.home'); ?></a> /
    <span class="red"><?= __('team.edit'); ?></span>

    <form class="max-w780" action="<?= url('team.change'); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('team.heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" value="<?= $team['name']; ?>" name="name">
        <div class="help">6 - 250 <?= __('team.characters'); ?></div>
      </fieldset>

      <?php Tpl::insert('/_block/form/textarea', [
        'title'     => __('team.description'),
        'type'      => 'text',
        'name'      => 'content',
        'content'   => $team['content'],
        'min'       => 6,
        'max'       => 555,
        'help'      => '5 - 555 ' . __('team.characters'),
        'user'       => $user
      ]); ?>

      <?= includeTemplate('/view/default/form/user', ['users' => $data['users']]); ?>

      <input type="hidden" name="id" value="<?= $team['id']; ?>">
      <p><?= Html::sumbit(__('team.add')); ?></p>
    </form>

  </div>
</main>

<?= Tpl::insert('/footer'); ?>