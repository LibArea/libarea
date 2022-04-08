<?= Tpl::insert('/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<main>
  <div class="box-white relative">
    <h1><?= __('teams'); ?></h1>

    <i><?= __('being.developed'); ?></i>
  </div>
</main> 


<?= Tpl::insert('/footer', ['user' => $user]); ?>