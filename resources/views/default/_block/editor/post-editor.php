<div class="redactor mb20">
  <div id="markdown-view">
    <?php if ($post_id) { ?>
      <textarea name="post_content" minlength="6"><?= $conten; ?></textarea>
    <?php } else { ?>
      <textarea id="md-redactor" name="post_content" minlength="6"></textarea>
    <?php } ?>
  </div>
  <?= includeTemplate('/_block/editor/config-editor', ['post_id' => $post_id, 'type' => $type, 'width100' => 'yes']); ?>
</div>