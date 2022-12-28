<div class="w-40 mb-w-100">
  <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
</div>
<div class="w-60 mb-none">
  <div class="box bg-beige sticky top-sm">
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.choose_dialogue'), 'icon' => 'mail']); ?>
  </div>
</div>