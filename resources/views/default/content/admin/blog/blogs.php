<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <a class="right mr15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('topic.add'); ?>">
    <i class="bi bi-plus-lg middle"></i>
  </a>

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('topics')
  ); ?>


  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('blogs'); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'blogs',
            'url' => '/admin/blogs',
            'content' => Translate::get('all'),
            'icon' => 'bi bi-record-circle'
          ],
          [
            'id' => 'admin.blogs.ban.all',
            'url' => '/admin/blogs/ban',
            'content' => Translate::get('answers-ban'),
            'icon' => 'bi bi-x-circle'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <div class="white-box pt5 pr15 pb5 pl15">
    <?php if (!empty($data['blogs'])) { ?>
      <table>
        <thead>
          <th>Id</th>
          <th><?= Translate::get('logo'); ?></th>
          <th><?= Translate::get('info'); ?></th>
          <th>Ban</th>
          <th><?= Translate::get('action'); ?></th>
        </thead>
        <?php foreach ($data['blogs'] as $key => $blog) { ?>
          <tr>
            <td class="center">
              <?= $blog['facet_id']; ?>
            </td>
            <td class="center">
              <?= facet_logo_img($blog['facet_img'], 'max', $blog['facet_title'], 'w64'); ?>
            </td>
            <td>
              <a class="size-21" rel="nofollow noreferrer" href="<?= getUrlByName('blog', ['slug' => $blog['facet_slug']]); ?>">
                <?= $blog['facet_title']; ?>
              </a>
              <span class="green mr5 ml5">blog/<?= $blog['facet_slug']; ?></span>
              <span class="mr5 ml5">posts: <?= $blog['facet_count']; ?></span>
              <?php if ($blog['facet_top_level'] != 0) { ?>
                <span class="green mr5 ml5"><?= Translate::get('subtopic'); ?></span>
              <?php } ?>
              <div class="content-telo">
                <?= $blog['facet_description']; ?>
              </div>
            </td>
            <td class="center">
                <?php if ($blog['facet_is_deleted'] == 1) { ?>
                  <span class="type-ban" data-id="<?= $blog['facet_id']; ?>" data-type="topic">
                    <span class="red"><?= Translate::get('unban'); ?></span>
                  </span>
                <?php } else { ?>
                  <span class="type-ban" data-id="<?= $blog['facet_id']; ?>" data-type="topic">
                    <?= Translate::get('ban it'); ?>
                  </span>
                <?php } ?>
            </td>
            <td class="center">
              <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('topic.edit', ['id' => $blog['facet_id']]); ?>">
                <i class="bi bi-pencil size-15"></i>
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