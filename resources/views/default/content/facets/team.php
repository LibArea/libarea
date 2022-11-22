<?php
  $fs = $data['facet'];
  $url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main>
  <div class="flex justify-between">
    <ul class="nav">
      <?= insert(
        '/_block/navigation/nav',
        [
          'list' => [
            [
              'id'        => 'topic',
              'url'       => url('content.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
              'title'     => __('app.edit_' . $data['type']),
            ], [
              'id'        => 'blog',
              'url'       => url('team.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
              'title'     => __('app.team'),
            ]
          ]
        ]
      ); ?> 
    </ul>
    <a class="gray-600" href="<?= $url; ?>"><?= __('app.go_to'); ?></a>
  </div>
  <p>
    <?= __('app.being_developed'); ?>
  </p>  
</main>
<aside>
  <div class="box bg-beige">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('app.being_developed'); ?>
  </div>
</aside>