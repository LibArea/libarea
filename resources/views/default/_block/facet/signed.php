<?php if (!$user['id']) { ?>
  <a href="<?= getUrlByName('login'); ?>">
    <div class="focus-id no">
      + <?= Translate::get('read'); ?>
    </div>
  </a>
<?php } else { ?>
  <?php if ($topic['facet_user_id'] != $user['id']) { ?>
    <?php if ($topic_signed) { ?>
      <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id yes">
        <?= Translate::get('unsubscribe'); ?>
      </div>
    <?php } else { ?>
      <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id no">
        + <?= Translate::get('read'); ?>
      </div>
    <?php } ?>
  <?php } ?>
<?php } ?>