<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('web'),
    Translate::get('sites'),
    getUrlByName('web.website', ['slug' => $data['domain']['link_url_domain']]),
    $data['domain']['link_title'],
    Translate::get('change the site')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName('web.edit.pr'); ?>" method="post">
      <?= csrf_field() ?>
      <div class="right">
        <?= favicon_img($data['domain']['link_id'], $data['domain']['link_url_domain']); ?>
        <span class="add-favicon right size-13" data-id="<?= $data['domain']['link_id']; ?>">
          + favicon
        </span>
      </div>
      <div class="mb20 max-w780">
        <label for="post_title">Id:</label>
        <?= $data['domain']['link_id']; ?> (<?= $data['domain']['link_url_domain']; ?>)
      </div>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('URL'),
            'type' => 'text', 'name' => 'link_url',
            'value' => $data['domain']['link_url']
          ],
          [
            'title' => Translate::get('status'),
            'type' => 'text',
            'name' => 'link_status',
            'value' => $data['domain']['link_status']
          ],
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'link_title',
            'value' => $data['domain']['link_title'],
            'help' => '14 - 250 ' . Translate::get('characters') . ' («Газета.Ru» — интернет-газета)'
          ],
        ]
      ]); ?>

      <div class="mb20">
        <label for="topic_content"><?= Translate::get('posted'); ?>?</label>
        <input type="radio" name="link_published" <?php if ($data['domain']['link_published'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
        <input type="radio" name="link_published" <?php if ($data['domain']['link_published'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
        <div class="size-14 gray-light-2"><?= Translate::get('posted-help'); ?></div>
      </div>

      <?php includeTemplate('/_block/editor/textarea', [
        'title' => Translate::get('description'),
        'type' => 'text',
        'name' => 'link_content',
        'content' => $data['domain']['link_content'],
        'min' => 24,
        'max' => 1500,
        'help' => '24 - 1500 ' . Translate::get('characters')
      ]); ?>

      <?= includeTemplate('/_block/form/select-facet-post', [
        'uid'           => $uid,
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'topic',
        'title'         => Translate::get('topics'),
        'required'      => false,
        'maximum'       => 3,
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <input type="hidden" name="link_id" value="<?= $data['domain']['link_id']; ?>">
      <?= sumbit(Translate::get('edit')); ?>
    </form>
  </div>
</main>