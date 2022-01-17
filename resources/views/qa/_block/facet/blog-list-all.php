  <div class="mt5 mr0 mb5 ml0 justify-betwee">
      <?php foreach ($facets as $key => $facet) { ?>

        <div class="w-100 mb20 mb-w-100 flex flex-row">
          <a title="<?= $facet['facet_title']; ?>" class="mr10" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
            <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'w60 h60 br-box-gray br-rd-50'); ?>
          </a>
          <div class="ml5 w-100">

            <?php if ($user['id']) { ?>
              <?php if ($facet['facet_user_id'] != $user['id']) { ?>
                <?php if ($facet['signed_facet_id']) { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 gray-400 center mr15">
                    <sup><?= Translate::get('unsubscribe'); ?></sup>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 sky-500 center mr15">
                    <sup><i class="bi bi-plus"></i> <?= Translate::get('read'); ?></sup>
                  </div>
                <?php } ?>
              <?php } ?>
            <?php } ?>

            <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
              <?= $facet['facet_title']; ?>
            </a>

            <?php if ($user['id'] == $facet['facet_user_id']) { ?>
              <i class="bi bi-mic sky-500 text-sm"></i>
            <?php } ?>
            <div class="text-sm pr15 mb-pr-0 gray-400">
              <?= $facet['facet_short_description']; ?>
              <div class="flex mt5 text-sm">
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