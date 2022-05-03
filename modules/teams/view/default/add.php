<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<main class="col-two">
  <div class="box">

    <a href="<?= url('teams'); ?>"><?= __('team.home'); ?></a> /
    <span class="red"><?= __('team.add_team'); ?></span>

    <form class="max-w780" action="<?= url('team.create'); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('team.heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" name="name">
        <div class="help">6 - 250 <?= __('team.characters'); ?></div>
      </fieldset>
      <fieldset>
        <label for="name"><?= __('team.description'); ?></label>
        <?= Tpl::insert('/_block/form/editor', ['height'  => '250px', 'type' => 'content', 'id' => 0]); ?>
      </fieldset>
      <p><?= Html::sumbit(__('team.add')); ?></p>
    </form>

  </div>
</main>

<?= Tpl::insert('/footer'); ?>