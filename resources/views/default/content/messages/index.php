<div class="w-30 mb-w-100">
  <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
</div>
<div class="w-70 ml20 mb-none">
  <div class="box sticky top-sm">
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.choose_dialogue'), 'icon' => 'mail']); ?>
  </div>
</div>