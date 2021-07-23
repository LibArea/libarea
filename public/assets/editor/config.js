
var editor = editormd("test-markdown-view", {
    width: "100%",
    height: "400px",
    emoji                : true, 
    toolbarIcons  : [
        "bold", "italic", "del", "quote",  "h3", "list-ul", "|",  "hr", "image",  "link", "|", "code", "preformatted-text", "|", "fullscreen", "preview", "help",
    ],
    
    toolbarCustomIcons   : {               // using html tag create toolbar icon, unused default <a> tag.
        quote: "<a href=\"javascript:;\" title=\"Цитата\" unselectable=\"on\">«»</a>",
        del: "<a href=\"javascript:;\" title=\"Зачеркнуть\" unselectable=\"on\"><i class=\"fa\" name=\"del\">~</i></a>" 
    },

    // imageUpload: true,
    // imageFormats : ["jpg","jpeg","gif","png","bmp","webp"],
    // imageUploadURL:"{{url('backend/uploadimage')}}",

    toolbarIconsClass    : {
        undo             : "fa-undo",
        redo             : "fa-repeat",
        bold             : "light-icon-bold",
        del              : "fa-strikethrough",
        italic           : "light-icon-italic",
        quote            : "icon bubble",
        uppercase        : "fa-font",
        h3               : editormd.classPrefix + "bold",
        h4               : editormd.classPrefix + "bold",
        "list-ul"        : "light-icon-list",
        "list-ol"        : "fa-list-ol",
        hr               : "light-icon-separator",
        link             : "light-icon-link",
        "reference-link" : "fa-anchor",
        image            : "light-icon-camera",
        code             : "light-icon-code",
        "preformatted-text" : "light-icon-terminal",
        "code-block"     : "fa-file-code-o",
        table            : "fa-table",
        datetime         : "fa-clock-o",
        emoji            : "icon crop",
        "html-entities"  : "fa-copyright",
        pagebreak        : "fa-newspaper-o",
        "goto-line"      : "fa-terminal", // fa-crosshairs
        watch            : "fa-eye-slash",
        unwatch          : "fa-eye",
        preview          : "light-icon-device-desktop",
        search           : "fa-search",
        fullscreen       : "light-icon-arrows-maximize",
        clear            : "fa-eraser",
        help             : "light-icon-info-square",
        info             : "fa-info-circle"
    },        
    toolbarIconTexts     : {},

    lang : {
        name        : "ru",
        description : "Поддерживает Markdown разметку",
        tocTitle    : "Каталог",
        toolbar     : {
            undo             : "Отменить （Ctrl+Z）",
            redo             : "Переделать （Ctrl+Y）",
            bold             : "Жирный",
            del              : "Зачеркнуть",
            italic           : "Курсив",
            quote            : "Цитата",
            ucwords          : "Заглавные буквы парвые буквы",
            uppercase        : "В верхний регист",
            lowercase        : "В нижний регистр",
            h3               : "Заголовок H3",
            h4               : "Заголовок H4",
            "list-ul"        : "Неупорядоченный список",
            "list-ol"        : "Упорядоченный список",
            hr               : "Линия",
            link             : "Ссылка",
            "reference-link" : "Ссылка",
            image            : "Фото",
            code             : "Код",
            "preformatted-text" : "Предварительно отформатированные блоки текста / кода(стиль с отступом)",
            "code-block"     : "Блоки кода (многоязычный стиль)",
            table            : "Таблица",
            datetime         : "Дата и время",
            emoji            : "Emoji",
            "html-entities"  : "Символы сущностей HTML",
            pagebreak        : "Вставка разрывов страниц",
            "goto-line"      : "Перейти к строке",
            watch            : "Выключить предпросмот",
            unwatch          : "Предпросмот",
            preview          : "Предпросмотр HTML в полном окне (Shift + ESC для восстановления)",
            fullscreen       : "Полный экран (ESC восстановить)",
            clear            : "Очистить",
            search           : "Поиск",
            help             : "Помощь",
            info             : "Об " + editormd.title
        },
        buttons : {
            enter  : "Применить",
            cancel : "Отменить",
            close  : "Закрыть"
        },
        dialog : {
            link : {
                title    : "Добавьте ссылку",
                url      : "URL",
                urlTitle : "Название",
                urlEmpty : "Пожалуйста, заполните адрес ссылки"
            },
            referenceLink : {
                title    : "Добавьте справочную ссылку",
                name     : "Примечание",
                url      : "URL",
                urlId    : "ID",
                urlTitle : "Название",
                nameEmpty: "Имя ссылки не может быть пустым",
                idEmpty  : "Заполните справочную ссылку ",
                urlEmpty : "Заполните URL-адрес ссылки "
            },
            image : {
                title    : "Добавьте изображение",
                url      : "URL",
                link     : "Ссылка",
                alt      : "Описание",
                uploadButton     : "С компа",
                imageURLEmpty    : "Адрес изображения не может быть пустым",
                uploadFileEmpty  : "Изображение не может быть пустым",
                formatNotAllowed : "Разрешено загружать только： "
            },
            preformattedText : {
                title             : "Добавьте предварительно отформатированный текст или блоки кода", 
                emptyAlert        : "Заполните содержимое предварительно отформатированного текста или кода."
            },
            codeBlock : {
                title             : "Блок кода",                    
                selectLabel       : "Язык：",
                selectDefaultText : "Выберите язык",
                otherLanguage     : "Другие языки",
                unselectedLanguageAlert : "Выберите тип языка, к которому относится код.",
                codeEmptyAlert    : "Заполните содержимое кода"
            },
            htmlEntities : {
                title : "HTML символы"
            },
            help : {
                title : "Помощь"
            }       
        }    
     },
    path : "/assets/editor/lib/"
});
