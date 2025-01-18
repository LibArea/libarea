<aside>
  <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
</aside>
<?php if (!empty($data['dialogs'])) : ?>
  <main>
    <div class="box sticky top-sm">
      <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.choose_dialogue'), 'icon' => 'mail']); ?>
    </div>
  </main>
<?php endif; ?>