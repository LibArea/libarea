<!-- mdhtmlform Dependencies      
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/mdedit/plugins.js"></script>

<script src="/assets/js/mdedit/vendor/showdown.js"></script> 
<script src="/assets/js/mdedit/mdhtmlform.js"></script> 
-->
<div class="qa-mod qa-editor-box">
    <div class="mod-head">
        <div class="wmd-panel">
            <div id="questionText" class="editor liveMode">
                <div class="editor-toolbar">
                  <div class="editor-mode"></div>
                  <div id="wmd-button-bar"></div>
                </div>
                <div class="wmd">
                    <?php if (!empty($post['post_content'])) { ?>
                        <textarea id="wmd-input" name="post_content" placeholder=""><?= $post['post_content']; ?></textarea>
                    <?php } else { ?>
                        <textarea id="wmd-input" name="post_content" placeholder=""></textarea>
                    <?php } ?>
                </div>
                <div class="editor-preview"><div class="fmt" id="wmd-preview"></div></div>
            </div>
            <link rel="stylesheet" href="/assets/js/editor/editor.css"> 
            <script src="/assets/js/editor/marked.js"></script>
            <script src="/assets/js/editor/markdown.editor.js"></script>
        </div>
    </div>
</div>         
       
 