<div class="redactor">
    <?php if (!empty($post['post_content'])) { ?>
        <textarea class="editable" name="post_content" placeholder=""><?= $post['post_content']; ?></textarea>
    <?php } else { ?>
        <textarea class="editable" name="post_content" placeholder=""></textarea>
    <?php } ?>
</div> 