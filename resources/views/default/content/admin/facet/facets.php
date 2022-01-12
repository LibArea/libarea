<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi bi-record-circle'
      ], [
        'id' => $data['type'] . '.ban',
        'url' => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name' => Translate::get('deleted'),
        'icon' => 'bi bi-x-circle'
      ],
    ]
  ]
); ?>

<div class="white-box pt5 pr15 pb5 pl15">
  <?php if (!empty($data['facets'])) { ?>
    <table>
      <thead>
        <th>Id</th>
        <th><?= Translate::get('logo'); ?></th>
        <th><?= Translate::get('info'); ?></th>
        <th>Ban</th>
        <th><?= Translate::get('action'); ?></th>
      </thead>
      <?php foreach ($data['facets'] as $key => $fc) {
        $url = 'topic';
        if ($data['type'] == 'blogs') {
          $url = 'blog';
        }
      ?>
        <tr>
          <td class="center">
            <?= $fc['facet_id']; ?>
          </td>
          <td class="center">
            <?= facet_logo_img($fc['facet_img'], 'max', $fc['facet_title'], 'w60 h60'); ?>
          </td>
          <td>
            <a class="text-2xl" rel="nofollow noreferrer" href="<?= getUrlByName($url, ['slug' => $fc['facet_slug']]); ?>">
              <?= $fc['facet_title']; ?>
            </a>
            <span class="green-600 mr5 ml5"><?= $data['type']; ?>/<?= $fc['facet_slug']; ?></span>
            <span class="mr5 ml5 gray-400"><?= Translate::get('posts'); ?> <?= $fc['facet_count']; ?></span>
            <?php if ($fc['facet_top_level'] != 0) { ?>
              <span class="green-600 mr5 ml5"><?= Translate::get('subtopic'); ?></span>
            <?php } ?>
            <div class="content-telo">
              <?= $fc['facet_description']; ?>
            </div>
          </td>
          <td class="center">
            <?php if ($fc['facet_is_deleted'] == 1) { ?>
              <span class="type-ban" data-id="<?= $fc['facet_id']; ?>" data-type="topic">
                <span class="red-500"><?= Translate::get('unban'); ?></span>
              </span>
            <?php } else { ?>
              <span class="type-ban" data-id="<?= $fc['facet_id']; ?>" data-type="topic">
                <?= Translate::get('ban it'); ?>
              </span>
            <?php } ?>
          </td>
          <td class="center">
            <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('topic.edit', ['id' => $fc['facet_id']]); ?>">
              <i class="bi bi-pencil"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
<?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.topics')); ?>
</main>