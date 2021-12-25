<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.admin'),
      ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => [
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
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['audits'])) { ?>
    <table>
      <thead>
        <th>Id</th>
        <th><?= Translate::get('info'); ?></th>
        <th><?= Translate::get('action'); ?></th>
        <th><?= Translate::get('id'); ?></th>
      </thead>
      <?php foreach ($data['audits'] as $key => $audit) { ?>
        <?php $user_id = $audit['user_id']; ?>
        <tr>
          <td class="center">
            <?= $audit['audit_id']; ?>
          </td>
          <td class="size-13">
            <div class="content-telo">
              <?= $audit['content'][$audit['audit_type'] . '_content']; ?>
            </div>

            (id:<?= $user_id; ?>)
            <a href="<?= getUrlByName('user', ['login' => $audit['user_login']]); ?>">
              <?= $audit['user_login']; ?>
            </a>
            <?php if ($audit['user_limiting_mode'] == 1) { ?>
              <span class="mr5 ml5 red-500"> audit </span>
            <?php } ?>
            <span class="mr5 ml5"> &#183; </span>
            <a class="mr5 ml5" href="<?= getUrlByName('admin.user.edit', ['id' => $user_id]); ?>">
              <i class="bi bi-pencil size-15"></i>
            </a>
            <span class="mr5 ml5"> &#183; </span>
            <?= $audit['content'][$audit['audit_type'] . '_date']; ?>
            <span class="mr5 ml5"> &#183; </span>

            <?= Translate::get('type'); ?>: <i><?= $audit['audit_type']; ?></i>
            <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
              <span class="red-500"><?= Translate::get('deleted'); ?> </span>
            <?php } ?>

            <?php if (!empty($audit['post'])) { ?>
              <?php if ($audit['post']['post_slug']) { ?>
                <a class="block mt5" href="<?= getUrlByName('post', ['id' => $audit['post']['post_id'], 'slug' => $audit['post']['post_slug']]); ?>">
                  <?= $audit['post']['post_title']; ?>
                </a>
              <?php } ?>
            <?php } ?>
          </td>
          <td class="center">
            <a data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" data-type="<?= $audit['audit_type']; ?>" class="type-action size-13">
              <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                <span class="red-500"><?= Translate::get('recover'); ?></span>
              <?php } else { ?>
                <?= Translate::get('remove'); ?>
              <?php } ?>
            </a>
          </td>
          <td class="center">
            <?php if ($audit['audit_read_flag'] == 1) { ?>
              id:
              <a href="<?= getUrlByName('admin.user.edit', ['id' => $user_id]); ?>">
                <?= $audit['audit_user_id']; ?>
              </a>
            <?php } else { ?>
              <a data-status="<?= $audit['audit_type']; ?>" data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" class="audit-status size-13">
                <?= Translate::get('to approve'); ?>
              </a>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
<?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.audits')); ?>
</main>