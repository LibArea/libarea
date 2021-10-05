<main class="col-span-9 mb-col-12">
  <div class="bg-white pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('drafts')); ?>
  </div>
  <?php if (!empty($data['drafts'])) { ?>
    <?php foreach ($data['drafts'] as $draft) { ?>
      <div class="bg-white br-rd-5 border-box-1 pt5 pb5 pl15">
        <a href="<?= getUrlByName('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="title m0 size-21"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 size-14 gray-light lowercase">
          <?= $draft['post_date']; ?> |
          <a href="/post/edit/<?= $draft['post_id']; ?>"><?= lang('edit'); ?></a>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'there no drafts']); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('under development')]); ?>