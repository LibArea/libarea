    var modhead = document.querySelector('.mod-head');
    var converter1 = {
        makeHtml: function (str) {
            return marked(str)
        }
    }
     
    if(modhead) {
        var editor1 = new Markdown.Editor(converter1);
        editor1.run();
    }
     
