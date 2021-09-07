<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15 space-tags">
        <?php if ($uid['user_trust_level'] == 5) { ?>
          <a title="<?= lang('Add'); ?>" class="right mt5" href="/admin/webs/add">
            <i class="icon-plus middle"></i>
          </a>
        <?php } ?>
        <h1><?= lang('domains-title'); ?></h1>
    </div>

    <?php if (!empty($data['links'])) { ?>
      <?php foreach ($data['links'] as $key => $link) { ?>
        <div class="white-box">
          <a href="/domain/<?= $link['link_url_domain']; ?>">
            <h2 class="title size-21 pt15 ml15 mb0">
              <?php if ($link['link_title']) { ?>
                <?= $link['link_title']; ?>
              <?php } else { ?>
                Add title...
              <?php } ?>
            </h2>
          </a>
          <?php if ($uid['user_trust_level'] == 5) { ?>
            <a class="size-13 mr10 right" title="<?= lang('Edit'); ?>" href="/admin/webs/<?= $link['link_id']; ?>/edit">
              <i class="icon-pencil size-15"></i>
            </a>
          <?php } ?>
          <span class="green ml15">
            <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
            <?= $link['link_url_domain']; ?>
          </span>
          <div class="gray ml15">
            <?php if ($link['link_content']) { ?>
              <?= $link['link_content']; ?>
            <?php } else { ?>
              Add content...
            <?php } ?>
          </div>
          <div class="pt5 pr15 pb5 pl15 hidden lowercase">
            <?= votes($uid['user_id'], $link, 'link'); ?>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content('No'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('domains-desc'); ?>.
    </div>
  </aside>
</div>