<div class="redactor">
    <div class="wmd-panel">
        <div id="wmd-button-bar"></div>
        <?php if (!empty($post['post_content'])) { ?>
            <textarea name="post_content"  class="wmd-input" minlength="6" id="wmd-input"><?= $post['post_content']; ?></textarea>
        <?php } else { ?>
            <textarea name="post_content" class="wmd-input" minlength="6" id="wmd-input"></textarea>
        <?php } ?>
    </div>
    <div id="wmd-preview" class="wmd-panel wmd-preview"></div> 
</div> 