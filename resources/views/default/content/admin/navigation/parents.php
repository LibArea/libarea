<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => []
  ]
); ?>

<div class="bg-white br-box-gray p15  text-sm">
  <form action="<?= getUrlByName('admin.navigation.edit'); ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="upsub" value="0">
    <table>
      <thead>
        <th><span class="left">id</span></th>
        <th><span class="left"><?= Translate::get('sort'); ?></span></th>
        <th><span class="left"><?= Translate::get('name'); ?></span></th>
        <th><?= Translate::get('children'); ?></th>
        <th><i class="bi bi-eye"></i></th>
        <th><?= Translate::get('action'); ?></th>
      </thead>
      <?php foreach ($data['root_menu'] as $menu) {  ?>
        <tr>
          <td>
            <input class="checkbox inline" type="checkbox" value="<?= $menu['nav_id']; ?>" name="cid[]">
            <?= $menu['nav_id']; ?>
          </td>
          <td>
            <input class="gray-400" type="text" value="<?= $menu['nav_ordernum']; ?>" name="corder<?= $menu['nav_id']; ?>">
          </td>
          <td>
            <input class="checkbox inline" type="text" value="<?= $menu['nav_name']; ?>" name="cname<?= $menu['nav_id']; ?>">
            <?= Translate::get($menu['nav_name']); ?>
          </td>
          <td>
            <?= $menu['nav_childs']; ?> — 
            <a class="ml5 lowercase" href="<?= getUrlByName('admin.navigation.sub', ['id' => $menu['nav_id']]); ?>">
              <?= Translate::get('view'); ?>
            </a>
          </td>
          <td class="center">

            <?php if ($menu['nav_status']) { ?>
              <span class="type-ban" data-id="<?= $menu['nav_id']; ?>" data-type="navigation">
                <i class="bi bi-x-circle red-500"></i>
              </span>
            <?php } else { ?>
              <span class="type-ban" data-id="<?= $menu['nav_id']; ?>" data-type="navigation">
                <i class="bi bi-record-circle gray-400"></i>
              </span>
            <?php } ?>

          </td>
          <td class="center">
            <a class="btn btn-small btn-primary" href="<?= getUrlByName('admin.navigation.add', ['id' => $menu['nav_id']]); ?>">
              <?= Translate::get('add.submenu'); ?>
            </a>
        </tr>
      <?php } ?>
    </table>

    <?= sumbit(Translate::get('edit')); ?>

    <a class="ml10 gray-400" href="<?= getUrlByName('admin.navigation.add', ['id' => 0]); ?>">
      <?= Translate::get('add'); ?>
    </a>
  </form>
  <ul>
    <li><b>Name:</b> String in localization files</li> 
    <li><b>URL:</b> Named Route from Router</li> 
    <li>You cannot delete parents, make them invisible, or edit existing ones.</li> 
  <ul>
</div>
</main>