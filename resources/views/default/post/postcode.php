<div class="shown_post">
    <?php if($post['post_content_img']) { ?> 
        <div class="img-post-bl">
            <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/posts/<?= $post['post_content_img']; ?>">
        </div>    
    <?php } ?>

    <?php if($post['post_thumb_img']) { ?> 
        <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
    <?php } ?>
                            
    <?= $post['post_content']; ?>
</div>