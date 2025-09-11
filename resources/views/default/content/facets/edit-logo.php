<?php
$fs = $data['facet_inf'];
$url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main class="max">
  <div class="box">
    <div class="nav-bar">
      <ul class="nav">
        <?= insert(
          '/_block/navigation/nav',
          [
            'list' => [
              [
                'id'        => 'topic',
                'url'       => url('facet.form.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.edit_' . $data['type'],
              ],
              [
                'id'        => 'topic',
                'url'       => url('facet.form.logo.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.logo',
              ],
              [
                'id'        => 'team',
                'url'       => url('team.form.edit', ['type' => $data['type'], 'id' => $fs['facet_id']]),
                'title'     => 'app.team',
              ]
            ]
          ]
        ); ?>
      </ul>
      <a class="gray-600" href="<?= $url; ?>"><?= __('app.go_to'); ?></a>
    </div>

    <?= insert('/_block/form/cropper/facet-logo', ['data' => $fs]); ?>

    <br>

    <?php if ($fs['facet_type'] == 'blog') : ?>
      <?= insert('/_block/form/cropper/facet-cover', ['data' => $fs]); ?>
    <?php endif; ?>

    <div class="box-info mt20">
      <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
      <?= __('help.edit_' . $data['type']); ?>
    </div>

  </div>
</main>

<script src="/assets/js/cropper/cropper.min.js"></script>
<link rel="stylesheet" href="/assets/js/cropper/cropper.min.css" type="text/css">