<?php if (!empty($data['dialogs'])) : ?>
  <main class="max">
    <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
    <div class="box sticky">
      <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.choose_dialogue'), 'icon' => 'mail']); ?>
    </div>
  </main>
<?php else : ?>
  <div class="w-100">
    <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
  </div>
<?php endif; ?>