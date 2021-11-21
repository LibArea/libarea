<?php if (!$user_id) { ?>
  <a href="<?= getUrlByName('login'); ?>">
    <div class="bg-gray-200 bg-hover-gray mazarine br-box-gray inline br-rd20 center pt5 pr15 pb5 pl15">
      + <?= Translate::get('read'); ?>
    </div>
  </a>
<?php } else { ?>
  <?php if ($topic['facet_user_id'] != $user_id) { ?>
    <?php if ($topic_signed) { ?>
      <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id bg-gray-100 gray-light-2 br-box-gray inline br-rd20 center pt5 pr15 pb5 pl15">
        <?= Translate::get('unsubscribe'); ?>
      </div>
    <?php } else { ?>
      <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id bg-gray-200 bg-hover-gray mazarine br-box-gray inline br-rd20 center pt5 pr15 pb5 pl15">
        + <?= Translate::get('read'); ?>
      </div>
    <?php } ?>
  <?php } ?>
<?php } ?>