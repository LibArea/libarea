<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
      <h1><?= lang('Search'); ?></h1>
      <?php if (!empty($data['result'])) { ?>
        <div><?= lang('You were looking for'); ?>: <b><?= $data['query']; ?></b></div>
      <?php } ?>
      <br>
      <?php if (!empty($data['result'])) { ?>
        <?php if (Lori\Config::get(Lori\Config::PARAM_SEARCH) == 0) { ?>
          <?php foreach ($data['result'] as  $post) { ?>
            <div class="search max-width mb20">
              <a class="search-title size-21" href="<?= post_url($post); ?>">
                <?= $post['post_title']; ?>
              </a>
              <span class="no-md"><?= $post['post_content']; ?>...</span>
            </div>
            <div class="mt15 mb15"></div>
          <?php } ?>
        <?php } else { ?>
          <?php foreach ($data['result'] as  $post) { ?>
            <div class="search max-width mb20">
              <div class="search-info gray size-13 lowercase">
                <?= spase_logo_img($post['space_img'], 'small', $post['space_name'], 'ava mr5'); ?>
                <a class="search-info gray lowercase" href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a>
                — <?= lang('Like'); ?> <?= $post['post_votes']; ?>
              </div>
              <a class="search-title" href="<?= post_url($post); ?>">
                <?= $post['_title']; ?>
              </a>
              <div class="no-md"><?= $post['_content']; ?></div>
            </div>
            <div class="mt15 mb15"></div>
          <?php } ?>
        <?php } ?>
      <?php } else { ?>
        <p><?= lang('The search has not given any results'); ?>
        <p>
        <?php } ?>
  </main>
  <aside>
    <div class="white-box p15 mb15">
      <?= lang('info-search'); ?>
    </div>
    <?php foreach ($data['tags'] as $key => $tag) { ?>
      <div class="search max-width mb15 ml10">
        <a class="tags gray size-13" href="/topic/<?= $tag['topic_slug'] ?>">
          <?= $tag['topic_title']; ?>
        </a>
        <sup class="gray">x<?= $tag['topic_count']; ?></sup>
      </div>
    <?php } ?>
  </aside>
</div>