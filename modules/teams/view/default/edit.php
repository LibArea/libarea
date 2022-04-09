<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<?php $team = $data['team']; ?>

<main>
  <div class="box-white">
    <a href="<?= getUrlByName('teams'); ?>"><?= __('teams'); ?></a> /
    <span class="red"><?= sprintf(__('edit.option'), __('team')); ?></span>

    <form class="max-w780" action="<?= getUrlByName('team.change'); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" value="<?= $team['name']; ?>" name="name">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>
      <fieldset>
        <label for="name"><?= __('description'); ?></label>
        <?= Tpl::insert('/_block/editor/editor', ['height'  => '250px', 'content' => $team['content'], 'type' => 'content', 'id' => $team['id']]); ?>
      </fieldset>
      <input type="hidden" name="id" value="<?= $team['id']; ?>">
      <p><?= Html::sumbit(__('add')); ?></p>
    </form>

  </div>
</main>

<?= Tpl::insert('/footer', ['user' => $user]); ?>