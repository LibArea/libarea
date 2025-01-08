<?php if ($container->request()->getUri()->getPath() == '/') : ?>
  <div class="grid-cols-2 mt15">
    <?php foreach ($data['topics'] as $topic) : ?>
      <div class="box shadow-bottom flex gap items-center justify-between">
        <div>
          <a class="" title="<?= $topic['facet_title']; ?>" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
            <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-base', 'logo', 'max'); ?>
            <?= $topic['facet_title']; ?>
          </a>
          <div class="mt5 text-sm max-w-md gray">
            <?= $topic['facet_description']; ?>
          </div>
        </div>
        <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id red"><?= __('app.read'); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
  <center>
    <a class="btn btn-outline-primary center mb20" href="/topics">
      <?= __('app.topic_subs'); ?>!
    </a>
  </center>
<?php endif; ?>