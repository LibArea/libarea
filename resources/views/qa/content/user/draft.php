<main class="col-two">
  <div class="box-white bg-violet-50">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.favorites')]); ?>
    </ul>
  </div>
    <?php if (!empty($data['drafts'])) { ?>
      <div class="box-white bg-violet-50">
      <?php foreach ($data['drafts'] as $draft) { ?>

        <a href="<?= getUrlByName('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="m0 text-2xl"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 text-sm gray-600 lowercase">
          <?= $draft['post_date']; ?> |
          <a href="<?= getUrlByName('post.edit', ['id' => $draft['post_id']]); ?>"><?= Translate::get('edit'); ?></a>
        </div>

      <?php } ?>
    </div>
    <?php } else { ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'max', 'text' => Translate::get('no.content'), 'icon' => 'bi-journal-richtext']); ?>
    <?php } ?>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('being.developed'); ?>
  </div>
</aside>