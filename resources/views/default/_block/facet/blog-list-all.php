       <?php foreach ($facets as $key => $facet) { ?>
        <div class="mb20 items-center flex flex-row">
          <a title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
            <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'ava-lg'); ?>
          </a>
          <div class="ml5 w-100">
            <?php if ($user['id']) { ?>
              <div class="right">
              <?php if ($facet['facet_user_id'] != $user['id']) { ?>
                <?php if ($facet['signed_facet_id']) { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id yes">
                     <?= Translate::get('unsubscribe'); ?>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id no">
                    <i class="bi-plus"></i> <?= Translate::get('read'); ?>
                  </div>
                <?php } ?>
              <?php } ?>
              </div>
            <?php } ?>

            <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
              <?= $facet['facet_title']; ?>
            </a>

            <?php if ($user['id'] == $facet['facet_user_id']) { ?>
              <i class="bi-mic sky-500 text-sm"></i>
            <?php } ?>
            <div class="text-sm pr15 mb-pr0 gray-400">
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
  