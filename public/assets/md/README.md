#   Markdown-Editor

Online Markdown editor based on Pagedown 

*   Source code: https://github.com/ujifgc/pagedown
*   For the project: https://loriup.com/

##   Use 

```
<div class="wmd-panel">
    <div id="wmd-button-bar"></div>
    <textarea name="content" class="wmd-input" id="wmd-input"></textarea>
</div>
<div id="wmd-preview" class="wmd-panel wmd-preview"></div>
```

###  js + css

```
Request::getResources()->addBottomStyles('/assets/md/editor.css');  
Request::getResources()->addBottomScript('/assets/md/Markdown.Converter.js'); 
... /assets/md/Markdown.Sanitizer.js
... /assets/md/Markdown.Editor.js
... /assets/md/editor.js
```

MIT License
