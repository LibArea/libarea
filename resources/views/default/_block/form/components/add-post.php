<fieldset>
  <label for="post_title"><?= __('app.heading'); ?> <sup class="red">*</sup></label>
  <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<?= insert('/_block/form/select/select', [
  'data'          => $data['facets'],
  'type'          => 'topic',
  'action'        => 'add',
  'title'         => __('app.facets'),
  'help'          => __('app.necessarily'),
  'red'           => 'red'
]); ?>

<?php if (!empty($data['blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'data'        => $data,
    'action'      => 'add',
    'type'        => 'blog',
    'title'       => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<?php if (UserData::getRegType(config('trust-levels.tl_add_url'))) : ?>
  <fieldset>
    <div class="left w-70">
      <input id="link" placeholder="URL" class="post_url" type="text" name="post_url" />
    </div>
    <div class="left w-30 pl5">
      <input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('app.to_extract'); ?>" />
    </div>
  </fieldset>
<?php endif; ?>

<div class="file-upload" id="file-drag">
  <div class="flex">
    <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-gray">
    <div id="start">
      <input id="file-upload" type="file" name="images" accept="image/*" />
      <div class="text-sm gray-600 mt5">
        <?= __('app.format_cover_post'); ?>.
      </div>
      <i class="fa fa-download" aria-hidden="true"></i>
      <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
    </div>
  </div>
  <div id="response" class="hidden">
    <div id="messages"></div>
  </div>
</div>

<?= insert('/_block/form/editor', [
    'autosave'  => 'addPostId',
    'title'     => __('app.post'),
    'height'    => '250px',
     'type'     => 'post-telo',
     'id'       => 0,
     'cut'      => true
     ]); ?>

<?php if (UserData::getRegType(UserData::USER_FIRST_LEVEL)) : ?>
  <fieldset>
    <input type="checkbox" name="post_draft"> <?= __('app.draft_post'); ?>?
  </fieldset>


  <?= insert('/_block/form/select/content-tl', ['data' => null]); ?>

  <fieldset>
    <input type="checkbox" name="post_feature"> <?= __('app.format_Q&A'); ?>?
  </fieldset>

  <fieldset>
    <input type="checkbox" name="closed"> <?= __('app.post_closed'); ?>?
  </fieldset>

<?php endif; ?>

<fieldset>
  <input type="checkbox" name="translation"> <?= __('app.post_translation'); ?>?
</fieldset>

<?php if (UserData::checkAdmin()) : ?>
   <fieldset>
    <input type="checkbox" name="top"> <?= __('app.pin'); ?>?
   </fieldset>
<?php endif; ?>

<?= insert('/_block/form/select/related-posts', [
  'data'          => [],
  'action'        => 'add',
  'type'          => 'post',
  'title'         => __('app.related_posts'),
  'help'          => __('app.necessarily'),
]); ?>

<p><?= Html::sumbit(__('app.create')); ?></p>