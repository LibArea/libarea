<div class="redactor">
    <div id="test-markdown-view">
        <?php if (!empty($post['post_content'])) { ?>
            <textarea name="post_content" minlength="6"><?= $post['post_content']; ?></textarea>
        <?php } else { ?>
            <textarea name="post_content" minlength="6"></textarea>
        <?php } ?>
    </div> 
</div> 

