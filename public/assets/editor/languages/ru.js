(function () {
    var factory = function (exports) {
        var lang = {
            name: "ru",
            description: "Поддерживает Markdown разметку",
            tocTitle: "Каталог",
            toolbar: {
                undo: "Отменить （Ctrl+Z）",
                redo: "Переделать （Ctrl+Y）",
                bold: "Жирный",
                del: "Зачеркнуть",
                italic: "Курсив",
                quote: "Цитата",
                ucwords: "Заглавные буквы парвые буквы",
                uppercase: "В верхний регист",
                lowercase: "В нижний регистр",
                h3: "Заголовок H3",
                h4: "Заголовок H4",
                "list-ul": "Неупорядоченный список",
                "list-ol": "Упорядоченный список",
                hr: "Линия",
                link: "Ссылка",
                "reference-link": "Ссылка",
                image: "Фото",
                code: "Код",
                "preformatted-text": "Предварительно отформатированные блоки текста / кода(стиль с отступом)",
                "code-block": "Блоки кода (многоязычный стиль)",
                table: "Таблица",
                datetime: "Дата и время",
                emoji: "Emoji",
                "html-entities": "Символы сущностей HTML",
                pagebreak: "Вставка разрывов страниц",
                "goto-line": "Перейти к строке",
                watch: "Выключить предпросмот",
                unwatch: "Предпросмот",
                preview: "Предпросмотр HTML в полном окне (Shift + ESC для восстановления)",
                fullscreen: "Полный экран (ESC восстановить)",
                clear: "Очистить",
                search: "Поиск",
                help: "Помощь",
                info: "Об " + editormd.title
            },
            buttons: {
                enter: "Применить",
                cancel: "Отменить",
                close: "Закрыть"
            },
            dialog: {
                link: {
                    title: "Добавьте ссылку",
                    url: "URL",
                    urlTitle: "Название",
                    urlEmpty: "Пожалуйста, заполните адрес ссылки"
                },
                referenceLink: {
                    title: "Добавьте справочную ссылку",
                    name: "Примечание",
                    url: "URL",
                    urlId: "ID",
                    urlTitle: "Название",
                    nameEmpty: "Имя ссылки не может быть пустым",
                    idEmpty: "Заполните справочную ссылку ",
                    urlEmpty: "Заполните URL-адрес ссылки "
                },
                image: {
                    title: "Добавьте изображение",
                    url: "URL",
                    link: "Ссылка",
                    alt: "Описание",
                    uploadButton: "Загрузить",
                    imageURLEmpty: "Адрес изображения не может быть пустым",
                    uploadFileEmpty: "Изображение не может быть пустым",
                    formatNotAllowed: "Разрешено загружать только： "
                },
                preformattedText: {
                    title: "Добавьте предварительно отформатированный текст или блоки кода",
                    emptyAlert: "Заполните содержимое предварительно отформатированного текста или кода."
                },
                codeBlock: {
                    title: "Блок кода",
                    selectLabel: "Язык：",
                    selectDefaultText: "Выберите язык",
                    otherLanguage: "Другие языки",
                    unselectedLanguageAlert: "Выберите тип языка, к которому относится код.",
                    codeEmptyAlert: "Заполните содержимое кода"
                },
                htmlEntities: {
                    title: "HTML символы"
                },
                help: {
                    title: "Помощь"
                }
            }
        };

        exports.defaults.lang = lang;
    };


    factory(window.editormd);


})();