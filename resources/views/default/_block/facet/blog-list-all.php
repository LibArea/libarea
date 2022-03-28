<?php foreach ($facets as $key => $facet) { ?>
  <div class="mb20 items-center flex flex-row">
    <a title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
      <?= Html::image($facet['facet_img'], $facet['facet_title'], 'ava-lg', 'logo', 'max'); ?>
    </a>
    <div class="ml5 w-100">
      <div class="right">
        <?= Html::signed([
          'user_id'         => $user['id'],
          'type'            => 'facet',
          'id'              => $facet['facet_id'],
          'content_user_id' => $facet['facet_user_id'],
          'state'           => $facet['signed_facet_id'],
          'unsubscribe'     => Translate::get('unsubscribe'),
          'read'            => Translate::get('read'),
        ]); ?>
      </div>

      <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_title']; ?>
      </a>

      <?php if ($user['id'] == $facet['facet_user_id']) { ?>
        <i class="bi-mic sky text-sm"></i>
      <?php } ?>
      <div class="text-sm pr15 mb-pr0 gray">
        <?= $facet['facet_short_description']; ?>
        <div class="flex mt5 text-sm">
          <i class="bi-journal mr5"></i>
          <?= $facet['facet_count']; ?>
          <?php if ($facet['facet_focus_count'] > 0) { ?>
            <i class="bi-people ml15 mr5"></i>
            <?= $facet['facet_focus_count']; ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>