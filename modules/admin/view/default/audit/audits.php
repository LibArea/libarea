<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi bi-record-circle'
      ], [
        'id' => $data['type'] . '.ban',
        'url' => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name' => Translate::get('approved'),
        'icon' => 'bi bi-x-circle'
      ]
    ]
  ]
); ?>

<div class="box-white">
  <?php if (!empty($data['audits'])) { ?>
    <table>
      <thead>
        <th>Id</th>
        <th><?= Translate::get('info'); ?></th>
        <th><?= Translate::get('action'); ?></th>
        <th><?= Translate::get('id'); ?></th>
      </thead>
      <?php foreach ($data['audits'] as $key => $audit) { ?>
        <?php $user_id = $audit['id']; ?>
        <tr>
          <td class="center">
            <?= $audit['audit_id']; ?>
          </td>
          <td class="text-sm">
            <div class="content-telo">
              <?= $audit['content'][$audit['audit_type'] . '_content']; ?>
            </div>

            (id:<?= $user_id; ?>)
            <a href="<?= getUrlByName('profile', ['login' => $audit['login']]); ?>">
              <?= $audit['login']; ?>
            </a>
            <?php if ($audit['limiting_mode'] == 1) { ?>
              <span class="mr5 ml5 red-500"> audit </span>
            <?php } ?>
            <span class="mr5 ml5"> &#183; </span>
            <a class="mr5 ml5" href="<?= getUrlByName('admin.user.edit', ['id' => $user_id]); ?>">
              <i class="bi bi-pencil"></i>
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
                <a class="block" href="<?= getUrlByName('post', ['id' => $audit['post']['post_id'], 'slug' => $audit['post']['post_slug']]); ?>">
                  <?= $audit['post']['post_title']; ?>
                </a>
              <?php } ?>
            <?php } ?>
          </td>
          <td class="center">
            <a data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" data-type="<?= $audit['audit_type']; ?>" class="type-action text-sm">
              <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                <span class="red-500">
                  <?= Translate::get('recover'); ?>
                </span>
              <?php } else { ?>
                <?= Translate::get('remove'); ?>
              <?php } ?>
            </a>
            <div class="lowercase text-xs">
              <?= Translate::get('content'); ?>
            </div>
          </td>
          <td class="center">
            <?php if ($audit['audit_read_flag'] == 1) { ?>
              id:
              <a href="<?= getUrlByName('admin.user.edit', ['id' => $user_id]); ?>">
                <?= $audit['audit_user_id']; ?>
              </a>
            <?php } else { ?>
              <a data-status="<?= $audit['audit_type']; ?>" data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" class="audit-status text-sm">
                <?= Translate::get('to approve'); ?>
              </a>
              <div class="text-xs"><?= Translate::get('off.mute.mode'); ?></div>
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
<?= includeTemplate('/view/default/footer'); ?>