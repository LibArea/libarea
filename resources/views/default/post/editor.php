<div class="redactor">
    <?php if (!empty($post['post_content'])) { ?>
        <textarea id="answer_100" name="post_content" placeholder=""><?= $post['post_content']; ?></textarea>
    <?php } else { ?>
        <textarea id="answer_100" name="post_content" placeholder=""></textarea>
    <?php } ?>
</div> 