<?php if (!empty($data['dialogs'])) : ?>
  <div class="w-100">
    <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
    <div class="box sticky top-sm">
      <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.choose_dialogue'), 'icon' => 'mail']); ?>
    </div>
  </div>
<?php else : ?>
  <div class="w-100">
    <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
  </div>
<?php endif; ?>