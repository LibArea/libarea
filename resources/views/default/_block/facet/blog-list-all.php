  <div class="mt5 mr0 mb5 ml0 grid grid-cols-12 gap-4 justify-betwee">

    <div class="col-span-9 mb-col-12 mt5 mr0 mb5 ml0">
      <?php foreach ($facets as $key => $facet) { ?>

        <div class="w-100 mb20 mb-w-100 flex flex-row">
          <a title="<?= $facet['facet_title']; ?>" class="mr10" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
            <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'w64 br-box-gray br-rd-50'); ?>
          </a>
          <div class="ml5 w-100">

            <?php if ($uid['user_id']) { ?>
              <?php if ($facet['facet_user_id'] != $uid['user_id']) { ?>
                <?php if ($facet['signed_facet_id']) { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 gray-light-2 center mr15">
                    <sup><?= Translate::get('unsubscribe'); ?></sup>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 blue center mr15">
                    <sup><i class="bi bi-plus"></i> <?= Translate::get('read'); ?></sup>
                  </div>
                <?php } ?>
              <?php } ?>
            <?php } ?>

            <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
              <?= $facet['facet_title']; ?>
            </a>

            <?php if ($uid['user_id'] == $facet['facet_user_id']) { ?>
              <i class="bi bi-mic blue size-14"></i>
            <?php } ?>
            <div class="size-14 pr15 mb-pr-0 gray-light-2">
              <?= $facet['facet_short_description']; ?>
              <div class="flex mt5 size-14">
                <i class="bi bi-journal mr5"></i>
                <?= $facet['facet_count']; ?>
                <?php if ($facet['facet_focus_count'] > 0) { ?>
                  <i class="bi bi-people ml15 mr5"></i>
                  <?= $facet['facet_focus_count']; ?>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <div class="col-span-3 relative pl20 no-mob">
      В стадии разработки...
    </div>
  </div>