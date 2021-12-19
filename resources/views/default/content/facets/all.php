<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['type'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">

    <p class="m0 size-18"><?= Translate::get($data['type']); ?>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="ml15" href="<?= getUrlByName('admin.' . $data['type']); ?>">
          <i class="bi bi-pencil"></i>
        </a>
      <?php } ?>
 
      <?php if ($uid['user_trust_level'] > 0) { 
          $type = $data['type'] == 'blogs' ? 'blog' : 'topic';
        ?> 
        <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_' . $type)) { ?>
          <?php if ($data['count_facet'] > 0) { ?>
            <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName($data['type'] . '.add'); ?>">
              <i class="bi bi-plus-lg middle"></i>
            </a>
          <?php } ?>
        <?php } ?>
      <?php } ?>

    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        false,
        $pages = [
          [
            'id' => $data['type'] . '.all',
            'url' => getUrlByName($data['type'] . '.all'),
            'content' => Translate::get('all'),
            'icon' => 'bi bi-app'
          ],
          [
            'id' => $data['type'] . '.new',
            'url' => getUrlByName($data['type'] . '.new'),
            'content' => Translate::get('new ones'),
            'icon' => 'bi bi-sort-up'
          ],
          [
            'id' => $data['type'] . '.my',
            'url' => getUrlByName($data['type'] . '.my'),
            'content' => Translate::get('reading'),
            'auth' => 'yes',
            'icon' => 'bi bi-check2-square'
          ],
        ]
      );
      ?>

    </ul>
  </div>

  <div class="bg-white p15 br-box-gray">

    <?php if (!empty($data['facets'])) { ?>
      <?php if ($data['type'] == 'blogs') { ?>
        <?= import('/_block/facet/blog-list-all', ['facets' => $data['facets'], 'uid' => $uid]); ?>
      <?php } else { ?>
        <?= import('/_block/facet/topic-list-all', ['facets' => $data['facets'], 'uid' => $uid]); ?>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get($data['type'] . '-no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/' . $data['type']); ?>
  </div>

</main>
<?= import('/_block/wide-footer'); ?>