<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h2><?php echo $data['h1']; ?></h2>

        <div class="box create">
            <form action="/post/create" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Заголовок</label>
                    <input id="title" class="add" type="text" name="post_title" /><br />
                </div>   
                <?php if($uid['trust_level'] == 5) { ?>
                    <div class="boxline">
                        <label for="post_title">URL</label>
                        <span class="w-50">
                            <input id="link" class="add" type="url" name="post_url" />
                        </span>
                        <span class="w-33">
                            <input id="graburl" type="submit_url" name="submit_url" value="Извлечь" />
                        </span>
                        <br>
                    </div> <?php } ?>
                    <div class="boxline">
                        <label for="post_title">Превью</label>
                            <script src="/assets/js/editor.js"></script>
                            <textarea class="content_preview" name="content_preview" placeholder=""></textarea>
                        <br />
                        <label for="post_title"></label>    
                        <div class="box_h">Около 160 символов</div>
                    </div>
                
                <div class="qa-mod qa-editor-box">
                    <div class="mod-head">
                        <div class="wmd-panel">
                            <div id="questionText" class="editor liveMode">
                                <div class="editor-toolbar">
                                  <div class="editor-mode"></div>
                                  <div id="wmd-button-bar"></div>
                                </div>
                                <div class="wmd">
                                  <textarea id="wmd-input" name="post_content" placeholder=""></textarea>
                                </div>
                                <div class="editor-preview"><div class="fmt" id="wmd-preview"></div></div>
                            </div>
                            <link rel="stylesheet" href="/assets/js/editor/editor.css"> 
                            <script src="/assets/js/editor/marked.js"></script>
                            <script src="/assets/js/editor/markdown.editor.js"></script>
                        </div>
                    </div>
                </div>         
           
                <div class="boxline">
                    <label for="post_content">Пространство</label>
                    <input type="radio" name="space" value="1" > cms
                    <input type="radio" name="space" value="2" > вопросы
                    <input type="radio" name="space" value="3" > флуд
                </div>
                <input type="submit" name="submit" value="Написать" />
            </form>
            <br>
            <details>
                <summary>Доступно форматирование Markdown</summary>
                <p> 
                <table>
                  <tbody><tr>
                    <td width="125"><em>курсив</em></td>
                    <td>окружить текст <tt>*звездочками*</tt></td>
                  </tr>
                  <tr>
                    <td><strong>жирный</strong></td>
                    <td>окружить текст <tt>**двумя звездочками**</tt></td>
                  </tr>
                  <tr>
                    <td><strike>зачеркнутый</strike></td>
                    <td>окружить текст <tt>~~двумя тильдами~~</tt></td>
                  </tr>
                  <tr>
                    <td><tt>од (строка)</tt></td>
                    <td>окружить текст <tt>`обратными ковычками`</tt></td>
                  </tr>
                  <tr>
                    <td><a href="http://example.com/" style="color: inherit;">связаный текст</a></td>
                    <td><tt>[связанный текст](http://example.com/)</tt> или просто URL-адрес для создания без заголовка</td>
                  </tr>
                  <tr>
                    <td><blockquote> цитата</blockquote></td>
                    <td><tt>&gt;</tt> текс цитаты </td>
                  </tr>
                  </tbody></table>
                </p>
            </details>
            <br>
        </div>
        
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 