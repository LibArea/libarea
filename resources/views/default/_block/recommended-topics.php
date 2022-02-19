<?php if (Request::getUri() == '/') { ?>
  <center>
    <a class="tags center" href="/topics">
      <i class="bi bi-lightbulb middle mr5"></i>
      <?= Translate::get('topic-subscription'); ?>
    </a>
  </center>
  <div class="grid grid-cols-12 gap-4 mb-gap-05 pt20 justify-between">
    <?php foreach ($data['topics'] as $topic) { ?>
      <div class="box-white col-span-6">
        <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 sky-500 center mr5">
          <sup><i class="bi bi-plus"></i> <?= Translate::get('read'); ?></sup>
        </div>
        <a class="" title="<?= $topic['facet_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
          <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'img-base'); ?>
          <?= $topic['facet_title']; ?>
        </a>
        <div class="mt5 text-sm gray-600">
          <?= $topic['facet_description']; ?>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } ?>