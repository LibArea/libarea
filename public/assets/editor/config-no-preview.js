var editor = editormd("test-markdown-view-post", {
    height          : "200px",
    readOnly        : false,
    watch           : false,       // disable watch
 // lineNumbers     : false, 
    autoFocus       : false,
 
    toolbarIcons  : [
        "bold", "italic", "del", "quote",  "h3", "list-ul", "|",  "hr", "image",  "link", "|", "code", "code-block", "|", "fullscreen", "help",
    ],
    
     imageUpload: true,
     imageFormats : ["jpg","jpeg","gif","png","webp"],
     imageUploadURL: "/backend/uploadimage", //"{{url('/backend/uploadimage')}}",

    toolbarIconsClass    : {
        bold             : "icon-bold",
        del              : "icon-strike",
        italic           : "icon-italic",
        quote            : "icon-quote",
        h3               : editormd.classPrefix + "icon-message-circle",
        "list-ul"        : "icon-list-bullet",
        hr               : "icon-minus-outline",
        link             : "icon-link",
        image            : "icon-camera-outline",
        code             : "icon-terminal",
        "code-block"     : "icon-file-code",
        preview          : "icon-device-desktop",
        search           : "fa-search",
        fullscreen       : "icon-move",
        help             : "icon-info",
        info             : "fa-info-circle"
    },        
    toolbarIconTexts     : {},

    lang : {
        name        : "ru",
        description : "Поддерживает Markdown разметку",
        tocTitle    : "Каталог",
        toolbar     : {
            bold             : "Жирный",
            del              : "Зачеркнуть",
            italic           : "Курсив",
            quote            : "Цитата",
            h3               : "Заголовок H3",
            "list-ul"        : "Неупорядоченный список",
            hr               : "Линия",
            link             : "Ссылка",
            "reference-link" : "Ссылка",
            image            : "Фото",
            code             : "Код",
            "code-block"     : "Блоки кода (многоязычный стиль)",
            preview          : "Предпросмотр HTML в полном окне (Shift + ESC для восстановления)",
            fullscreen       : "Полный экран (ESC восстановить)",
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
                uploadButton     : "Загрузить",
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
