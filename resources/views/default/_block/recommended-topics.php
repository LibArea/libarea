<?php if (Request::getUri() == '/') { ?>
  <a class="bg-blue-100 bg-hover-green white-hover flex justify-center pt5 pr10 pb5 pl10 br-rd5 p15 mb15 sky-500 size-14" href="/topics">
    <i class="bi bi-lightbulb middle mr5"></i>
    <?= Translate::get('topic-subscription'); ?>
  </a>
  <div class="grid grid-cols-12 gap-4 pr10 pl10 justify-between">
    <?php foreach ($data['topics'] as $topic) { ?>
      <div class="col-span-6 br-box-gray p10">
        <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 sky-500 center mr5">
          <sup><i class="bi bi-plus"></i> <?= Translate::get('read'); ?></sup>
        </div>
        <a class="" title="<?= $topic['facet_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
          <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w24 mr5'); ?>
          <?= $topic['facet_title']; ?>
        </a>
        <div class="mt5 size-14 gray-600">
          <?= $topic['facet_description']; ?>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } ?>