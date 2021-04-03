$(function(){
    var converter1 = {
        makeHtml: function (str) {
            return marked(str)
        }
    }
    var editor1 = new Markdown.Editor(converter1);
    editor1.run();
});