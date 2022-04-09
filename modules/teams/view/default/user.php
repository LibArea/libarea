<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<main>
  <div class="box-flex-white">
    <p class="m0"><?= __('teams'); ?></p>
    <a href="<?= getUrlByName('team.add'); ?>" class="btn btn-primary"><?= __('add'); ?></a>
  </div>
  <div class="bg-white mb15">
    <i>Все команды добавленные мною...</i>
  </div>
</main> 


<?= Tpl::insert('/footer', ['user' => $user]); ?>