<?php if (!$user['id']) { ?>
  <a href="<?= getUrlByName('login'); ?>">
    <div class="bg-sky-50 sky-500 bg-hover-gray br-sky-100 inline br-rd20 center pt5 pr15 pb5 pl15">
      + <?= Translate::get('read'); ?>
    </div>
  </a>
<?php } else { ?>
  <?php if ($topic['facet_user_id'] != $user['id']) { ?>
    <?php if ($topic_signed) { ?>
      <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id bg-gray-100 gray-400 br-gray-200 inline br-rd20 center pt5 pr15 pb5 pl15">
        <?= Translate::get('unsubscribe'); ?>
      </div>
    <?php } else { ?>
      <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id bg-sky-50 sky-500 bg-hover-gray br-sky-100 inline br-rd20 center pt5 pr15 pb5 pl15">
        + <?= Translate::get('read'); ?>
      </div>
    <?php } ?>
  <?php } ?>
<?php } ?>