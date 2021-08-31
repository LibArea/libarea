<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Drafts')); ?>
    </div>
    <?php if (!empty($data['drafts'])) { ?>
      <?php foreach ($data['drafts'] as $draft) { ?>
        <div class="white-box pt5 pb5 pl15">
          <a href="<?= post_url($draft); ?>">
            <h3 class="title m0 size-21"><?= $draft['post_title']; ?></h3>
          </a>
          <div class="mr5 size-13 gray-light lowercase">
            <?= $draft['post_date']; ?> |
            <a href="/post/edit/<?= $draft['post_id']; ?>"><?= lang('Edit'); ?></a>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content('There no drafts'); ?>
    <?php } ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('Under development'); ?>...
    </div>
  </aside>
</div>