<?php $fs = $data['facet']; ?>

<main class="w-100">

  <div class="bg-white box-flex">
    <p class="m0 text-xl"><?= __($fs['facet_type']); ?></p>
    <ul class="nav">

      <?= Tpl::insert(
        '/_block/navigation/nav',
        [
          'type' => $data['sheet'],
          'user' => $user,
          'list' =>  [
            [
              'id'        => 'edit',
              'url'       => getUrlByName($fs['facet_type'] . '.edit', ['id' => $fs['facet_id']]),
              'title'     => __('settings'),
              'icon'      => 'bi-gear'
            ], [
              'id'        => 'pages',
              'url'       => '',
              'title'     => __('pages'),
              'icon'      => 'bi-app'
            ], [
              'id'        => 'all',
              'url'       => getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]),
              'title'     => __('go.to'),
              'icon'      => 'bi-arrow-up-right-square'
            ]
          ]
        ]
      ); ?>

    </ul>
  </div>

  <div class="bg-white p15">

    <div class="uppercase gray mt5 mb5">
      <?= __('pages'); ?>
      <a class="mr15 right" title="<?= __('add'); ?>" href="<?= getUrlByName('page.add'); ?>">
        <i class="bi-plus-lg text-xl"></i>
      </a>
    </div>
    <?php if ($data['pages']) : ?>

      <?php foreach ($data['pages'] as $ind => $row) : ?>
        <div class="mb5">
          <a class="relative pt5 pb5 hidden" href="<?= getUrlByName('page', ['facet' => $fs['facet_slug'], 'slug' => $row['post_slug']]); ?>">
            <?= $row['post_title']; ?>
          </a>

          <?php if (UserData::checkAdmin() || $fs['facet_user_id'] == $user['id']) : ?>
            <a class="text-sm gray-600" title="<?= __('edit'); ?>" href="<?= getUrlByName('content.edit', ['type' => 'page', 'id' => $row['post_id']]); ?>">
              <i class="bi-pencil"></i>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

    <?php else : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>

  </div>
</main>