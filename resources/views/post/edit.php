<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h1><?= $data['h1']; ?></h1>

         <div class="box create">
            <form action="/post/editpost/<?= $post['post_id']; ?>" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Заголовок</label>
                    <input class="add" type="text" value="<?= htmlspecialchars($post['post_title']); ?>" name="post_title" />
                    <br />
                </div>   
                <?php if($uid['trust_level'] == 5) { ?>
                    <div class="boxline">
                        <label for="post_title">URL</label>
                        <input class="add" type="text" value="<?= htmlspecialchars($post['post_url']); ?>" name="post_url" />
                        <br />
                    </div> 
                <?php } ?>
                <div class="boxline">
                    <label for="post_content">Текст</label> <br />
                    <div class="qa-mod qa-editor-box">
                        <div class="mod-head">
                            <div class="wmd-panel">
                                <div id="questionText" class="editor liveMode">
                                    <div class="editor-toolbar">
                                      <div class="editor-mode"></div>
                                      <div id="wmd-button-bar"></div>
                                    </div>
                                    <div class="wmd">
                                      <textarea id="wmd-input" name="post_content" placeholder=""><?= $post['post_content']; ?></textarea>
                                    </div>
                                    <div class="editor-preview"><div class="fmt" id="wmd-preview"></div></div>
                                </div>
                                <link rel="stylesheet" href="/assets/js/editor/editor.css"> 
                                <script src="/assets/js/editor/marked.js"></script>
                                <script src="/assets/js/editor/markdown.editor.js"></script>
                            </div>
                        </div>
                    </div>   
                </div>
                <?php if($uid['trust_level'] == 5) { ?>
                    <div class="boxline"> 
                        <label for="post_content">Закрыть</label>
                        <input type="radio" name="closed" <?php if($post['post_closed'] == 0) { ?>checked<?php } ?> value="0"> Нет
                        <input type="radio" name="closed" <?php if($post['post_closed'] == 1) { ?>checked<?php } ?> value="1" > Да
                    </div>  
                    <div class="boxline">
                        <label for="post_content">Поднять</label>
                        <input type="radio" name="top" <?php if($post['post_top'] == 0) { ?>checked<?php } ?> value="0"> Нет
                        <input type="radio" name="top" <?php if($post['post_top']== 1) { ?>checked<?php } ?> value="1"> Да
                    </div>                      
                <?php } ?>
                <div class="boxline">  
                    <label for="post_content">Пространство</label>
                    <?php foreach ($space as $sp) { ?>
                        
                        <input type="radio" name="space_id" value="<?= $sp['space_id']; ?>"<?php if($post['space_id'] == $sp['space_id']) { ?> checked<?php } ?>><?= $sp['space_name']; ?>
                       
                    <?php } ?>
                    <br> 
                </div>
                <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                <input type="submit" name="submit" value="Изменить" />
            </form>
        </div>

    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 