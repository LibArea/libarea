<main class="col-two">
  <div class="box">

    <a href="<?= url('teams'); ?>"><?= __('team.home'); ?></a> /
    <span class="red"><?= __('team.add_team'); ?></span>

    <form class="max-w780" action="<?= url('content.create', ['type' => 'team']); ?>" method="post">
      <?= csrf_field() ?>
      <fieldset>
        <label for="name"><?= __('team.heading'); ?></label>
        <input minlength="6" maxlength="250" type="text" required="" name="name">
        <div class="help">6 - 250 <?= __('team.characters'); ?></div>
      </fieldset>
      <fieldset>
      
        <?= insert('/_block/form/textarea', [
        'title'     => __('team.description'),
        'height'  => '250px', 
        'name' => 'content', 
        'type' => 'content', 
        'id' => 0]
        ); ?>
        
      </fieldset>
      <p><?= Html::sumbit(__('team.add')); ?></p>
    </form>

  </div>
</main>