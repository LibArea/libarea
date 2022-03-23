<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'audits.all',
        'url'   => getUrlByName('admin.audits'),
        'name'  => Translate::get('all'),
        'icon'  => 'bi-record-circle',
      ], [
        'id'    => 'audits.ban',
        'url'   => getUrlByName('admin.audits.ban'),
        'name'  => Translate::get('approved'),
        'icon'  => 'bi-x-circle',
      ], [
        'id'    => 'reports.all',
        'url'   => getUrlByName('admin.reports'),
        'name'  => Translate::get('reports'),
        'icon'  => 'bi-record-circle',
      ] 
    ]
  ]
); ?>

<div class="box-white">
  <?php if (!empty($data['audits'])) { ?>
    <table>
      <thead>
        <th class="w50">id</th>
        <th><?= Translate::get('info'); ?></th>
        <th><?= Translate::get('type'); ?></th>
        <th><?= Translate::get('action'); ?></th>
        <th><?= Translate::get('action'); ?></th>
      </thead>
      <?php foreach ($data['audits'] as $key => $audit) { ?>
        <tr>
          <td>
            <?= $audit['audit_id']; ?>
          </td>
          <td class="text-sm">
            <div class="content-telo">
              <?php $content = $audit['content'][$audit['action_type'] . '_content']; ?>
              <?= Content::text(fragment($content), 'text'); ?>
            </div>

            (id:<?= $audit['id']; ?>)
            <a href="<?= getUrlByName('profile', ['login' => $audit['login']]); ?>">
              <?= $audit['login']; ?>
            </a>
            <?php if ($audit['limiting_mode'] == 1) { ?>
              <span class="mr5 ml5 red"> audit </span>
            <?php } ?>
            <span class="mr5 ml5"> &#183; </span>
            <a class="mr5 ml5" href="<?= getUrlByName('admin.user.edit', ['id' => $audit['id']]); ?>">
              <i class="bi-pencil"></i>
            </a>
            <span class="mr5 ml5"> &#183; </span>
             <?= $audit['content'][$audit['action_type'] . '_date']; ?>
            <span class="mr5 ml5"> &#183; </span>

            <?= Translate::get('type'); ?>: <i><?= $audit['action_type']; ?></i>
            <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) { ?>
              <span class="red"><?= Translate::get('deleted'); ?> </span>
            <?php } ?>

            <?php if (!empty($audit['post'])) { ?>
              <?php if ($audit['post']['post_slug']) { ?>
                <a class="block" href="<?= getUrlByName('post', ['id' => $audit['post']['post_id'], 'slug' => $audit['post']['post_slug']]); ?>">
                  <?= $audit['post']['post_title']; ?>
                </a>
              <?php } ?>
            <?php } ?>
          </td>
          <th><?= Translate::get($audit['type_belonging']); ?></th>
          <td class="center">
            <a data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" data-type="<?= $audit['action_type']; ?>" class="type-action text-sm">
              <?php if ($audit['content'][$audit['action_type'] . '_is_deleted'] == 1) { ?>
                <span class="red">
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
            <?php if ($audit['type_belonging'] == 'audit') { ?>
                <?php if ($audit['read_flag'] == 1) { ?>
                  id:
                  <a href="<?= getUrlByName('admin.user.edit', ['id' => $audit['audit_id']]); ?>">
                    <?= $audit['user_id']; ?>
                  </a>
                <?php } else { ?>
                  <a data-status="<?= $audit['action_type']; ?>" data-id="<?= $audit['content'][$audit['action_type'] . '_id']; ?>" class="audit-status text-sm">
                    <?= Translate::get('to approve'); ?>
                  </a>
                  <div class="text-xs"><?= Translate::get('off.mute.mode'); ?></div>
                <?php } ?>
            <?php } else { ?>
              <div class="<?php if ($audit['read_flag'] == 0) { ?> bg-red-200<?php } ?>">
                <span class="report-saw" data-id="<?= $audit['audit_id']; ?>">
                  <i class="bi-record-circle gray text-2xl"></i>
                </span>
              </div>  
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</div>
<?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.audits')); ?>
</main>
<?= includeTemplate('/view/default/footer'); ?>