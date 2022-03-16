<?php if (Request::getUri() == '/') { ?>
  <center>
    <a class="tags center" href="/topics">
      <i class="bi-lightbulb middle mr5"></i>
      <?= Translate::get('topic-subscription'); ?>
    </a>
  </center>
  <div class="grid-cols-2 pt20">
    <?php foreach ($data['topics'] as $topic) { ?>
      <div class="box-white">
        <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id bg-violet-50 gray-600 text-sm right">
          <i class="bi-plus"></i> <?= Translate::get('read'); ?>
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