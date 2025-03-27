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