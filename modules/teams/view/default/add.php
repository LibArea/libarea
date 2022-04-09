<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<main class="col-two">
  <div class="box-white">

    <a href="<?= getUrlByName('teams'); ?>"><?= __('teams'); ?></a> /
    <span class="red"><?= sprintf(__('add.option'), __('team')); ?></span>

    <form class="max-w780" action="<?= getUrlByName('team.create'); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" name="name">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>
      <fieldset>
        <label for="name"><?= __('description'); ?></label>
        <?= Tpl::insert('/_block/editor/editor', ['height'  => '250px', 'type' => 'content', 'id' => 0]); ?>
      </fieldset>
      <p><?= Html::sumbit(__('add')); ?></p>
    </form>

  </div>
</main>

<?= Tpl::insert('/footer', ['user' => $user]); ?>