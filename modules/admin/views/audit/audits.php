<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'audits.all',
        'url'   => url('admin.audits'),
        'name'  => __('admin.all'),
      ], [
        'id'    => 'audits.ban',
        'url'   => url('admin.audits.ban'),
        'name'  => __('admin.approved'),
      ], [
        'id'    => 'reports.all',
        'url'   => url('admin.reports'),
        'name'  => __('admin.reports'),
      ]
    ]
  ]
); ?>

<?php if (!empty($data['audits'])) : ?>
  <table>
    <thead>
      <th class="w50">id</th>
      <th><?= __('admin.info'); ?></th>
      <th class="center"><?= __('admin.type'); ?></th>
      <th class="center"><?= __('admin.action'); ?></th>
      <th class="center">#</th>
    </thead>
    <?php foreach ($data['audits'] as $key => $audit) : ?>
      <tr>
        <td>
          <?= $audit['audit_id']; ?>
        </td>
        <td class="text-sm">
          <div class="content-telo">
            <?php $content = $audit['content'][$audit['action_type'] . '_content']; ?>
            <?= fragment($content ?? 'no', 200); ?>
          </div>

          (id:<?= $audit['id']; ?>)
          <a href="<?= url('profile', ['login' => $audit['login']]); ?>">
            <?= $audit['login']; ?>
          </a>
          <?php if ($audit['limiting_mode'] == 1) : ?>
            <span class="mr5 ml5 red"> audit </span>
          <?php endif; ?>
          <span class="mr5 ml5"> &#183; </span>
          <a class="mr5 ml5" href="<?= url('admin.user.edit.form', ['id' => $audit['id']]); ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
          <span class="mr5 ml5"> &#183; </span>
          <?= $audit['content'][$audit['action_type'] . '_date']; ?>
          <span class="mr5 ml5"> &#183; </span>

          <?= __('admin.type'); ?>: <i><?= $audit['action_type']; ?></i>
          <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) : ?>
            <span class="red"><?= __('admin.deleted'); ?> </span>
          <?php endif; ?>

          <?php if (!empty($audit['post'])) : ?>
            <?php if ($audit['post']['post_slug']) : ?>
              <a class="block" href="<?= post_slug($audit['post']['post_id'], $audit['post']['post_slug']); ?>">
                <?= $audit['post']['post_title']; ?>
              </a>
            <?php endif; ?>
          <?php endif; ?>
        </td>
        <td class="center"><?= __('admin.' . $audit['type_belonging']); ?></td>
        <td class="center">
          <a data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" data-type="<?= $audit['action_type']; ?>" class="type-action text-sm">
            <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) : ?>
              <span class="red">
                <?= __('admin.deleted'); ?>
              </span>
            <?php else : ?>
              <?= __('admin.remove'); ?>
            <?php endif; ?>
          </a>
          <div class="lowercase text-sm">
            <?= __('admin.content'); ?>
          </div>
        </td>
        <td class="center">
          <?php if ($audit['type_belonging'] == 'audit') : ?>
            <?php if ($audit['read_flag'] == 1) : ?>
              id:
              <a href="<?= url('admin.user.edit.form', ['id' => $audit['audit_id']]); ?>">
                <?= $audit['user_id']; ?>
              </a>
            <?php else : ?>
              <a data-status="<?= $audit['action_type']; ?>" data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" class="audit-status text-sm">
                <?= __('admin.approve'); ?>
              </a>
              <div class="text-sm"><?= __('admin.off_mode'); ?></div>
            <?php endif; ?>
          <?php else : ?>
            <div class="<?php if ($audit['read_flag'] == 0) : ?> bg-red-200<?php endif; ?>">
              <span class="report-saw" data-id="<?= $audit['audit_id']; ?>">
                <svg class="icon gray">
                  <use xlink:href="/assets/svg/icons.svg#circle"></use>
                </svg>
              </span>
            </div>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
<?php endif; ?>

<?= pagination($data['pNum'], $data['pagesCount'], false, url('admin.audits')); ?>
</main>

<?= insertTemplate('footer'); ?>