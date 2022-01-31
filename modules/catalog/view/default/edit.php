<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<main class="col-span-12 mb-col-12">
  <div class="bg-white items-center justify-between p15">

    <a href="<?= getUrlByName('web.website', ['slug' => $data['domain']['item_url_domain']]); ?>"><?= $data['domain']['item_title_url']; ?></a> /
    <span class="red-500">
      <?= sprintf(Translate::get('edit.option'), Translate::get('website')); ?>
    </span>

    <form action="<?= getUrlByName('web.edit.pr'); ?>" method="post">
      <?= csrf_field() ?>
      <div class="right">
        <?= website_img($data['domain']['item_url_domain'], 'favicon', $data['domain']['item_url_domain'], 'mr5 w20 h20'); ?>
        <span class="add-favicon right text-sm" data-id="<?= $data['domain']['item_id']; ?>">
          + favicon
        </span>
      </div>
      <div class="mb20 max-w780">
        <label for="post_title">Id:</label>
        <?= $data['domain']['item_id']; ?> (<?= $data['domain']['item_url_domain']; ?>)
      </div>

      <?= Tpl::insert('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('URL'),
            'type'  => 'text', 'name' => 'item_url',
            'value' => $data['domain']['item_url']
          ],
          [
            'title' => Translate::get('status'),
            'type'  => 'text',
            'name'  => 'item_status_url',
            'value' => $data['domain']['item_status_url']
          ],
          [
            'title' => Translate::get('title'),
            'type'  => 'text',
            'name'  => 'item_title_url',
            'value' => $data['domain']['item_title_url'],
            'help'  => '14 - 250 ' . Translate::get('characters') . ' («Газета.Ru» — интернет-газета)'
          ],
        ]
      ]); ?>

      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title'   => Translate::get('posted') . '?',
            'name'    => 'item_published',
            'checked' => $data['domain']['item_published'],
            'help'    => Translate::get('posted-help'),
          ],
        ]
      ]); ?>

      <?php Tpl::insert('/_block/editor/textarea', [
        'title'     => Translate::get('description'),
        'type'      => 'text',
        'name'      => 'item_content_url',
        'content'   => $data['domain']['item_content_url'],
        'min'       => 24,
        'max'       => 1500,
        'help'      => '24 - 1500 ' . Translate::get('characters')
      ]); ?>


      <?= Tpl::insert('/_block/form/select/select', [
        'data'      => $data,
        'action'    => 'edit',
        'type'      => 'topic',
        'title'     => Translate::get('topics'),
        'help'      => Translate::get('necessarily'),
        'red'       => 'red'
      ]); ?>

      <h3 class="mb5"><?= Translate::get('soft'); ?></h3>
      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title'   => Translate::get('there.program'),
            'name'    => 'item_is_soft',
            'checked' => $data['domain']['item_is_soft'],
          ],
          [
            'title'   => Translate::get('hosted.github'),
            'name'    => 'item_is_github',
            'checked' => $data['domain']['item_is_github'],
          ],
        ]
      ]); ?>

      <?= Tpl::insert('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('url.address.github'),
            'type'  => 'text',
            'name'  => 'item_github_url',
            'value' => $data['domain']['item_github_url'],
          ],
          [
            'title' => Translate::get('title'),
            'type'  => 'text',
            'name'  => 'item_title_soft',
            'value' => $data['domain']['item_title_soft'],
          ],
        ]
      ]); ?>

      <?php Tpl::insert('/_block/editor/textarea', [
        'title'     => Translate::get('description'),
        'type'      => 'text',
        'name'      => 'item_content_soft',
        'content'   => $data['domain']['item_content_soft'],
        'min'       => 24,
        'max'       => 1500,
        'help'      => '24 - 1500 ' . Translate::get('characters')
      ]); ?>

      <?= Tpl::insert('/_block/form/select/related-posts', [
        'data'      => $data,
        'action'    => 'edit',
        'type'      => 'post',
        'title'     => Translate::get('related posts'),
        'help'      => Translate::get('necessarily'),
      ]); ?>

      <input type="hidden" name="item_id" value="<?= $data['domain']['item_id']; ?>">
      <?= sumbit(Translate::get('edit')); ?>
    </form>
  </div>
</main>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>