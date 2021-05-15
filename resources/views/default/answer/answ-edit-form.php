<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
<script src="/assets/js/jquery.min.js"></script>
    <h1><?= $data['h1']; ?>: 
        <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
            <?= $post['post_title']; ?>
        </a>
    </h1>
    <div class="answ_addentry"> 
        <?php if ($data['user_id'] > 0) : ?>
            <form id="add_answ" action="/answer/edit" accept-charset="UTF-8" method="post">
                <?= csrf_field() ?>
                <div class="redactor">
                    <div class="wmd-panel">
                        <div id="wmd-button-bar"></div>
                        <textarea name="answer"  class="wmd-input h-150" id="wmd-input"><?= $data['answer_content']; ?></textarea>
                    </div> 
                    <div id="wmd-preview" class="wmd-panel wmd-preview"></div> 
                </div>

                <div class="clear"> 
                    <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
                    <input type="hidden" name="answ_id" id="answ_id" value="<?= $data['answ_id']; ?>">
                    <input type="submit" name="answit" value="<?= lang('Edit'); ?>" class="answer-post">
                </div> 
 
            </form>
              
        <?php endif; ?>
    </div>     
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 