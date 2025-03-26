<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?php if (!empty($data['showing-blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'blog'       => $data['blog'],
    'action'  => 'add',
    'title'    => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<fieldset>
  <label for="post_title"><?= __('app.heading'); ?> <sup class="red">*</sup></label>
  <input id="title" type="text" required="" name="title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <div class="mb5"><?= __('app.text'); ?> Q&A <sup class="red">*</sup></div>
  <textarea name="content_qa"></textarea>
  <div class="help"><?= __('app.necessarily'); ?></div>
</fieldset>


<details class="mt15">
  <summary><?= __('app.other'); ?></summary>

  <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_draft'))) : ?>
    <fieldset>
      <input type="checkbox" name="draft"> <?= __('app.draft_post'); ?>
    </fieldset>
  <?php endif; ?>

  <?= insert('/_block/form/content-tl', ['data' => null]); ?>

  <fieldset>
    <input type="checkbox" name="closed"> <?= __('app.post_closed'); ?>
  </fieldset>

  <fieldset>
    <input type="checkbox" name="translation"> <?= __('app.post_translation'); ?>
  </fieldset>

  <?php if ($container->user()->admin()) : ?>
    <fieldset>
      <input type="checkbox" name="top"> <?= __('app.pin'); ?>
    </fieldset>
  <?php endif; ?>

  <?php if (config('feed', 'nsfw')) : ?>
    <fieldset>
      <input type="checkbox" name="nsfw"> <?= __('app.nsfw_post'); ?>
    </fieldset>
  <?php endif; ?>

  <fieldset>
    <input type="checkbox" name="hidden"> <?= __('app.hidden_post'); ?>
    <div class="help"><?= __('app.hidden_post_help'); ?></div>
  </fieldset>

  <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_poll')) && $data['count_poll']) : ?>
    <?= insert('/_block/form/select/poll', ['poll' => false]); ?>
  <?php endif; ?>

  <?= insert('/_block/form/select/related-posts'); ?>

</details>

<p><?= Html::sumbit(__('app.create')); ?></p>