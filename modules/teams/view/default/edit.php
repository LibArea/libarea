<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<?php $team = $data['team']; ?>

<main>
  <div class="box">
    <a href="<?= url('teams'); ?>"><?= __('teams'); ?></a> /
    <span class="red"><?= sprintf(__('edit.option'), __('team')); ?></span>

    <form class="max-w780" action="<?= url('team.change'); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" value="<?= $team['name']; ?>" name="name">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>

      <?php Tpl::insert('/_block/editor/textarea', [
        'title'     => __('description'),
        'type'      => 'text',
        'name'      => 'content',
        'content'   => $team['content'],
        'min'       => 6,
        'max'       => 555,
        'help'      => '5 - 555 ' . __('characters'),
        'user'       => $user
      ]); ?>

      <?= includeTemplate('/view/default/form/user', ['users' => $data['users']]); ?>

      <input type="hidden" name="id" value="<?= $team['id']; ?>">
      <p><?= Html::sumbit(__('add')); ?></p>
    </form>

  </div>
</main>

<?= Tpl::insert('/footer', ['user' => $user]); ?>