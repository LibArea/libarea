<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'audits.all',
        'url'   => url('admin.audits'),
        'name'  => __('admin.all'),
        'icon'  => 'bi-record-circle',
      ], [
        'id'    => 'audits.ban',
        'url'   => url('admin.audits.ban'),
        'name'  => __('admin.approved'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'reports.all',
        'url'   => url('admin.reports'),
        'name'  => __('admin.reports'),
        'icon'  => 'bi-record-circle',
      ] 
    ]
  ]
); ?>

<div class="box bg-white">
  <?php if (!empty($data['audits'])) : ?>
    <table>
      <thead>
        <th class="w50">id</th>
        <th><?= __('admin.info'); ?></th>
        <th><?= __('admin.type'); ?></th>
        <th><?= __('admin.action'); ?></th>
        <th>#</th>
      </thead>
      <?php foreach ($data['audits'] as $key => $audit) : ?>
        <tr>
          <td>
            <?= $audit['audit_id']; ?>
          </td>
          <td class="text-sm">
            <div class="content-telo">
              <?php $content = $audit['content'][$audit['action_type'] . '_content']; ?>
              <?= Html::fragment(Content::text($content, 'line'), 200); ?>
            </div>

            (id:<?= $audit['id']; ?>)
            <a href="<?= url('profile', ['login' => $audit['login']]); ?>">
              <?= $audit['login']; ?>
            </a>
            <?php if ($audit['limiting_mode'] == 1) : ?>
              <span class="mr5 ml5 red"> audit </span>
            <?php endif; ?>
            <span class="mr5 ml5"> &#183; </span>
            <a class="mr5 ml5" href="<?= url('admin.user.edit', ['id' => $audit['id']]); ?>">
              <i class="bi-pencil"></i>
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
                <a class="block" href="<?= url('post', ['id' => $audit['post']['post_id'], 'slug' => $audit['post']['post_slug']]); ?>">
                  <?= $audit['post']['post_title']; ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </td>
          <th><?= __('admin.' . $audit['type_belonging']); ?></th>
          <td class="center">
            <a data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" data-type="<?= $audit['action_type']; ?>" class="type-action text-sm">
              <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) : ?>
                <span class="red">
                  <?= __('admin.recover'); ?>
                </span>
              <?php else : ?>
                <?= __('admin.remove'); ?>
              <?php endif; ?>
            </a>
            <div class="lowercase text-xs">
              <?= __('admin.content'); ?>
            </div>
          </td>
          <td class="center">
            <?php if ($audit['type_belonging'] == 'audit') : ?>
                <?php if ($audit['read_flag'] == 1) : ?>
                  id:
                  <a href="<?= url('admin.user.edit', ['id' => $audit['audit_id']]); ?>">
                    <?= $audit['user_id']; ?>
                  </a>
                <?php else : ?>
                  <a data-status="<?= $audit['action_type']; ?>" data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" class="audit-status text-sm">
                    <?= __('admin.approve'); ?>
                  </a>
                  <div class="text-xs"><?= __('admin.off_mode'); ?></div>
                <?php endif; ?>
            <?php else : ?>
              <div class="<?php if ($audit['read_flag'] == 0) : ?> bg-red-200<?php endif; ?>">
                <span class="report-saw" data-id="<?= $audit['audit_id']; ?>">
                  <i class="bi-record-circle gray text-2xl"></i>
                </span>
              </div>  
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</div>
<?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('admin.audits')); ?>
</main>
<?= includeTemplate('/view/default/footer'); ?>