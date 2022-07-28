<?php if (Request::getUri() == '/') : ?>
  <center>
    <a class="btn btn-outline-primary center" href="/topics">
      <?= __('app.topic_subs'); ?>!
    </a>
  </center>
  <div class="grid-cols-2 mt15">
    <?php foreach ($data['topics'] as $topic) : ?>
      <div class="box">
        <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id red right">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#plus"></use>
          </svg> <?= __('app.read'); ?>
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