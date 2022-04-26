<?php if (Request::getUri() == '/') : ?>
  <center>
    <a class="tags center" href="/topics">
      <i class="bi-lightbulb middle mr5"></i>
      <?= __('topic.subscription'); ?>
    </a>
  </center>
  <div class="grid-cols-2 pt20">
    <?php foreach ($data['topics'] as $topic) : ?>
      <div class="box">
        <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id bg-violet gray-600 text-sm right">
          <i class="bi-plus"></i> <?= __('read'); ?>
        </div>
        <a class="" title="<?= $topic['facet_title']; ?>" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
          <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base', 'logo', 'max'); ?>
          <?= $topic['facet_title']; ?>
        </a>
        <div class="mt5 text-sm gray-600">
          <?= $topic['facet_description']; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>