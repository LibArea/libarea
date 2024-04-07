<fieldset>
  <label for="title"><?= __('web.title'); ?> <strong class="red">*</strong></label>
  <input id="title" name="title" required="" type="text" value="">
  <div class="help">14 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="url">URL <strong class="red">*</strong></label>
  <input id="find-url" name="url" autocomplete="off" required="" type="text" value="">
  <div class="box-results none" id="search_url"></div>
</fieldset>

<fieldset>
  <label for="content"><?= __('web.description'); ?> <strong class="red">*</strong></label>
  <textarea id="content" name="content" rows="5" required=""></textarea>
  <div class="help">> 24 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <input type="checkbox" name="close_replies"> <?= __('web.deny_replies'); ?>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <input type="checkbox" name="published"> <?= __('web.posted'); ?>
  </fieldset>
<?php endif; ?>

<?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_poll'))) : ?>
  <?= insert('/_block/form/select/poll', ['poll' => false]); ?>
<?php endif; ?>  