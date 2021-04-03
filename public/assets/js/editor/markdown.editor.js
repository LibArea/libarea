(function() {
  var tabIndent = {
    version: '0.1.8',
    config: {
      tab: '\t'
    },
    events: {
      keydown: function(e) {
        var tab = tabIndent.config.tab;
        var tabWidth = tab.length;
        if (e.keyCode === 9) {
          e.preventDefault();
          var currentStart = this.selectionStart,
            currentEnd = this.selectionEnd;
          if (e.shiftKey === false) {
            // Normal Tab Behaviour
            if (!tabIndent.isMultiLine(this)) {
              // Add tab before selection, maintain highlighted text selection
              this.value = this.value.slice(0, currentStart) + tab + this.value.slice(currentStart);
              this.selectionStart = currentStart + tabWidth;
              this.selectionEnd = currentEnd + tabWidth;
            } else {
              // Iterating through the startIndices, if the index falls within selectionStart and selectionEnd, indent it there.
              var startIndices = tabIndent.findStartIndices(this),
                l = startIndices.length,
                newStart = undefined,
                newEnd = undefined,
                affectedRows = 0;

              while(l--) {
                var lowerBound = startIndices[l];
                if (startIndices[l+1] && currentStart != startIndices[l+1]) lowerBound = startIndices[l+1];

                if (lowerBound >= currentStart && startIndices[l] < currentEnd) {
                  this.value = this.value.slice(0, startIndices[l]) + tab + this.value.slice(startIndices[l]);

                  newStart = startIndices[l];
                  if (!newEnd) newEnd = (startIndices[l+1] ? startIndices[l+1] - 1 : 'end');
                  affectedRows++;
                }
              }

              this.selectionStart = newStart;
              this.selectionEnd = (newEnd !== 'end' ? newEnd + (tabWidth * affectedRows) : this.value.length);
            }
          } else {
            // Shift-Tab Behaviour
            if (!tabIndent.isMultiLine(this)) {
              if (this.value.substr(currentStart - tabWidth, tabWidth) == tab) {
                // If there's a tab before the selectionStart, remove it
                this.value = this.value.substr(0, currentStart - tabWidth) + this.value.substr(currentStart);
                this.selectionStart = currentStart - tabWidth;
                this.selectionEnd = currentEnd - tabWidth;
              } else if (this.value.substr(currentStart - 1, 1) == "\n" && this.value.substr(currentStart, tabWidth) == tab) {
                // However, if the selection is at the start of the line, and the first character is a tab, remove it
                this.value = this.value.substring(0, currentStart) + this.value.substr(currentStart + tabWidth);
                this.selectionStart = currentStart;
                this.selectionEnd = currentEnd - tabWidth;
              } else if (/\n[ ]+$/.test(this.value.substr(currentStart - tabWidth, tabWidth))) {
                // However, if the selection is at the start with a line and spaces, remove spaces
                var newline = this.value.substring(0, currentStart).lastIndexOf('\n') + 1;
                this.value = this.value.substring(0, newline) + this.value.substr(currentStart);
                this.selectionStart = newline;
                this.selectionEnd = currentEnd - currentStart + newline;
              }
            } else {
              // Iterating through the startIndices, if the index falls within selectionStart and selectionEnd, remove an indent from that row
              var startIndices = tabIndent.findStartIndices(this),
                l = startIndices.length,
                newStart = undefined,
                newEnd = undefined,
                affectedRows = 0;

              while(l--) {
                var lowerBound = startIndices[l];
                if (startIndices[l+1] && currentStart != startIndices[l+1]) lowerBound = startIndices[l+1];

                if (lowerBound >= currentStart && startIndices[l] < currentEnd) {
                  if (this.value.substr(startIndices[l], tabWidth) == tab) {
                    // Remove a tab
                    this.value = this.value.slice(0, startIndices[l]) + this.value.slice(startIndices[l] + tabWidth);
                    affectedRows++;
                  } else if (this.value.substr(startIndices[l], tabWidth) == tab) {
                    // Remove a tab
                    this.value = this.value.slice(0, startIndices[l]) + this.value.slice(startIndices[l] + tabWidth);
                    affectedRows++;
                  } else if (/^[ ]+/.test(this.value.substr(startIndices[l], tabWidth))) {
                    // If line start with a line and spaces, remove spaces
                    this.value = this.value.slice(0, startIndices[l]) + this.value.slice(startIndices[l]).replace(/^[ ]+/, '');
                    affectedRows++;
                  } else {} // Do nothing

                  newStart = startIndices[l];
                  if (!newEnd) newEnd = (startIndices[l+1] ? startIndices[l+1] - 1 : 'end');
                }
              }

              this.selectionStart = newStart;
              this.selectionEnd = (newEnd !== 'end' ? newEnd - (affectedRows * tabWidth) : this.value.length);
            }
          }
        } else if (e.keyCode === 27) {  // Esc
          tabIndent.events.disable(e);
        } else if (e.keyCode === 13 && e.shiftKey === false) {  // Enter
          var self = tabIndent,
            cursorPos = this.selectionStart,
            startIndices = self.findStartIndices(this),
            numStartIndices = startIndices.length,
            startIndex = 0,
            endIndex = 0,
            tabMatch = new RegExp("^" + tab.replace('\t', '\\t').replace(/ /g, '\\s') + "+", 'g'),
            lineText = '';
            tabs = null;

          for(var x=0;x<numStartIndices;x++) {
            if (startIndices[x+1] && (cursorPos >= startIndices[x]) && (cursorPos < startIndices[x+1])) {
              startIndex = startIndices[x];
              endIndex = startIndices[x+1] - 1;
              break;
            } else {
              startIndex = startIndices[numStartIndices-1];
              endIndex = this.value.length;
            }
          }

          lineText = this.value.slice(startIndex, endIndex);
          tabs = lineText.match(tabMatch);
          if (tabs !== null) {
            e.preventDefault();
            var indentText = tabs[0];
            var indentWidth = indentText.length;
            var inLinePos = cursorPos - startIndex;
            if (indentWidth > inLinePos) {
              indentWidth = inLinePos;
              indentText = indentText.slice(0, inLinePos);
            }
            
            this.value = this.value.slice(0, cursorPos) + "\n" + indentText + this.value.slice(cursorPos);
            this.selectionStart = cursorPos + indentWidth + 1;
            this.selectionEnd = this.selectionStart;
          }
        }
      },
      disable: function(e) {
        var events = this;

        // Temporarily suspend the main tabIndent event
        tabIndent.remove(e.target);
      },
      focus: function() {
        var self = tabIndent,
          el = this,
          delayedRefocus = setTimeout(function() {
            var classes = (el.getAttribute('class') || '').split(' '),
            contains = classes.indexOf('tabIndent');

            el.addEventListener('keydown', self.events.keydown);
            // el.style.backgroundImage = "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAAAEgAAABIAEbJaz4AAAKZSURBVEjH7ZRfSFNRHMe/9+/+3G26tUn+ycywgURgUBAUJlIhWlEQEjN8yQcfolKJxJAefOjRCnT0IPYQ9iRa9FAYJiaUVP4twf7gzJzpnDbdzHt3z+3Fua3dO4Ne/f5ezjmc8+F7zvmeA2zrv0VFGlexAssFw1mG1pqqUL8npGY60Bw3ykYaOVjlrFXmEyw0AQj6g53UONQBO8DBzuiT2tUx+gR/mwACBQpIUoACBZoAZaOSiWwFIFs4oMMS9/boZVF8T8vtkbEofatiRKF9mXK6M7tTyyxRaPwWtJezIu9+9cNzxHk/n9938rz6IWpvgRdZd5/HcsvC9jadqk6Z0qkBiCaAF3UtX8cy6h1mwlnLhsuZuRvqABlyNJqb0q0ZWsb7uUVHlXAahWl1y3M2tVuQVR1Q0Pl0dwZ67KbZtGnX/ma++/FsCCY1ANlAxIuT2NZP3XB/GRKc9qKhKTYnd4auJbIqINEBDa5zoWWByoS1jocR+loKpKGJKqBLybN/OQN2Tmodv4jCtYIMYurnP5sLf+V5XK4DbFv4haaDCEABA/J88GdegD1I2+heY0Xj7M1itiMjP8srzutjXMbkIDZKCrAcfGOt8LwODimYnzzjLcHIx5VFwPekZrhVPYmxyVNAvZP8KV28SykClo6XF4/t9LpC2TTIteulJepJjD5nCjL8E56sMHt40NYYqE51ZnZIfmGXYBC68p/6v6UkApSI8Y2ejPVKhyE0PdLDPcg+Z003G0W7YUmmvo/WtjXgbiKAAQNGpjYRDOwWILx3dV16ZBsx3QsdYi4JNUw6uCvMbrUcWFAvPWznfH9/GQHR5xAbPuTumRFWvS+ZwDGyJFfidkxWk2oaIfTRk8RI0YqMAQBAL7YVrz/iUDx4QII4/QAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxMi0xMi0wMVQwMDowNjo0My0wNTowMLKpTWYAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTItMTItMDFUMDA6MDY6NDMtMDU6MDDD9PXaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAABJRU5ErkJggg==)";
            // el.style.backgroundPosition = 'top right';
            // el.style.backgroundRepeat = 'no-repeat';

            if (contains !== -1) classes.splice(contains, 1);
            classes.push('tabIndent-rendered');
            el.setAttribute('class', classes.join(' '));

            el.removeEventListener('focus', self.events.keydown);
          }, 500);

        // If they were just tabbing through the input, let them continue unimpeded
        el.addEventListener('blur', function b() {
          clearTimeout(delayedRefocus);
          el.removeEventListener('blur', b);
        });
      }
    },
    render: function(el) {
      var self = this;

      if (el.nodeName === 'TEXTAREA') {
        el.addEventListener('focus', self.events.focus);

        el.addEventListener('blur', function b(e) {
          self.events.disable(e);
        });
      }
    },
    renderAll: function() {
      // Find all elements with the tabIndent class
      var textareas = document.getElementsByTagName('textarea'),
        t = textareas.length,
        contains = -1,
        classes = [],
        el = undefined;

      while(t--) {
        classes = (textareas[t].getAttribute('class') || '').split(' ');
        contains = classes.indexOf('tabIndent');

        if (contains !== -1) {
          el = textareas[t];
          this.render(el);
        }
        contains = -1;
        classes = [];
        el = undefined;
      }
    },
    remove: function(el) {
      if (el.nodeName === 'TEXTAREA') {
        var classes = (el.getAttribute('class') || '').split(' '),
          contains = classes.indexOf('tabIndent-rendered');

        if (contains !== -1) {
          el.removeEventListener('keydown', this.events.keydown);
          el.style.backgroundImage = '';

          classes.splice(contains, 1);
          classes.push('tabIndent');
          el.setAttribute('class', (classes.length > 1 ? classes.join(' ') : classes[0]));
        }
      }
    },
    removeAll: function() {
      // Find all elements with the tabIndent class
      var textareas = document.getElementsByTagName('textarea'),
        t = textareas.length,
        contains = -1,
        classes = [],
        el = undefined;

      while(t--) {
        classes = (textareas[t].getAttribute('class') || '').split(' ');
        contains = classes.indexOf('tabIndent-rendered');

        if (contains !== -1) {
          el = textareas[t];
          this.remove(el);
        }
        contains = -1;
        classes = [];
        el = undefined;
      }
    },
    isMultiLine: function(el) {
      // Extract the selection
      var snippet = el.value.slice(el.selectionStart, el.selectionEnd),
        nlRegex = new RegExp(/\n/);

      if (nlRegex.test(snippet)) return true;
      else return false;
    },
    findStartIndices: function(el) {
      var text = el.value,
        startIndices = [],
        offset = 0;

      while(text.match(/\n/) && text.match(/\n/).length > 0) {
        offset = (startIndices.length > 0 ? startIndices[startIndices.length - 1] : 0);
        var lineEnd = text.search("\n");
        startIndices.push(lineEnd + offset + 1);
        text = text.substring(lineEnd + 1);
      }
      startIndices.unshift(0);

      return startIndices;
    }
  }

  var removeClass = function(element, className, className2) {
    if (typeof element === 'string') element = document.querySelectorAll(element)

    if ('[object Array][object NodeList]'.indexOf(Object.prototype.toString.call(element)) != -1) {
      for (var i = 0, len = element.length; i < len; i++) {
        removeClass(element[i], className, className2)
      }
    } else if (element) {
      var list = className.replace(/\s+/ig, ' ').split(' '),
        /* Attention: str need two spaces!! */
        str = (' ' + (element.className || '').replace(/(\s)/ig, '  ') + ' '),
        name,
        rex

      // 用list[j]移除str
      for (var j = 0, len2 = list.length; j < len2; j++) {
        name = list[j]
        name = name.replace(/(\*)/g, '\\S*').replace(/(\?)/g, '\\S?')
        rex = new RegExp(' ' + name + ' ', 'ig')
        str = str.replace(rex, ' ')
      }
      str += ' ' + (className2 || '')
      str = str.replace(/(\s)+/ig, ' ').replace(/(^\s+|\s+$)/ig, '')
      element.className = str
    }
    return element
  }
  
  window.Markdown = {}
  
  function identity(x) {
    return x;
  }

  function returnFalse(x) {
    return false;
  }
  
  function HookCollection() {}
  HookCollection.prototype = {
    chain: function(hookname, func) {
      var original = this[hookname];
      if (!original)
        throw new Error("unknown hook " + hookname);

      if (original === identity)
        this[hookname] = func;
      else
        this[hookname] = function(text) {
          var args = Array.prototype.slice.call(arguments, 0);
          args[0] = original.apply(null, args);
          return func.apply(null, args);
        };
    },
    set: function(hookname, func) {
      if (!this[hookname])
        throw new Error("unknown hook " + hookname);
      this[hookname] = func;
    },
    addNoop: function(hookname) {
      this[hookname] = identity;
    },
    addFalse: function(hookname) {
      this[hookname] = returnFalse;
    }
  };

  Markdown.HookCollection = HookCollection;

  // needs Markdown.Converter.js at the moment
  var util = {},
    position = {},
    ui = {},
    doc = window.document,
    re = window.RegExp,
    nav = window.navigator,
    SETTINGS = {
      lineLength: 72
    },
    // Used to work around some browser bugs where we can't use feature testing.
    uaSniffed = {
      isIE: /msie/.test(nav.userAgent.toLowerCase()),
      isIE_5or6: /msie 6/.test(nav.userAgent.toLowerCase()) || /msie 5/.test(nav.userAgent.toLowerCase()),
      isOpera: /opera/.test(nav.userAgent.toLowerCase())
    };
  var defaultsStrings = {
    ok: "OK",
    cancel: "Cancel",
    bold: "Полужирный <strong> Ctrl+B",
    boldexample: "Полужирный текст",
    italic: "Курсив <em> Ctrl+I",
    italicexample: "Текст курсивом",
    link: "Ссылка <a> Ctrl+L",
    linkdescription: "Описание ссылки",
    linkdialog: '<p>Пожалуйста, введите адрес ссылки</p>',
    quote: "Цитата <blockquote> Ctrl+Q",
    quoteexample: "Цитирование текста",
    code: "Код <pre><code> Ctrl+K",
    codeexample: "Пожалуйста, введите код",
    image: "Фото <img> Ctrl+G",
    imagedescription: "Описание изображения",
    // imagedialog: '<ul class="nav nav-tabs" role="tablist"><li class="active"><a href="#localPic" role="tab" data-toggle="tab">Локальная</a></li>    <li><a href="#remotePic" role="tab" data-toggle="tab">Получение удаленного адреса</a></li></ul><div class="tab-content">    <div class="tab-pane fade active in pt20 form-horizontal" id="localPic">        <span class="text-muted">Объем изображения не должен превышать 4 MB</span>        <br>        <div class="widget-upload form-group">        <input type="file" id="editorUpload" name="image" class="widget-upload__file">        <div class="col-sm-8">        <input type="text" id="fileName" class="form-control col-sm-10 widget-upload__text" placeholder="Перетащите изображение сюда" readonly="">        </div>        <a href="javascript:void(0);" class="btn col-sm-2 btn-default">Выберите</a>        </div>    </div>    <div class="tab-pane fade pt20" id="remotePic">    <input type="url" name="img" id="remotePicUrl" class="form-control text-28" placeholder="Введите URL, где находится изображение">    </div></div>',
    imagedialog: "<p>Пожалуйста, введите адрес изображения</p>",
    olist: "Список чисел <ol> Ctrl+O",
    ulist: "Обычный список <ul> Ctrl+U",
    litem: "Элементы списка",
    heading: "Заголовок <h1>/<h2> Ctrl+H",
    headingexample: "Текст заголовка",
    hr: "Разделительная линия <hr> Ctrl+R",
    undo: "Отменить - Ctrl+Z",
    redo: "Изменить - Ctrl+Y",
    redomac: "Изменить - Ctrl+Shift+Z",
    zen: "Полноэкранный режим",
    help: "Markdown грамматика"
  };

  // -------------------------------------------------------------------
  //  YOUR CHANGES GO HERE
  //
  // I've tried to localize the things you are likely to change to
  // this area.
  // -------------------------------------------------------------------
  // The default text that appears in the dialog input box when entering
  // links.
  var imageDefaultText = ""; //http://
  var linkDefaultText = ""; //http://
  // -------------------------------------------------------------------
  //  END OF YOUR CHANGES
  // -------------------------------------------------------------------
  // options, if given, can have the following properties:
  //   options.helpButton = { handler: yourEventHandler }
  //   options.strings = { italicexample: "slanted text" }
  //   options.wrapImageInLink = true
  //   options.convertImagesToLinks = true
  // `yourEventHandler` is the click handler for the help button.
  // If `options.helpButton` isn't given, not help button is created.
  // `options.strings` can have any or all of the same properties as
  // `defaultStrings` above, so you can just override some string displayed
  // to the user on a case-by-case basis, or translate all strings to
  // a different language.
  //
  // For backwards compatibility reasons, the `options` argument can also
  // be just the `helpButton` object, and `strings.help` can also be set via
  // `helpButton.title`. This should be considered legacy.
  //
  // The constructed editor object has the methods:
  // - getConverter() returns the markdown converter object that was passed to the constructor
  // - run() actually starts the editor; should be called after all necessary plugins are registered. Calling this more than once is a no-op.
  // - refreshPreview() forces the preview to be updated. This method is only available after run() was called.
  Markdown.Editor = function(markdownConverter, idPostfix, options) {
    options = options || {};
    if (typeof options.handler === "function") { //backwards compatible behavior
      options = {
        helpButton: options
      };
    }
    options.strings = options.strings || {};
    if (options.helpButton) {
      options.strings.help = options.strings.help || options.helpButton.title;
    }
    var getString = function(identifier) {
      return options.strings[identifier] || defaultsStrings[identifier];
    }
    idPostfix = idPostfix || "";
    this.getPostfix = function() {
      return idPostfix;
    }
    var hooks = this.hooks = new Markdown.HookCollection();
    hooks.addNoop("onPreviewRefresh"); // called with no arguments after the preview has been refreshed
    hooks.addNoop("postBlockquoteCreation"); // called with the user's selection *after* the blockquote was created; should return the actual to-be-inserted text
    hooks.addFalse("insertImageDialog");
    /* called with one parameter: a callback to be called with the URL of the image. If the application creates
     * its own image insertion dialog, this hook should return true, and the callback should be called with the chosen
     * image url (or null if the user cancelled). If this hook returns false, the default dialog will be used.
     */
    hooks.addNoop("imageConvertedToLink"); // called with no arguments if an image was converted 
    hooks.addFalse("insertLinkDialog");
    /* called with one parameter: a callback to be called with the URL.
     * works identical to insertImageDialog (see above)
     */
    this.getConverter = function() {
      return markdownConverter;
    }
    var that = this,
      panels;
    this.run = function() {
      if (panels)
        return; // already initialized
      panels = new PanelCollection(idPostfix);
      var commandManager = new CommandManager(hooks, getString, markdownConverter, options.wrapImageInLink, options.convertImagesToLinks);
      var previewManager = new PreviewManager(markdownConverter, panels, function() {
        hooks.onPreviewRefresh();
      });
      var undoManager, uiManager;
      if (!/\?noundo/.test(doc.location.href)) {
        undoManager = new UndoManager(function() {
          previewManager.refresh();
          if (uiManager) // not available on the first call
            uiManager.setUndoRedoButtonStates();
        }, panels);
        this.textOperation = function(f) {
          undoManager.setCommandMode();
          f();
          that.refreshPreview();
        }
      }
      uiManager = new UIManager(idPostfix, panels, undoManager, previewManager, commandManager, options.helpButton, getString);
      uiManager.setUndoRedoButtonStates();
      var forceRefresh = that.refreshPreview = function() {
        previewManager.refresh(true);
      };
      forceRefresh();
    };

    this.createToolbar()
  }

  Markdown.Editor.prototype.createToolbar = function(e) {
    var i;
    var me = this
    var mode = '<li class="pull-right"><a class="editor__menu--preview" title="Режим предпросмотра"></a></li><li class="pull-right"><a class="editor__menu--live" title="Живой режим"></a></li><li class="pull-right"><a class="editor__menu--edit" title="Режим редактирования"></a></li><li class="pull-right editor__menu--divider"></li><li id="wmd-zen-button" class="pull-right" title="Полноэкранный режим"><a class="editor__menu--zen"></a></li>'
    document.querySelector('.editor-mode').innerHTML = mode;
    
    // Примечание: переключение режимов отображения
    document.querySelector('.editor__menu--preview').onclick = function() {
      removeClass('.editor', 'liveMode editMode', 'previewMode')
      removeClass('.editor-mode a', 'muted')
      removeClass('.editor__menu--preview', '', 'muted')
    }
    document.querySelector('.editor__menu--live').onclick = function() {
      removeClass('.editor', 'editMode previewMode', 'liveMode')
      removeClass('.editor-mode a', 'muted')
      removeClass('.editor__menu--live', '', 'muted')
    }
    document.querySelector('.editor__menu--edit').onclick = function() {
      removeClass('.editor', 'liveMode previewMode', 'editMode')
      removeClass('.editor-mode a', 'muted')
      removeClass('.editor__menu--edit', '', 'muted')
    }
    document.querySelector('.editor__menu--zen').onclick = function() {
      var zoomout = function() {
        removeClass('.editor__menu--zen', 'editor__menu--zen', 'editor__menu--unzen')
        removeClass('.editor', '', 'editor_fullscreen')
        removeClass('body', '', 'noscroll')
        document.querySelector('.wmd-input').style.height = '100%'
      }
      var zoomin = function() {
        removeClass('.editor__menu--unzen', 'editor__menu--unzen', 'editor__menu--zen')
        removeClass('.editor', 'editor_fullscreen')
        removeClass('body', 'noscroll')
      }
      document.querySelectorAll('.editor__menu--zen').length ? zoomout() : zoomin()
    }
    
  }
  
  // before: contains all the text in the input box BEFORE the selection.
  // after: contains all the text in the input box AFTER the selection.
  function Chunks() {}
  // startRegex: a regular expression to find the start tag
  // endRegex: a regular expresssion to find the end tag
  Chunks.prototype.findTags = function(startRegex, endRegex) {
    var chunkObj = this;
    var regex;
    if (startRegex) {
      regex = util.extendRegExp(startRegex, "", "$");
      this.before = this.before.replace(regex,
        function(match) {
          chunkObj.startTag = chunkObj.startTag + match;
          return "";
        });
      regex = util.extendRegExp(startRegex, "^", "");
      this.selection = this.selection.replace(regex,
        function(match) {
          chunkObj.startTag = chunkObj.startTag + match;
          return "";
        });
    }
    if (endRegex) {
      regex = util.extendRegExp(endRegex, "", "$");
      this.selection = this.selection.replace(regex,
        function(match) {
          chunkObj.endTag = match + chunkObj.endTag;
          return "";
        });
      regex = util.extendRegExp(endRegex, "^", "");
      this.after = this.after.replace(regex,
        function(match) {
          chunkObj.endTag = match + chunkObj.endTag;
          return "";
        });
    }
  };
  // If remove is false, the whitespace is transferred
  // to the before/after regions.
  //
  // If remove is true, the whitespace disappears.
  Chunks.prototype.trimWhitespace = function(remove) {
    var beforeReplacer, afterReplacer, that = this;
    if (remove) {
      beforeReplacer = afterReplacer = "";
    } else {
      beforeReplacer = function(s) {
        that.before += s;
        return "";
      }
      afterReplacer = function(s) {
        that.after = s + that.after;
        return "";
      }
    }
    this.selection = this.selection.replace(/^(\s*)/, beforeReplacer).replace(/(\s*)$/, afterReplacer);
  };

  Chunks.prototype.skipLines = function(nLinesBefore, nLinesAfter, findExtraNewlines) {
    if (nLinesBefore === undefined) {
      nLinesBefore = 1;
    }
    if (nLinesAfter === undefined) {
      nLinesAfter = 1;
    }
    nLinesBefore++;
    nLinesAfter++;
    var regexText;
    var replacementText;
    // chrome bug ... documented at: http://meta.stackexchange.com/questions/63307/blockquote-glitch-in-editor-in-chrome-6-and-7/65985#65985
    if (navigator.userAgent.match(/Chrome/)) {
      "X".match(/()./);
    }
    this.selection = this.selection.replace(/(^\n*)/, "");
    this.startTag = this.startTag + re.$1;
    this.selection = this.selection.replace(/(\n*$)/, "");
    this.endTag = this.endTag + re.$1;
    this.startTag = this.startTag.replace(/(^\n*)/, "");
    this.before = this.before + re.$1;
    this.endTag = this.endTag.replace(/(\n*$)/, "");
    this.after = this.after + re.$1;
    if (this.before) {
      regexText = replacementText = "";
      while (nLinesBefore--) {
        regexText += "\\n?";
        replacementText += "\n";
      }
      if (findExtraNewlines) {
        regexText = "\\n*";
      }
      this.before = this.before.replace(new re(regexText + "$", ""), replacementText);
    }
    if (this.after) {
      regexText = replacementText = "";
      while (nLinesAfter--) {
        regexText += "\\n?";
        replacementText += "\n";
      }
      if (findExtraNewlines) {
        regexText = "\\n*";
      }
      this.after = this.after.replace(new re(regexText, ""), replacementText);
    }
  };
  // end of Chunks
  // A collection of the important regions on the page.
  // Cached so we don't have to keep traversing the DOM.
  // Also holds ieCachedRange and ieCachedScrollTop, where necessary; working around
  // this issue:
  // Internet explorer has problems with CSS sprite buttons that use HTML
  // lists.  When you click on the background image "button", IE will
  // select the non-existent link text and discard the selection in the
  // textarea.  The solution to this is to cache the textarea selection
  // on the button's mousedown event and set a flag.  In the part of the
  // code where we need to grab the selection, we check for the flag
  // and, if it's set, use the cached area instead of querying the
  // textarea.
  //
  // This ONLY affects Internet Explorer (tested on versions 6, 7
  // and 8) and ONLY on button clicks.  Keyboard shortcuts work
  // normally since the focus never leaves the textarea.
  function PanelCollection(postfix) {
    this.buttonBar = doc.getElementById("wmd-button-bar" + postfix);
    this.preview = doc.getElementById("wmd-preview" + postfix);
    this.input = doc.getElementById("wmd-input" + postfix);
  };
  // Returns true if the DOM element is visible, false if it's hidden.
  // Checks if display is anything other than none.
  util.isVisible = function(elem) {
    if (window.getComputedStyle) {
      // Most browsers
      return window.getComputedStyle(elem, null).getPropertyValue("display") !== "none";
    } else if (elem.currentStyle) {
      // IE
      return elem.currentStyle["display"] !== "none";
    }
  };

  // Adds a listener callback to a DOM element which is fired on a specified
  // event.
  util.addEvent = function(elem, event, listener) {
    if (elem.attachEvent) {
      // IE only.  The "on" is mandatory.
      elem.attachEvent("on" + event, listener);
    } else {
      // Other browsers.
      elem.addEventListener(event, listener, false);
    }
  };

  // Removes a listener callback from a DOM element which is fired on a specified
  // event.
  util.removeEvent = function(elem, event, listener) {
    if (elem.detachEvent) {
      // IE only.  The "on" is mandatory.
      elem.detachEvent("on" + event, listener);
    } else {
      // Other browsers.
      elem.removeEventListener(event, listener, false);
    }
  };
  // Converts \r\n and \r to \n.
  util.fixEolChars = function(text) {
    text = text.replace(/\r\n/g, "\n");
    text = text.replace(/\r/g, "\n");
    return text;
  };
  // Extends a regular expression.  Returns a new RegExp
  // using pre + regex + post as the expression.
  // Used in a few functions where we have a base
  // expression and we want to pre- or append some
  // conditions to it (e.g. adding "$" to the end).
  // The flags are unchanged.
  //
  // regex is a RegExp, pre and post are strings.
  util.extendRegExp = function(regex, pre, post) {
    if (pre === null || pre === undefined) {
      pre = "";
    }
    if (post === null || post === undefined) {
      post = "";
    }
    var pattern = regex.toString();
    var flags;
    // Replace the flags with empty space and store them.
    pattern = pattern.replace(/\/([gim]*)$/, function(wholeMatch, flagsPart) {
      flags = flagsPart;
      return "";
    });
    // Remove the slash delimiters on the regular expression.
    pattern = pattern.replace(/(^\/|\/$)/g, "");
    pattern = pre + pattern + post;
    return new re(pattern, flags);
  }
  // UNFINISHED
  // The assignment in the while loop makes jslint cranky.
  // I'll change it to a better loop later.
  position.getTop = function(elem, isInner) {
    var result = elem.offsetTop;
    if (!isInner) {
      while (elem = elem.offsetParent) {
        result += elem.offsetTop;
      }
    }
    return result;
  };
  position.getHeight = function(elem) {
    return elem.offsetHeight || elem.scrollHeight;
  };
  position.getWidth = function(elem) {
    return elem.offsetWidth || elem.scrollWidth;
  };
  position.getPageSize = function() {
    var scrollWidth, scrollHeight;
    var innerWidth, innerHeight;
    // It's not very clear which blocks work with which browsers.
    if (self.innerHeight && self.scrollMaxY) {
      scrollWidth = doc.body.scrollWidth;
      scrollHeight = self.innerHeight + self.scrollMaxY;
    } else if (doc.body.scrollHeight > doc.body.offsetHeight) {
      scrollWidth = doc.body.scrollWidth;
      scrollHeight = doc.body.scrollHeight;
    } else {
      scrollWidth = doc.body.offsetWidth;
      scrollHeight = doc.body.offsetHeight;
    }
    if (self.innerHeight) {
      // Non-IE browser
      innerWidth = self.innerWidth;
      innerHeight = self.innerHeight;
    } else if (doc.documentElement && doc.documentElement.clientHeight) {
      // Some versions of IE (IE 6 w/ a DOCTYPE declaration)
      innerWidth = doc.documentElement.clientWidth;
      innerHeight = doc.documentElement.clientHeight;
    } else if (doc.body) {
      // Other versions of IE
      innerWidth = doc.body.clientWidth;
      innerHeight = doc.body.clientHeight;
    }
    var maxWidth = Math.max(scrollWidth, innerWidth);
    var maxHeight = Math.max(scrollHeight, innerHeight);
    return [maxWidth, maxHeight, innerWidth, innerHeight];
  };
  // Handles pushing and popping TextareaStates for undo/redo commands.
  // I should rename the stack variables to list.
  function UndoManager(callback, panels) {
    var undoObj = this;
    var undoStack = []; // A stack of undo states
    var stackPtr = 0; // The index of the current state
    var mode = "none";
    var lastState; // The last state
    var timer; // The setTimeout handle for cancelling the timer
    var inputStateObj;
    // Set the mode for later logic steps.
    var setMode = function(newMode, noSave) {
      if (mode != newMode) {
        mode = newMode;
        if (!noSave) {
          saveState();
        }
      }
      if (!uaSniffed.isIE || mode != "moving") {
        timer = setTimeout(refreshState, 1);
      } else {
        inputStateObj = null;
      }
    };
    var refreshState = function(isInitialState) {
      inputStateObj = new TextareaState(panels, isInitialState);
      timer = undefined;
    };
    this.setCommandMode = function() {
      mode = "command";
      saveState();
      timer = setTimeout(refreshState, 0);
    };
    this.canUndo = function() {
      return stackPtr > 1;
    };
    this.canRedo = function() {
      if (undoStack[stackPtr + 1]) {
        return true;
      }
      return false;
    };
    // Removes the last state and restores it.
    this.undo = function() {
      if (undoObj.canUndo()) {
        if (lastState) {
          // What about setting state -1 to null or checking for undefined?
          lastState.restore();
          lastState = null;
        } else {
          undoStack[stackPtr] = new TextareaState(panels);
          undoStack[--stackPtr].restore();
          if (callback) {
            callback();
          }
        }
      }
      mode = "none";
      panels.input.focus();
      refreshState();
    };
    // Redo an action.
    this.redo = function() {
      if (undoObj.canRedo()) {
        undoStack[++stackPtr].restore();
        if (callback) {
          callback();
        }
      }
      mode = "none";
      panels.input.focus();
      refreshState();
    };
    // Push the input area state to the stack.
    var saveState = function() {
      var currState = inputStateObj || new TextareaState(panels);
      if (!currState) {
        return false;
      }
      if (mode == "moving") {
        if (!lastState) {
          lastState = currState;
        }
        return;
      }
      if (lastState) {
        if (undoStack[stackPtr - 1].text != lastState.text) {
          undoStack[stackPtr++] = lastState;
        }
        lastState = null;
      }
      undoStack[stackPtr++] = currState;
      undoStack[stackPtr + 1] = null;
      if (callback) {
        callback();
      }
    };
    var handleCtrlYZ = function(event) {
      var handled = false;
      if ((event.ctrlKey || event.metaKey) && !event.altKey) {
        // IE and Opera do not support charCode.
        var keyCode = event.charCode || event.keyCode;
        var keyCodeChar = String.fromCharCode(keyCode);
        switch (keyCodeChar.toLowerCase()) {
          case "y":
            undoObj.redo();
            handled = true;
            break;
          case "z":
            if (!event.shiftKey) {
              undoObj.undo();
            } else {
              undoObj.redo();
            }
            handled = true;
            break;
        }
      }
      if (handled) {
        if (event.preventDefault) {
          event.preventDefault();
        }
        if (window.event) {
          window.event.returnValue = false;
        }
        return;
      }
    };
    // Set the mode depending on what is going on in the input area.
    var handleModeChange = function(event) {
      if (!event.ctrlKey && !event.metaKey) {
        var keyCode = event.keyCode;
        if ((keyCode >= 33 && keyCode <= 40) || (keyCode >= 63232 && keyCode <= 63235)) {
          // 33 - 40: page up/dn and arrow keys
          // 63232 - 63235: page up/dn and arrow keys on safari
          setMode("moving");
        } else if (keyCode == 8 || keyCode == 46 || keyCode == 127) {
          // 8: backspace
          // 46: delete
          // 127: delete
          setMode("deleting");
        } else if (keyCode == 13) {
          // 13: Enter
          setMode("newlines");
        } else if (keyCode == 27) {
          // 27: escape
          setMode("escape");
        } else if ((keyCode < 16 || keyCode > 20) && keyCode != 91) {
          // 16-20 are shift, etc.
          // 91: left window key
          // I think this might be a little messed up since there are
          // a lot of nonprinting keys above 20.
          setMode("typing");
        }
      }
    };
    var setEventHandlers = function() {
      util.addEvent(panels.input, "keypress", function(event) {
        // keyCode 89: y
        // keyCode 90: z
        if ((event.ctrlKey || event.metaKey) && !event.altKey && (event.keyCode == 89 || event.keyCode == 90)) {
          event.preventDefault();
        }
      });
      var handlePaste = function() {
        if (uaSniffed.isIE || (inputStateObj && inputStateObj.text != panels.input.value)) {
          if (timer == undefined) {
            mode = "paste";
            saveState();
            refreshState();
          }
        }
      };
      util.addEvent(panels.input, "keydown", handleCtrlYZ);
      util.addEvent(panels.input, "keydown", handleModeChange);
      util.addEvent(panels.input, "mousedown", function() {
        setMode("moving");
      });
      panels.input.onpaste = handlePaste;
      panels.input.ondrop = handlePaste;
    };
    var init = function() {
      setEventHandlers();
      tabIndent.config.tab = '  ';
      tabIndent.render(panels.input);
      refreshState(true);
      saveState();
    };
    init();
  }
  // end of UndoManager
  // The input textarea state/contents.
  // This is used to implement undo/redo by the undo manager.
  function TextareaState(panels, isInitialState) {
    // Aliases
    var stateObj = this;
    var inputArea = panels.input;
    this.init = function() {
      if (!util.isVisible(inputArea)) {
        return;
      }
      if (!isInitialState && doc.activeElement && doc.activeElement !== inputArea) { // this happens when tabbing out of the input box
        return;
      }
      this.setInputAreaSelectionStartEnd();
      this.scrollTop = inputArea.scrollTop;
      if (!this.text && inputArea.selectionStart || inputArea.selectionStart === 0) {
        this.text = inputArea.value;
      }
    }
    // Sets the selected text in the input box after we've performed an
    // operation.
    this.setInputAreaSelection = function() {
      if (!util.isVisible(inputArea)) {
        return;
      }
      if (inputArea.selectionStart !== undefined && !uaSniffed.isOpera) {
        inputArea.focus();
        inputArea.selectionStart = stateObj.start;
        inputArea.selectionEnd = stateObj.end;
        inputArea.scrollTop = stateObj.scrollTop;
      } else if (doc.selection) {
        if (doc.activeElement && doc.activeElement !== inputArea) {
          return;
        }
        inputArea.focus();
        var range = inputArea.createTextRange();
        range.moveStart("character", -inputArea.value.length);
        range.moveEnd("character", -inputArea.value.length);
        range.moveEnd("character", stateObj.end);
        range.moveStart("character", stateObj.start);
        range.select();
      }
    };
    this.setInputAreaSelectionStartEnd = function() {
      if (!panels.ieCachedRange && (inputArea.selectionStart || inputArea.selectionStart === 0)) {
        stateObj.start = inputArea.selectionStart;
        stateObj.end = inputArea.selectionEnd;
      } else if (doc.selection) {
        stateObj.text = util.fixEolChars(inputArea.value);
        // IE loses the selection in the textarea when buttons are
        // clicked.  On IE we cache the selection. Here, if something is cached,
        // we take it.
        var range = panels.ieCachedRange || doc.selection.createRange();
        var fixedRange = util.fixEolChars(range.text);
        var marker = "\x07";
        var markedRange = marker + fixedRange + marker;
        range.text = markedRange;
        var inputText = util.fixEolChars(inputArea.value);
        range.moveStart("character", -markedRange.length);
        range.text = fixedRange;
        stateObj.start = inputText.indexOf(marker);
        stateObj.end = inputText.lastIndexOf(marker) - marker.length;
        var len = stateObj.text.length - util.fixEolChars(inputArea.value).length;
        if (len) {
          range.moveStart("character", -fixedRange.length);
          while (len--) {
            fixedRange += "\n";
            stateObj.end += 1;
          }
          range.text = fixedRange;
        }
        if (panels.ieCachedRange)
          stateObj.scrollTop = panels.ieCachedScrollTop; // this is set alongside with ieCachedRange
        panels.ieCachedRange = null;
        this.setInputAreaSelection();
      }
    };
    // Restore this state into the input area.
    this.restore = function() {
      if (stateObj.text != undefined && stateObj.text != inputArea.value) {
        inputArea.value = stateObj.text;
      }
      this.setInputAreaSelection();
      inputArea.scrollTop = stateObj.scrollTop;
    };
    // Gets a collection of HTML chunks from the inptut textarea.
    this.getChunks = function() {
      var chunk = new Chunks();
      chunk.before = util.fixEolChars(stateObj.text.substring(0, stateObj.start));
      chunk.startTag = "";
      chunk.selection = util.fixEolChars(stateObj.text.substring(stateObj.start, stateObj.end));
      chunk.endTag = "";
      chunk.after = util.fixEolChars(stateObj.text.substring(stateObj.end));
      chunk.scrollTop = stateObj.scrollTop;
      return chunk;
    };
    // Sets the TextareaState properties given a chunk of markdown.
    this.setChunks = function(chunk) {
      chunk.before = chunk.before + chunk.startTag;
      chunk.after = chunk.endTag + chunk.after;
      this.start = chunk.before.length;
      this.end = chunk.before.length + chunk.selection.length;
      this.text = chunk.before + chunk.selection + chunk.after;
      this.scrollTop = chunk.scrollTop;
    };
    this.init();
  };

  function PreviewManager(converter, panels, previewRefreshCallback) {
    var managerObj = this;
    var timeout;
    var elapsedTime;
    var oldInputText;
    var maxDelay = 3000;
    var startType = "delayed"; // The other legal value is "manual"
    // Adds event listeners to elements
    var setupEvents = function(inputElem, listener) {
      util.addEvent(inputElem, "input", listener);
      inputElem.onpaste = listener;
      inputElem.ondrop = listener;
      util.addEvent(inputElem, "keypress", listener);
      util.addEvent(inputElem, "keydown", listener);
    };
    var getDocScrollTop = function() {
      var result = 0;
      if (window.innerHeight) {
        result = window.pageYOffset;
      } else
      if (doc.documentElement && doc.documentElement.scrollTop) {
        result = doc.documentElement.scrollTop;
      } else
      if (doc.body) {
        result = doc.body.scrollTop;
      }
      return result;
    };
    var makePreviewHtml = function() {
      // If there is no registered preview panel
      // there is nothing to do.
      if (!panels.preview)
        return;

      var text = panels.input.value;
      if (text && text == oldInputText) {
        return; // Input text hasn't changed.
      } else {
        oldInputText = text;
      }
      var prevTime = new Date().getTime();
      text = converter.makeHtml(text);
      // Calculate the processing time of the HTML creation.
      // It's used as the delay time in the event listener.
      var currTime = new Date().getTime();
      elapsedTime = currTime - prevTime;
      pushPreviewHtml(text);
    };
    // setTimeout is already used.  Used as an event listener.
    var applyTimeout = function() {
      if (timeout) {
        clearTimeout(timeout);
        timeout = undefined;
      }
      if (startType !== "manual") {
        var delay = 0;
        if (startType === "delayed") {
          delay = elapsedTime;
        }
        if (delay > maxDelay) {
          delay = maxDelay;
        }
        timeout = setTimeout(makePreviewHtml, delay);
      }
    };
    var getScaleFactor = function(panel) {
      if (panel.scrollHeight <= panel.clientHeight) {
        return 1;
      }
      return panel.scrollTop / (panel.scrollHeight - panel.clientHeight);
    };
    var setPanelScrollTops = function() {
      if (panels.preview) {
        panels.preview.scrollTop = (panels.preview.scrollHeight - panels.preview.clientHeight) * getScaleFactor(panels.preview);
      }
    };
    this.refresh = function(requiresRefresh) {
      if (requiresRefresh) {
        oldInputText = "";
        makePreviewHtml();
      } else {
        applyTimeout();
      }
    };
    this.processingTime = function() {
      return elapsedTime;
    };
    var isFirstTimeFilled = true;
    // IE doesn't let you use innerHTML if the element is contained somewhere in a table
    // (which is the case for inline editing) -- in that case, detach the element, set the
    // value, and reattach. Yes, that *is* ridiculous.
    var ieSafePreviewSet = function(text) {
      var preview = panels.preview;
      var parent = preview.parentNode;
      var sibling = preview.nextSibling;
      parent.removeChild(preview);
      preview.innerHTML = text;
      if (!sibling)
        parent.appendChild(preview);
      else
        parent.insertBefore(preview, sibling);
    }
    var nonSuckyBrowserPreviewSet = function(text) {
      panels.preview.innerHTML = text;
    }
    var previewSetter;
    var previewSet = function(text) {
      if (previewSetter)
        return previewSetter(text);
      try {
        nonSuckyBrowserPreviewSet(text);
        previewSetter = nonSuckyBrowserPreviewSet;
      } catch (e) {
        previewSetter = ieSafePreviewSet;
        previewSetter(text);
      }
    };
    var pushPreviewHtml = function(text) {
      var emptyTop = position.getTop(panels.input) - getDocScrollTop();
      if (panels.preview) {
        previewSet(text);
        previewRefreshCallback();
      }
      setPanelScrollTops();
      if (isFirstTimeFilled) {
        isFirstTimeFilled = false;
        return;
      }
      var fullTop = position.getTop(panels.input) - getDocScrollTop();
      if (uaSniffed.isIE) {
        setTimeout(function() {
          window.scrollBy(0, fullTop - emptyTop);
        }, 0);
      } else {
        window.scrollBy(0, fullTop - emptyTop);
      }
    };
    var init = function() {
      setupEvents(panels.input, applyTimeout);
      makePreviewHtml();
      if (panels.preview) {
        panels.preview.scrollTop = 0;
      }
    };
    init();
  };
  // Creates the background behind the hyperlink text entry box.
  // And download dialog
  // Most of this has been moved to CSS but the div creation and
  // browser-specific hacks remain here.
  ui.createBackground = function() {
    var background = doc.createElement("div"),
      style = background.style;
    background.className = "wmd-prompt-background";
    style.position = "absolute";
    style.top = "0";
    style.zIndex = "1000";
    if (uaSniffed.isIE) {
      style.filter = "alpha(opacity=50)";
    } else {
      style.opacity = "0.5";
    }
    var pageSize = position.getPageSize();
    style.height = pageSize[1] + "px";
    if (uaSniffed.isIE) {
      style.left = doc.documentElement.scrollLeft;
      style.width = doc.documentElement.clientWidth;
    } else {
      style.left = "0";
      style.width = "100%";
    }
    doc.body.appendChild(background);
    return background;
  };
  // This simulates a modal dialog box and asks for the URL when you
  // click the hyperlink or image buttons.
  //
  // text: The html for the input box.
  // defaultInputText: The default value that appears in the input box.
  // ok: The text for the OK button
  // cancel: The text for the Cancel button
  // callback: The function which is executed when the prompt is dismissed, either via OK or Cancel.
  //      It receives a single argument; either the entered text (if OK was chosen) or null (if Cancel
  //      was chosen).
  ui.prompt = function(text, defaultInputText, ok, cancel, callback) {
    // These variables need to be declared at this level since they are used
    // in multiple functions.
    var dialog; // The dialog box.
    var input; // The text box where you enter the hyperlink.

    if (defaultInputText === undefined) {
      defaultInputText = "";
    }
    // Used as a keydown event handler. Esc dismisses the prompt.
    // Key code 27 is ESC.
    var checkEscape = function(key) {
      var code = (key.charCode || key.keyCode);
      if (code === 27) {
        if (key.stopPropagation) key.stopPropagation();
        close(true);
        return false;
      }
    };
    // Dismisses the hyperlink input box.
    // isCancel is true if we don't care about the input text.
    // isCancel is false if we are going to keep the text.
    var close = function(isCancel) {
      util.removeEvent(doc.body, "keyup", checkEscape);
      var text = input.value;
      if (isCancel) {
        text = null;
      } else {
        // Fixes common pasting errors.
        text = text.replace(/^http:\/\/(https?|ftp):\/\//, '$1://');
        if (!/^(?:https?|ftp):\/\//.test(text))
          text = 'http://' + text;
      }
      dialog.parentNode.removeChild(dialog);
      callback(text);
      return false;
    };

    // Create the text input box form/window.
    var createDialog = function(ok, cancel) {
      // The main dialog box.
      dialog = doc.createElement("div");
      dialog.className = "wmd-prompt-dialog";
      dialog.style.cssText = "padding:10px;position:fixed;width:400px;z-index:1001;top:50%;left:50%;display:block;";
      // The dialog text.
      var question = doc.createElement("div");
      question.innerHTML = text;
      question.style.cssText = "padding-bottom:0px;width:94%;margin:auto;";
      dialog.appendChild(question);
      // The web form container for the text box and buttons.
      var form = doc.createElement("form")
      form.onsubmit = function() {
        return close(false);
      };
      form.style.cssText = "padding:0;margin:0 auto;width:94%;text-align:center;position:relative;";
      dialog.appendChild(form);
      // The input text box
      input = doc.createElement("input");
      input.type = "text";
      input.value = defaultInputText;
      input.style.cssText = "display:block;width:100%;margin-bottom:5px;";
      form.appendChild(input);
      // The ok button
      var okButton = doc.createElement("input");
      okButton.type = "button";
      okButton.onclick = function() {
        return close(false);
      };
      okButton.value = ok;
      okButton.style.cssText = "margin:10px;padding:3px 0;display:inline;width:7em;cursor:pointer;";
      
      // The cancel button
      var cancelButton = doc.createElement("input");
      cancelButton.type = "button";
      cancelButton.onclick = function() {
        return close(true);
      };
      cancelButton.value = cancel;
      cancelButton.style.cssText = "margin:10px;padding:3px 0;display:inline;width:7em;cursor:pointer;";
      form.appendChild(okButton);
      form.appendChild(cancelButton);
      util.addEvent(doc.body, "keyup", checkEscape);
      
      if (uaSniffed.isIE_5or6) {
        dialog.style.position = "absolute";
        dialog.style.top = doc.documentElement.scrollTop + 200 + "px";
        dialog.style.left = "50%";
      }
      doc.body.appendChild(dialog);
      // This has to be done AFTER adding the dialog to the form if you
      // want it to be centered.
      dialog.style.marginTop = -(position.getHeight(dialog) / 2) + "px";
      dialog.style.marginLeft = -(position.getWidth(dialog) / 2) + "px";
    };
    // Why is this in a zero-length timeout?
    // Is it working around a browser bug?
    setTimeout(function() {
      createDialog(ok, cancel);
      var defTextLen = defaultInputText.length;
      if (input.selectionStart !== undefined) {
        input.selectionStart = 0;
        input.selectionEnd = defTextLen;
      } else if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(false);
        range.moveStart("character", -defTextLen);
        range.moveEnd("character", defTextLen);
        range.select();
      }
      input.focus();
    }, 0);
  };

  function UIManager(postfix, panels, undoManager, previewManager, commandManager, helpOptions, getString) {
    var inputBox = panels.input,
      buttons = {}; // buttons.undo, buttons.link, etc. The actual DOM elements.
    makeSpritedButtonRow();
    var keyEvent = "keydown";
    if (uaSniffed.isOpera) {
      keyEvent = "keypress";
    }
    util.addEvent(inputBox, keyEvent, function(key) {
      // Check to see if we have a button key and, if so execute the callback.
      if ((key.ctrlKey || key.metaKey) && !key.altKey && !key.shiftKey) {
        var keyCode = key.charCode || key.keyCode;
        var keyCodeStr = String.fromCharCode(keyCode).toLowerCase();
        switch (keyCodeStr) {
          case "b":
            doClick(buttons.bold);
            break;
          case "i":
            doClick(buttons.italic);
            break;
          case "l":
            doClick(buttons.link);
            break;
          case "q":
            doClick(buttons.quote);
            break;
          case "k":
            doClick(buttons.code);
            break;
          case "g":
            doClick(buttons.image);
            break;
          case "o":
            doClick(buttons.olist);
            break;
          case "u":
            doClick(buttons.ulist);
            break;
          case "h":
            doClick(buttons.heading);
            break;
          case "r":
            doClick(buttons.hr);
            break;
          case "y":
            doClick(buttons.redo);
            break;
          case "z":
            if (key.shiftKey) {
              doClick(buttons.redo);
            } else {
              doClick(buttons.undo);
            }
            break;
          default:
            return;
        }

        if (key.preventDefault) {
          key.preventDefault();
        }
        if (window.event) {
          window.event.returnValue = false;
        }
      }
    });
    // Auto-indent on shift-enter
    util.addEvent(inputBox, "keyup", function(key) {
      if (key.shiftKey && !key.ctrlKey && !key.metaKey) {
        var keyCode = key.charCode || key.keyCode;
        // Character 13 is Enter
        if (keyCode === 13) {
          var fakeButton = {};
          fakeButton.textOp = bindCommand("doAutoindent");
          doClick(fakeButton);
        }
      }
    });
    // special handler because IE clears the context of the textbox on ESC
    if (uaSniffed.isIE) {
      util.addEvent(inputBox, "keydown", function(key) {
        var code = key.keyCode;
        if (code === 27) {
          return false;
        }
      });
    }

    // Perform the button's action.
    function doClick(button) {
      inputBox.focus();
      if (button.textOp) {
        if (undoManager) {
          undoManager.setCommandMode();
        }
        var state = new TextareaState(panels);
        if (!state) {
          return;
        }
        var chunks = state.getChunks();
        // Some commands launch a "modal" prompt dialog.  Javascript
        // can't really make a modal dialog box and the WMD code
        // will continue to execute while the dialog is displayed.
        // This prevents the dialog pattern I'm used to and means
        // I can't do something like this:
        //
        // var link = CreateLinkDialog();
        // makeMarkdownLink(link);
        //
        // Instead of this straightforward method of handling a
        // dialog I have to pass any code which would execute
        // after the dialog is dismissed (e.g. link creation)
        // in a function parameter.
        //
        // Yes this is awkward and I think it sucks, but there's
        // no real workaround.  Only the image and link code
        // create dialogs and require the function pointers.
        var fixupInputArea = function() {
          inputBox.focus();
          if (chunks) {
            state.setChunks(chunks);
          }
          state.restore();
          previewManager.refresh();
        };
        var noCleanup = button.textOp(chunks, fixupInputArea);
        if (!noCleanup) {
          fixupInputArea();
        }
      }
      if (button.execute) {
        button.execute(undoManager);
      }
    };

    function setupButton(button, isEnabled) {
      var normalYShift = "0px";
      var disabledYShift = "-20px";
      var highlightYShift = "-40px";
      var image = button.getElementsByTagName("a")[0];
      if (isEnabled) {
        image.style.backgroundPosition = button.XShift + " " + normalYShift;
        button.onmouseover = function() {
          image.style.backgroundPosition = this.XShift + " " + highlightYShift;
        };
        button.onmouseout = function() {
          image.style.backgroundPosition = this.XShift + " " + normalYShift;
        };
        // IE tries to select the background image "button" text (it's
        // implemented in a list item) so we have to cache the selection
        // on mousedown.
        if (uaSniffed.isIE) {
          button.onmousedown = function() {
            if (doc.activeElement && doc.activeElement !== panels.input) { // we're not even in the input box, so there's no selection
              return;
            }
            panels.ieCachedRange = document.selection.createRange();
            panels.ieCachedScrollTop = panels.input.scrollTop;
          };
        }
        if (!button.isHelp) {
          button.onclick = function() {
            if (this.onmouseout) {
              this.onmouseout();
            }
            doClick(this);
            return false;
          }
        }
      } else {
        image.style.backgroundPosition = button.XShift + " " + disabledYShift;
        button.onmouseover = button.onmouseout = button.onclick = function() {};
      }
    }

    function bindCommand(method) {
      if (typeof method === "string")
        method = commandManager[method];
      return function() {
        method.apply(commandManager, arguments);
      }
    }

    function makeSpritedButtonRow() {
      var buttonBar = panels.buttonBar;
      // buttonBar.innerHTML = '<ul id="wmd-button-row" class="editor__menu clearfix"><li class="wmd-button" id="wmd-bold-button" title="Полужирный <strong> Ctrl+B" style="left: 0px;"><a class="editor__menu--bold" style="background-position: 0px 0px;"></a></li><li class="wmd-button" id="wmd-italic-button" title="Курсив <em> Ctrl+I" style="left: 25px;"><a class="editor__menu--bold" style="background-position: -20px 0px;"></a></li><li class="editor__menu--divider wmd-spacer1" id="wmd-spacer1"></li><li class="wmd-button" id="wmd-link-button" title="Ссылка <a> Ctrl+L" style="left: 75px;"><a class="editor__menu--bold" style="background-position: -40px 0px;"></a></li><li class="wmd-button" id="wmd-quote-button" title="Цитата <blockquote> Ctrl+Q" style="left: 100px;"><a class="editor__menu--bold" style="background-position: -60px 0px;"></a></li><li class="wmd-button" id="wmd-code-button" title="代码 <pre><code> Ctrl+K" style="left: 125px;"><a class="editor__menu--bold" style="background-position: -80px 0px;"></a></li><li class="wmd-button" id="wmd-image-button" title="Фото <img> Ctrl+G" style="left: 150px;"><a class="editor__menu--bold" style="background-position: -100px 0px;"></a></li><li class="editor__menu--divider wmd-spacer2" id="wmd-spacer2"></li><li class="wmd-button" id="wmd-olist-button" title="Список чисел <ol> Ctrl+O" style="left: 200px;"><a class="editor__menu--bold" style="background-position: -120px 0px;"></a></li><li class="wmd-button" id="wmd-ulist-button" title="Обычный список <ul> Ctrl+U" style="left: 225px;"><a class="editor__menu--bold" style="background-position: -140px 0px;"></a></li><li class="wmd-button" id="wmd-heading-button" title="Заголовок <h1>/<h2> Ctrl+H" style="left: 250px;"><a class="editor__menu--bold" style="background-position: -160px 0px;"></a></li><li class="wmd-button" id="wmd-hr-button" title="Разделительная линия <hr> Ctrl+R" style="left: 275px;"><a class="editor__menu--bold" style="background-position: -180px 0px;"></a></li><li class="editor__menu--divider wmd-spacer3" id="wmd-spacer3"></li><li class="wmd-button" id="wmd-undo-button" title="Отменить - Ctrl+Z" style="left: 325px;"><a class="editor__menu--bold" style="background-position: -200px -20px;"></a></li><li class="wmd-button" id="wmd-redo-button" title="Изменить - Ctrl+Y" style="left: 350px;"><a class="editor__menu--bold" style="background-position: -220px -20px;"></a></li><li class="editor__menu--divider wmd-spacer4" id="wmd-spacer4"></li><li class="wmd-button" id="wmd-help-button" title="Markdown разметка" style="left: 400px;"><a class="editor__menu--bold" style="background-position: -300px 0px;"></a></li></ul>'

      var normalYShift = "0px";
      var disabledYShift = "-20px";
      var highlightYShift = "-40px";
      var buttonRow = document.createElement("ul");
      buttonRow.id = "wmd-button-row" + postfix;
      buttonRow.className = "editor__menu clearfix";
      buttonRow = buttonBar.appendChild(buttonRow);
      var xPosition = 0;
      var makeButton = function(id, title, XShift, textOp) {
        var button = document.createElement("li");
        button.className = "wmd-button";
        button.style.left = xPosition + "px";
        xPosition += 25;
        var buttonImage = document.createElement("a");
        buttonImage.className = "editor__menu--bold";
        button.id = id + postfix;
        button.appendChild(buttonImage);
        button.title = title;
        button.XShift = XShift;
        if (textOp)
          button.textOp = textOp;
        setupButton(button, true);
        buttonRow.appendChild(button);
        return button;
      };
      var makeSpacer = function(num) {
        var spacer = document.createElement("li");
        spacer.className = "editor__menu--divider wmd-spacer" + num;
        spacer.id = "wmd-spacer" + num + postfix;
        buttonRow.appendChild(spacer);
        xPosition += 25;
      }
      buttons.bold = makeButton("wmd-bold-button", getString("bold"), "0px", bindCommand("doBold"));
      buttons.italic = makeButton("wmd-italic-button", getString("italic"), "-20px", bindCommand("doItalic"));
      makeSpacer(1);
      buttons.link = makeButton("wmd-link-button", getString("link"), "-40px", bindCommand(function(chunk, postProcessing) {
        return this.doLinkOrImage(chunk, postProcessing, false);
      }));
      buttons.quote = makeButton("wmd-quote-button", getString("quote"), "-60px", bindCommand("doBlockquote"));
      buttons.code = makeButton("wmd-code-button", getString("code"), "-80px", bindCommand("doCode"));
      buttons.image = makeButton("wmd-image-button", getString("image"), "-100px", bindCommand(function(chunk, postProcessing) {
        return this.doLinkOrImage(chunk, postProcessing, true);
      }));
      makeSpacer(2);
      buttons.olist = makeButton("wmd-olist-button", getString("olist"), "-120px", bindCommand(function(chunk, postProcessing) {
        this.doList(chunk, postProcessing, true);
      }));
      buttons.ulist = makeButton("wmd-ulist-button", getString("ulist"), "-140px", bindCommand(function(chunk, postProcessing) {
        this.doList(chunk, postProcessing, false);
      }));
      buttons.heading = makeButton("wmd-heading-button", getString("heading"), "-160px", bindCommand("doHeading"));
      buttons.hr = makeButton("wmd-hr-button", getString("hr"), "-180px", bindCommand("doHorizontalRule"));
      makeSpacer(3);
      buttons.undo = makeButton("wmd-undo-button", getString("undo"), "-200px", null);
      buttons.undo.execute = function(manager) {
        if (manager) manager.undo();
      };
      var redoTitle = /win/.test(nav.platform.toLowerCase()) ?
        getString("redo") :
        getString("redomac"); // mac and other non-Windows platforms
      buttons.redo = makeButton("wmd-redo-button", redoTitle, "-220px", null);
      buttons.redo.execute = function(manager) {
        if (manager) manager.redo();
      };

      setUndoRedoButtonStates();
      
      // Help
      var t = '<title>Markdown руководство</title><script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script><script src="https://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>', n = t + '<body style="background-color:#FAF2CC"><div class="editor-help"><ul class="editor-help-tabs nav nav-tabs" id="editorHelpTab" role="tablist">    <li rel="heading"><a href="#editorHelpHeading" role="tab" data-toggle="tab">Заголовок / полужирный</a></li>    <li rel="code"><a href="#editorHelpCode" role="tab" data-toggle="tag">Код</a></li>    <li rel="link"><a href="#editorHelpLink" role="tab" data-toggle="tag">Ссылка</a></li>    <li rel="image"><a href="#editorHelpImage" role="tab" data-toggle="tag">Фото</a></li>    <li rel="split"><a href="#editorHelpSplit" role="tab" data-toggle="tag">Разрывы строк / разделители</a></li>    <li rel="list"><a href="#editorHelpList" role="tab" data-toggle="tag">Списки / ссылки</a></li>     </ul><div class="tab-content"><!-- Жирный курсив, заголовок --><div class="editor-help-content tab-pane fade" id="editorHelpHeading" rel="heading"><p>Текс может сожержать заголовок</p><pre>## Большой заголовок ##\n### Подзаголовок ###</pre><p>Жирный / курсив</p><pre>*Курсивный текст*    _Курсивный текст_\n**Полужирный текст**    __Полужирный текст__\n***Жирный курсивный текст***    ___Жирный курсивный текст___</pre></div><!-- end Жирный курсив, заголовок --><!-- Код --><div class="editor-help-content tab-pane fade" id="editorHelpCode" rel="code"><p>Если вы хотите выделить только имя функции или ключевое слово в операторе, вы можете использовать <code>`function_name()`</code> выполнить</p><p>Обычно мы адаптируем соответствующий метод выделения в соответствии с вашим фрагментом кода,но вы также можете использовать <code>```</code> Оберните фрагмент кода и укажите язык</p><pre>```<strong>javascript</strong>\n$(document).ready(function () {\n    alert(\'hello world\');\n});\n```</pre><p>Поддерживаемые языки：<code>actionscript, apache, bash, clojure, cmake, coffeescript, cpp, cs, css, d, delphi, django, erlang, go, haskell, html, http, ini, java, javascript, json, lisp, lua, markdown, matlab, nginx, objectivec, perl, php, python, r, ruby, scala, smalltalk, sql, tex, vbscript, xml</code></p><p>Вы также можете использовать 4 пробела для отступа, а затем вставить код, чтобы добиться того же эффекта</p><pre><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>def g(x):\n<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>yield from range(x, 0, -1)\n<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>yield from range(x)</pre><p>Если вам не нужна подсветка кода, Вы можете отключить ее следующим образом：</p><pre>```nohighlight\n```</pre></div><!-- end код --><!-- Ссылка --><div class="editor-help-content tab-pane fade" rel="link" id="editorHelpLink"><p>Общие методы связывания</p><pre>Текстовая ссылка [имя ссылки](http://URL-адрес)\nСсылка на URL &lt;http://URL-адрес&gt;</pre><p>Расширенные навыки связывания</p><pre>Эта ссылка использует 1 в качестве переменной url [Google][1].\nЭта ссылка использует yahoo как переменная url [Yahoo!][yahoo].\nЗатем назначьте переменную (URL) в конце документа）\n\n<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>[1]: http://www.google.com/\n<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>[yandex]: http://yandex.ru/</pre></div><!-- end Ссылка --><!-- Картинка --><div class="editor-help-content tab-pane fade" id="editorHelpImage" rel="image"><p>Разница с подходом к ссылке заключается в том, что перед ней стоит восклицательный знак. <code>!</code>，Разве это не кажется более запоминающимся?</p><pre>![Имя изображения](http://URL-адрес изображения)</pre><p>Конечно, вы также можете использовать переменные для URL-адресов изображений, таких как URL-адрес</p><pre>Эта ссылка использует 1 в качестве переменной url [Google][1].\nЗатем в конце документа битовая переменная присваивается (url\n\n<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>[1]: http://www.google.com/logo.png</pre></div><!-- end фото --><!-- Разрыв строки, разделитель --><div class="editor-help-content tab-pane fade" id="editorHelpSplit" rel="split"><p>Если вы начинаете с другой строки, просто добавьте 2 пробела в конец текущей строки</p><pre>Добавьте 2 пробела в конец текущей строки<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>\nЭто будет новая строка.</pre><p>Если вы хотите начать новый абзац, просто оставьте пустую строку.</p><p>Если у вас есть привычка писать разделенные строки, вы можете ввести три знака минус в новой строке <code>-</code>：</p><pre>---\n</pre></div><!-- end разрыв строки, разделитель --><!-- Списки, ссылки --><div class="editor-help-content tab-pane fade" id="editorHelpList" rel="list"><p>Обычный список</p><pre>-<i class="nbsp">&nbsp;</i>Перед текстом списка используйте [минус + пробел]\n+<i class="nbsp">&nbsp;</i>Перед текстом списка используйте [плюс + пробел]\n*<i class="nbsp">&nbsp;</i>Перед текстом списка используйте [звездочку+пробел]</pre><p>Список с цифрами</p><pre>1.<i class="nbsp">&nbsp;</i>Перед списком используйте [число + пробел]\n2.<i class="nbsp">&nbsp;</i>Мы автоматически добавим номер для вас\n7.<i class="nbsp">&nbsp;</i>Не беспокойтесь о неправильных цифрах, мы автоматически исправим эту строку 7 до 3, когда она появится</pre><p>Цитировать</p><pre>&gt;<i class="nbsp">&nbsp;</i>Перед цитируемым текстом используйте [знак больше+пробел\n&gt;<i class="nbsp">&nbsp;</i>Складывание строк не может быть добавлено,новая строка должна быть добавлена</pre></div><!-- end списки, ссылки --></div></div><script>$("#editorHelpTab a").eq(0).tab("show");$("#editorHelpTab a").click(function (e) {    var _$wrap = $(this).parent();    if(! _$wrap.hasClass("pull-right")) {        e.preventDefault();        $(this).tab("show");    }});</script><style>html { font-family: sans-serif; text-size-adjust: 100%; }body { margin: 0px; }a { background: transparent; }a:active, a:hover { outline: 0px; }b, strong { font-weight: bold; }pre { overflow: auto; }code, kbd, pre, samp { font-family: monospace, monospace; font-size: 1em; }* { box-sizing: border-box; }html { font-size: 10px; -webkit-tap-highlight-color: transparent; }body { font-family: -apple-system, "Helvetica Neue", Helvetica, Arial, "PingFang SC", "Hiragino Sans GB", "WenQuanYi Micro Hei", "Microsoft Yahei", sans-serif; font-size: 14px; line-height: 1.42858; color: rgb(51, 51, 51); background-color: rgb(255, 255, 255); -webkit-font-smoothing: antialiased; }a { color: rgb(0, 154, 97); text-decoration: none; }a:hover, a:focus { color: rgb(0, 78, 49); text-decoration: underline; }a:focus { outline: -webkit-focus-ring-color auto 5px; outline-offset: -2px; }p { margin: 0px 0px 10px; }ul, ol { margin-top: 0px; margin-bottom: 10px; }code, kbd, pre, samp { font-family: "Source Code Pro", Consolas, Menlo, Monaco, "Courier New", monospace; }code { padding: 2px 4px; font-size: 90%; color: rgb(199, 37, 78); background-color: rgb(249, 242, 244); border-radius: 4px; }pre { display: block; padding: 9.5px; margin: 0px 0px 10px; font-size: 13px; line-height: 1.42858; word-break: break-all; word-wrap: break-word; color: rgb(51, 51, 51); background-color: rgb(245, 245, 245); border: 1px solid rgb(204, 204, 204); border-radius: 4px; }.nav { margin-bottom: 0px; padding-left: 0px; list-style: none; }.nav::before, .nav::after { content: " "; display: table; }.nav::after { clear: both; }.nav > li { position: relative; display: block; }.nav > li > a { position: relative; display: block; padding: 10px 15px; }.nav > li > a:hover, .nav > li > a:focus { text-decoration: none; background-color: rgb(238, 238, 238); }.nav-tabs { border-bottom: 1px solid rgb(221, 221, 221); }.nav-tabs > li { float: left; margin-bottom: -1px; }.nav-tabs > li > a { margin-right: 2px; line-height: 1.42858; border: 1px solid transparent; border-radius: 4px 4px 0px 0px; }.nav-tabs > li > a:hover { border-color: rgb(238, 238, 238) rgb(238, 238, 238) rgb(221, 221, 221); }.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus { color: rgb(85, 85, 85); background-color: rgb(255, 255, 255); border-width: 1px; border-style: solid; border-color: rgb(221, 221, 221) rgb(221, 221, 221) transparent; border-image: initial; cursor: default; }.tab-content > .tab-pane { display: none; }.tab-content > .active { display: block; }.pull-right { float: right !important; }a, a:hover, a:active, a:focus { outline: 0px; }.editor-help { border-bottom: none; background-color: rgb(250, 242, 204); font-size: 13px; }.editor-help .nav-tabs { position: fixed; top: 0px; width: 100%; border-bottom: none; background-color: rgb(252, 248, 227); }.editor-help .nav > li { margin: 0px; }.editor-help .nav > li > a { margin: 0px; padding: 6px 10px; border: none; color: rgb(138, 109, 59); border-radius: 0px; }.editor-help .nav > li.active > a, .editor-help .nav > li > a:hover { border: none; background-color: rgb(250, 242, 204); color: rgb(138, 109, 59); cursor: pointer; }.editor-help .nav > li.active > a { font-weight: bold; }.editor-help-content { margin-top: 30px; padding: 10px; }.editor-help-content pre { padding: 5px 8px; border: none; background-color: rgb(252, 248, 227); font-size: 12px; }.editor-help-content code { white-space: normal; }</style></body>'
      var helphandler = function() {
        if (window.markdownopenwin && window.markdownopenwin.window) window.markdownopenwin.focus()
        else {
          window.markdownopenwin = window.open("", "Markdown Help", "channelmode=yes, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=505, height=400, top=100, left=100")
          window.markdownopenwin.document.write(n)
        }
      }
      // if (helpOptions) {
      var helpButton = document.createElement("li");
      var helpButtonImage = document.createElement("a");
      helpButton.appendChild(helpButtonImage);
      helpButton.className = "wmd-button wmd-help-button";
      helpButton.id = "wmd-help-button" + postfix;
      helpButton.XShift = "-300px";
      helpButton.isHelp = true;
      helpButton.style.right = "0px";
      helpButton.title = getString("help");
      helpButton.onclick = helpOptions && helpOptions.handler ? helpOptions.handler : helphandler;
      setupButton(helpButton, true);
      buttonRow.appendChild(helpButton);
      buttons.help = helpButton;
      // }
    }

    function setUndoRedoButtonStates() {
      if (undoManager) {
        setupButton(buttons.undo, undoManager.canUndo());
        setupButton(buttons.redo, undoManager.canRedo());
      }
    };
    this.setUndoRedoButtonStates = setUndoRedoButtonStates;
  }

  function CommandManager(pluginHooks, getString, converter, wrapImageInLink, convertImagesToLinks) {
    this.hooks = pluginHooks;
    this.getString = getString;
    this.converter = converter;
    this.wrapImageInLink = wrapImageInLink;
    this.convertImagesToLinks = convertImagesToLinks;
  }

  var commandProto = CommandManager.prototype;
  // The markdown symbols - 4 spaces = code, > = blockquote, etc.
  commandProto.prefixes = "(?:\\s{4,}|\\s*>|\\s*-\\s+|\\s*\\d+\\.|=|\\+|-|_|\\*|#|\\s*\\[[^\n]]+\\]:)";
  // Remove markdown symbols from the chunk selection.
  commandProto.unwrap = function(chunk) {
    var txt = new re("([^\\n])\\n(?!(\\n|" + this.prefixes + "))", "g");
    chunk.selection = chunk.selection.replace(txt, "$1 $2");
  };
  commandProto.wrap = function(chunk, len) {
    this.unwrap(chunk);
    var regex = new re("(.{1," + len + "})( +|$\\n?)", "gm"),
      that = this;
    chunk.selection = chunk.selection.replace(regex, function(line, marked) {
      if (new re("^" + that.prefixes, "").test(line)) {
        return line;
      }
      return marked + "\n";
    });
    chunk.selection = chunk.selection.replace(/\s+$/, "");
  };
  commandProto.doBold = function(chunk, postProcessing) {
    return this.doBorI(chunk, postProcessing, 2, this.getString("boldexample"));
  };
  commandProto.doItalic = function(chunk, postProcessing) {
    return this.doBorI(chunk, postProcessing, 1, this.getString("italicexample"));
  };
  // chunk: The selected region that will be enclosed with */**
  // nStars: 1 for italics, 2 for bold
  // insertText: If you just click the button without highlighting text, this gets inserted
  commandProto.doBorI = function(chunk, postProcessing, nStars, insertText) {
    // Get rid of whitespace and fixup newlines.
    chunk.trimWhitespace();
    chunk.selection = chunk.selection.replace(/\n{2,}/g, "\n");
    // Look for stars before and after.  Is the chunk already marked up?
    // note that these regex matches cannot fail
    var starsBefore = /(\**$)/.exec(chunk.before)[0];
    var starsAfter = /(^\**)/.exec(chunk.after)[0];
    var prevStars = Math.min(starsBefore.length, starsAfter.length);
    // Remove stars if we have to since the button acts as a toggle.
    if ((prevStars >= nStars) && (prevStars != 2 || nStars != 1)) {
      chunk.before = chunk.before.replace(re("[*]{" + nStars + "}$", ""), "");
      chunk.after = chunk.after.replace(re("^[*]{" + nStars + "}", ""), "");
    } else if (!chunk.selection && starsAfter) {
      // It's not really clear why this code is necessary.  It just moves
      // some arbitrary stuff around.
      chunk.after = chunk.after.replace(/^([*_]*)/, "");
      chunk.before = chunk.before.replace(/(\s?)$/, "");
      var whitespace = re.$1;
      chunk.before = chunk.before + starsAfter + whitespace;
    } else {
      // In most cases, if you don't have any selected text and click the button
      // you'll get a selected, marked up region with the default text inserted.
      if (!chunk.selection && !starsAfter) {
        chunk.selection = insertText;
      }
      // Add the true markup.
      var markup = nStars <= 1 ? "*" : "**"; // shouldn't the test be = ?
      chunk.before = chunk.before + markup;
      chunk.after = markup + chunk.after;
    }
    return;
  };
  commandProto.stripLinkDefs = function(text, defsToAdd) {
    text = text.replace(/^[ ]{0,3}\[(\d+)\]:[ \t]*\n?[ \t]*<?(\S+?)>?[ \t]*\n?[ \t]*(?:(\n*)["(](.+?)[")][ \t]*)?(?:\n+|$)/gm,
      function(totalMatch, id, link, newlines, title) {
        defsToAdd[id] = totalMatch.replace(/\s*$/, "");
        if (newlines) {
          // Strip the title and return that separately.
          defsToAdd[id] = totalMatch.replace(/["(](.+?)[")]$/, "");
          return newlines + title;
        }
        return "";
      });
    return text;
  };
  commandProto.addLinkDef = function(chunk, linkDef) {
    var refNumber = 0; // The current reference number
    var defsToAdd = {}; //
    // Start with a clean slate by removing all previous link definitions.
    chunk.before = this.stripLinkDefs(chunk.before, defsToAdd);
    chunk.selection = this.stripLinkDefs(chunk.selection, defsToAdd);
    chunk.after = this.stripLinkDefs(chunk.after, defsToAdd);
    var defs = "";
    var regex = /\[(\d+)\]/g;
    // The above regex, used to update [foo][13] references after renumbering,
    // is much too liberal; it can catch things that are not actually parsed
    // as references (notably: code). It's impossible to know which matches are
    // real references without performing a markdown conversion, so that's what
    // we do. All matches are replaced with a unique reference number, which is
    // given a unique link. The uniquifier in both cases is the character offset
    // of the match inside the source string. The modified version is then sent
    // through the Markdown renderer. Because link reference are stripped during
    // rendering, the unique link is present in the rendered version if and only
    // if the match at its offset was in fact rendered as a link or image.
    var complete = chunk.before + chunk.selection + chunk.after;
    var rendered = this.converter.makeHtml(complete);
    var testlink = "http://this-is-a-real-link.biz/";
    // If our fake link appears in the rendered version *before* we have added it,
    // this probably means you're a Meta Stack Exchange user who is deliberately
    // trying to break this feature. You can still break this workaround if you
    // attach a plugin to the converter that sometimes (!) inserts this link. In
    // that case, consider yourself unsupported.
    while (rendered.indexOf(testlink) != -1)
      testlink += "nicetry/";
    var fakedefs = "\n\n";
    var uniquified = complete.replace(regex, function uniquify(wholeMatch, id, offset) {
      fakedefs += " [" + offset + "]: " + testlink + offset + "/unicorn\n";
      return "[" + offset + "]";
    });
    rendered = this.converter.makeHtml(uniquified + fakedefs);
    var okayToModify = function(offset) {
      return rendered.indexOf(testlink + offset + "/unicorn") !== -1;
    }
    // property names are "L_" + link (prefixed to prevent collisions with built-in properties),
    // values are the definition numbers
    var addedDefsByUrl = {};
    var addOrReuseDefNumber = function(def) {
      var stripped = def.replace(/^[ ]{0,3}\[(\d+)\]:/, "");
      var key = "L_" + stripped;
      if (key in addedDefsByUrl)
        return addedDefsByUrl[key];
      refNumber++;
      def = "  [" + refNumber + "]:" + stripped;
      defs += "\n" + def;
      addedDefsByUrl[key] = refNumber;
      return refNumber;
    };
    // the regex is tested on the (up to) three chunks separately,
    // so in order to have the correct offsets to check against okayToModify(), we
    // have to keep track of how many characters are in the original source before
    // the substring that we're looking at. Note that doLinkOrImage aligns the selection
    // on potential brackets, so there should be no major breakage from the chunk
    // separation.
    var skippedChars = 0;
    // note that
    // a) the recursive call to getLink cannot go infinite, because by definition
    //    of regex, inner is always a proper substring of wholeMatch, and
    // b) more than one level of nesting is neither supported by the regex
    //    nor making a lot of sense (the only use case for nesting is a linked image)
    var getLink = function(wholeMatch, id, offset) {
      if (!okayToModify(skippedChars + offset))
        return wholeMatch;
      if (defsToAdd[id]) {
        var refnum = addOrReuseDefNumber(defsToAdd[id]);
        return "[" + refnum + "]";
      }
      return wholeMatch;
    };
    var len = chunk.before.length;
    chunk.before = chunk.before.replace(regex, getLink);
    skippedChars += len;
    len = chunk.selection.length;
    var refOut;
    if (linkDef) {
      refOut = addOrReuseDefNumber(linkDef);
    } else {
      chunk.selection = chunk.selection.replace(regex, getLink);
    }
    skippedChars += len;
    chunk.after = chunk.after.replace(regex, getLink);
    if (chunk.after) {
      chunk.after = chunk.after.replace(/\n*$/, "");
    }
    if (!chunk.after) {
      chunk.selection = chunk.selection.replace(/\n*$/, "");
    }
    chunk.after += "\n\n" + defs;
    return refOut;
  };
  // takes the line as entered into the add link/as image dialog and makes
  // sure the URL and the optinal title are "nice".
  function properlyEncoded(linkdef) {
    return linkdef.replace(/^\s*(.*?)(?:\s+"(.+)")?\s*$/, function(wholematch, link, title) {
      var inQueryString = false;
      // Having `[^\w\d-./]` in there is just a shortcut that lets us skip
      // the most common characters in URLs. Replacing that it with `.` would not change
      // the result, because encodeURI returns those characters unchanged, but it
      // would mean lots of unnecessary replacement calls. Having `[` and `]` in that
      // section as well means we do *not* enocde square brackets. These characters are
      // a strange beast in URLs, but if anything, this causes URLs to be more readable,
      // and we leave it to the browser to make sure that these links are handled without
      // problems.
      link = link.replace(/%(?:[\da-fA-F]{2})|\?|\+|[^\w\d-./[\]]/g, function(match) {
        // Valid percent encoding. Could just return it as is, but we follow RFC3986
        // Section 2.1 which says "For consistency, URI producers and normalizers
        // should use uppercase hexadecimal digits for all percent-encodings."
        // Note that we also handle (illegal) stand-alone percent characters by
        // replacing them with "%25"
        if (match.length === 3 && match.charAt(0) == "%") {
          return match.toUpperCase();
        }
        switch (match) {
          case "?":
            inQueryString = true;
            return "?";
            break;
            // In the query string, a plus and a space are identical -- normalize.
            // Not strictly necessary, but identical behavior to the previous version
            // of this function.
          case "+":
            if (inQueryString)
              return "%20";
            break;
        }
        return encodeURI(match);
      })
      if (title) {
        title = title.trim ? title.trim() : title.replace(/^\s*/, "").replace(/\s*$/, "");
        title = title.replace(/"/g, "quot;").replace(/\(/g, "&#40;").replace(/\)/g, "&#41;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
      }
      return title ? link + ' "' + title + '"' : link;
    });
  }
  commandProto.doLinkOrImage = function(chunk, postProcessing, isImage) {
    chunk.trimWhitespace();
    chunk.findTags(/\s*!?\[/, /\][ ]?(?:\n[ ]*)?(\[.*?\])?/);
    var background;
    var wrapImageInLink = this.wrapImageInLink;
    var convertImagesToLinks = this.convertImagesToLinks;
    if (chunk.endTag.length > 1 && chunk.startTag.length > 0) {
      chunk.startTag = chunk.startTag.replace(/!?\[/, "");
      chunk.endTag = "";
      this.addLinkDef(chunk, null);
    } else {
      // We're moving start and end tag back into the selection, since (as we're in the else block) we're not
      // *removing* a link, but *adding* one, so whatever findTags() found is now back to being part of the
      // link text. linkEnteredCallback takes care of escaping any brackets.
      chunk.selection = chunk.startTag + chunk.selection + chunk.endTag;
      chunk.startTag = chunk.endTag = "";
      if (/\n\n/.test(chunk.selection)) {
        this.addLinkDef(chunk, null);
        return;
      }
      var that = this;
      // The function to be executed when you enter a link and press OK or Cancel.
      // Marks up the link and adds the ref.
      var linkEnteredCallback = function(link) {
        background.parentNode.removeChild(background);
        if (link !== null) {
          // (                          $1
          //     [^\\]                  anything that's not a backslash
          //     (?:\\\\)*              an even number (this includes zero) of backslashes
          // )
          // (?=                        followed by
          //     [[\]]                  an opening or closing bracket
          // )
          //
          // In other words, a non-escaped bracket. These have to be escaped now to make sure they
          // don't count as the end of the link or similar.
          // Note that the actual bracket has to be a lookahead, because (in case of to subsequent brackets),
          // the bracket in one match may be the "not a backslash" character in the next match, so it
          // should not be consumed by the first match.
          // The "prepend a space and finally remove it" steps makes sure there is a "not a backslash" at the
          // start of the string, so this also works if the selection begins with a bracket. We cannot solve
          // this by anchoring with ^, because in the case that the selection starts with two brackets, this
          // would mean a zero-width match at the start. Since zero-width matches advance the string position,
          // the first bracket could then not act as the "not a backslash" for the second.
          chunk.selection = (" " + chunk.selection).replace(/([^\\](?:\\\\)*)(?=[[\]])/g, "$1\\").substr(1);
          // var linkDef = " [999]: " + properlyEncoded(link);
          // var num = that.addLinkDef(chunk, linkDef);
          if (!isImage) {
            chunk.startTag = "[";
            chunk.endTag = "](" + link + ")";
          }
          if (isImage) {
            chunk.startTag += "![";
            chunk.endTag = "](" + link + ")" + chunk.endTag;
          }
          if (!chunk.selection) {
            if (isImage) {
              chunk.selection = that.getString("imagedescription");
            } else {
              chunk.selection = that.getString("linkdescription");
            }
          }
          // if (isImage && convertImagesToLinks) {
          //   that.hooks.imageConvertedToLink();
          // }
        }
        postProcessing();
      };
      background = ui.createBackground();
      if (isImage) {
        if (!this.hooks.insertImageDialog(linkEnteredCallback))
          ui.prompt(this.getString("imagedialog"), imageDefaultText, this.getString("ok"), this.getString("cancel"), linkEnteredCallback);
      } else {
        if (!this.hooks.insertLinkDialog(linkEnteredCallback))
          ui.prompt(this.getString("linkdialog"), linkDefaultText, this.getString("ok"), this.getString("cancel"), linkEnteredCallback);
      }
      return true;
    }
  };
  // When making a list, hitting shift-enter will put your cursor on the next line
  // at the current indent level.
  commandProto.doAutoindent = function(chunk, postProcessing) {
    var commandMgr = this,
      fakeSelection = false;
    chunk.before = chunk.before.replace(/(\n|^)[ ]{0,3}([*+-]|\d+[.])[ \t]*\n$/, "\n\n");
    chunk.before = chunk.before.replace(/(\n|^)[ ]{0,3}>[ \t]*\n$/, "\n\n");
    chunk.before = chunk.before.replace(/(\n|^)[ \t]+\n$/, "\n\n");
    // There's no selection, end the cursor wasn't at the end of the line:
    // The user wants to split the current list item / code line / blockquote line
    // (for the latter it doesn't really matter) in two. Temporarily select the
    // (rest of the) line to achieve this.
    if (!chunk.selection && !/^[ \t]*(?:\n|$)/.test(chunk.after)) {
      chunk.after = chunk.after.replace(/^[^\n]*/, function(wholeMatch) {
        chunk.selection = wholeMatch;
        return "";
      });
      fakeSelection = true;
    }
    if (/(\n|^)[ ]{0,3}([*+-]|\d+[.])[ \t]+.*\n$/.test(chunk.before)) {
      if (commandMgr.doList) {
        commandMgr.doList(chunk);
      }
    }
    if (/(\n|^)[ ]{0,3}>[ \t]+.*\n$/.test(chunk.before)) {
      if (commandMgr.doBlockquote) {
        commandMgr.doBlockquote(chunk);
      }
    }
    if (/(\n|^)(\t|[ ]{4,}).*\n$/.test(chunk.before)) {
      if (commandMgr.doCode) {
        commandMgr.doCode(chunk);
      }
    }
    if (fakeSelection) {
      chunk.after = chunk.selection + chunk.after;
      chunk.selection = "";
    }
  };
  commandProto.doBlockquote = function(chunk, postProcessing) {
    chunk.selection = chunk.selection.replace(/^(\n*)([^\r]+?)(\n*)$/,
      function(totalMatch, newlinesBefore, text, newlinesAfter) {
        chunk.before += newlinesBefore;
        chunk.after = newlinesAfter + chunk.after;
        return text;
      });
    chunk.before = chunk.before.replace(/(>[ \t]*)$/,
      function(totalMatch, blankLine) {
        chunk.selection = blankLine + chunk.selection;
        return "";
      });
    chunk.selection = chunk.selection.replace(/^(\s|>)+$/, "");
    chunk.selection = chunk.selection || this.getString("quoteexample");
    // The original code uses a regular expression to find out how much of the
    // text *directly before* the selection already was a blockquote:
    /*
    if (chunk.before) {
    chunk.before = chunk.before.replace(/\n?$/, "\n");
    }
    chunk.before = chunk.before.replace(/(((\n|^)(\n[ \t]*)*>(.+\n)*.*)+(\n[ \t]*)*$)/,
    function (totalMatch) {
    chunk.startTag = totalMatch;
    return "";
    });
    */
    // This comes down to:
    // Go backwards as many lines a possible, such that each line
    //  a) starts with ">", or
    //  b) is almost empty, except for whitespace, or
    //  c) is preceded by an unbroken chain of non-empty lines
    //     leading up to a line that starts with ">" and at least one more character
    // and in addition
    //  d) at least one line fulfills a)
    //
    // Since this is essentially a backwards-moving regex, it's susceptible to
    // catstrophic backtracking and can cause the browser to hang;
    // see e.g. http://meta.stackexchange.com/questions/9807.
    //
    // Hence we replaced this by a simple state machine that just goes through the
    // lines and checks for a), b), and c).
    var match = "",
      leftOver = "",
      line;
    if (chunk.before) {
      var lines = chunk.before.replace(/\n$/, "").split("\n");
      var inChain = false;
      for (var i = 0; i < lines.length; i++) {
        var good = false;
        line = lines[i];
        inChain = inChain && line.length > 0; // c) any non-empty line continues the chain
        if (/^>/.test(line)) { // a)
          good = true;
          if (!inChain && line.length > 1) // c) any line that starts with ">" and has at least one more character starts the chain
            inChain = true;
        } else if (/^[ \t]*$/.test(line)) { // b)
          good = true;
        } else {
          good = inChain; // c) the line is not empty and does not start with ">", so it matches if and only if we're in the chain
        }
        if (good) {
          match += line + "\n";
        } else {
          leftOver += match + line;
          match = "\n";
        }
      }
      if (!/(^|\n)>/.test(match)) { // d)
        leftOver += match;
        match = "";
      }
    }
    chunk.startTag = match;
    chunk.before = leftOver;
    // end of change
    if (chunk.after) {
      chunk.after = chunk.after.replace(/^\n?/, "\n");
    }
    chunk.after = chunk.after.replace(/^(((\n|^)(\n[ \t]*)*>(.+\n)*.*)+(\n[ \t]*)*)/,
      function(totalMatch) {
        chunk.endTag = totalMatch;
        return "";
      }
    );
    var replaceBlanksInTags = function(useBracket) {
      var replacement = useBracket ? "> " : "";
      if (chunk.startTag) {
        chunk.startTag = chunk.startTag.replace(/\n((>|\s)*)\n$/,
          function(totalMatch, markdown) {
            return "\n" + markdown.replace(/^[ ]{0,3}>?[ \t]*$/gm, replacement) + "\n";
          });
      }
      if (chunk.endTag) {
        chunk.endTag = chunk.endTag.replace(/^\n((>|\s)*)\n/,
          function(totalMatch, markdown) {
            return "\n" + markdown.replace(/^[ ]{0,3}>?[ \t]*$/gm, replacement) + "\n";
          });
      }
    };
    if (/^(?![ ]{0,3}>)/m.test(chunk.selection)) {
      this.wrap(chunk, SETTINGS.lineLength - 2);
      chunk.selection = chunk.selection.replace(/^/gm, "> ");
      replaceBlanksInTags(true);
      chunk.skipLines();
    } else {
      chunk.selection = chunk.selection.replace(/^[ ]{0,3}> ?/gm, "");
      this.unwrap(chunk);
      replaceBlanksInTags(false);
      if (!/^(\n|^)[ ]{0,3}>/.test(chunk.selection) && chunk.startTag) {
        chunk.startTag = chunk.startTag.replace(/\n{0,2}$/, "\n\n");
      }
      if (!/(\n|^)[ ]{0,3}>.*$/.test(chunk.selection) && chunk.endTag) {
        chunk.endTag = chunk.endTag.replace(/^\n{0,2}/, "\n\n");
      }
    }
    chunk.selection = this.hooks.postBlockquoteCreation(chunk.selection);
    if (!/\n/.test(chunk.selection)) {
      chunk.selection = chunk.selection.replace(/^(> *)/,
        function(wholeMatch, blanks) {
          chunk.startTag += blanks;
          return "";
        });
    }
  };
  commandProto.doCode = function(chunk, postProcessing) {
    var hasTextBefore = /\S[ ]*$/.test(chunk.before);
    var hasTextAfter = /^[ ]*\S/.test(chunk.after);
    // Use 'four space' markdown if the selection is on its own
    // line or is multiline.
    if ((!hasTextAfter && !hasTextBefore) || /\n/.test(chunk.selection)) {
      chunk.before = chunk.before.replace(/[ ]{4}$/,
        function(totalMatch) {
          chunk.selection = totalMatch + chunk.selection;
          return "";
        });
      var nLinesBack = 1;
      var nLinesForward = 1;
      if (/(\n|^)(\t|[ ]{4,}).*\n$/.test(chunk.before)) {
        nLinesBack = 0;
      }
      if (/^\n(\t|[ ]{4,})/.test(chunk.after)) {
        nLinesForward = 0;
      }
      chunk.skipLines(nLinesBack, nLinesForward);
      if (!chunk.selection) {
        chunk.startTag = "    ";
        chunk.selection = this.getString("codeexample");
      } else {
        if (/^[ ]{0,3}\S/m.test(chunk.selection)) {
          if (/\n/.test(chunk.selection))
            chunk.selection = chunk.selection.replace(/^/gm, "    ");
          else // if it's not multiline, do not select the four added spaces; this is more consistent with the doList behavior
            chunk.before += "    ";
        } else {
          chunk.selection = chunk.selection.replace(/^(?:[ ]{4}|[ ]{0,3}\t)/gm, "");
        }
      }
    } else {
      // Use backticks (`) to delimit the code block.
      chunk.trimWhitespace();
      chunk.findTags(/`/, /`/);
      if (!chunk.startTag && !chunk.endTag) {
        chunk.startTag = chunk.endTag = "`";
        if (!chunk.selection) {
          chunk.selection = this.getString("codeexample");
        }
      } else if (chunk.endTag && !chunk.startTag) {
        chunk.before += chunk.endTag;
        chunk.endTag = "";
      } else {
        chunk.startTag = chunk.endTag = "";
      }
    }
  };
  commandProto.doList = function(chunk, postProcessing, isNumberedList) {
    // These are identical except at the very beginning and end.
    // Should probably use the regex extension function to make this clearer.
    var previousItemsRegex = /(\n|^)(([ ]{0,3}([*+-]|\d+[.])[ \t]+.*)(\n.+|\n{2,}([*+-].*|\d+[.])[ \t]+.*|\n{2,}[ \t]+\S.*)*)\n*$/;
    var nextItemsRegex = /^\n*(([ ]{0,3}([*+-]|\d+[.])[ \t]+.*)(\n.+|\n{2,}([*+-].*|\d+[.])[ \t]+.*|\n{2,}[ \t]+\S.*)*)\n*/;
    // The default bullet is a dash but others are possible.
    // This has nothing to do with the particular HTML bullet,
    // it's just a markdown bullet.
    var bullet = "-";
    // The number in a numbered list.
    var num = 1;
    // Get the item prefix - e.g. " 1. " for a numbered list, " - " for a bulleted list.
    var getItemPrefix = function() {
      var prefix;
      if (isNumberedList) {
        prefix = " " + num + ". ";
        num++;
      } else {
        prefix = " " + bullet + " ";
      }
      return prefix;
    };
    // Fixes the prefixes of the other list items.
    var getPrefixedItem = function(itemText) {
      // The numbering flag is unset when called by autoindent.
      if (isNumberedList === undefined) {
        isNumberedList = /^\s*\d/.test(itemText);
      }
      // Renumber/bullet the list element.
      itemText = itemText.replace(/^[ ]{0,3}([*+-]|\d+[.])\s/gm,
        function(_) {
          return getItemPrefix();
        });
      return itemText;
    };
    chunk.findTags(/(\n|^)*[ ]{0,3}([*+-]|\d+[.])\s+/, null);
    if (chunk.before && !/\n$/.test(chunk.before) && !/^\n/.test(chunk.startTag)) {
      chunk.before += chunk.startTag;
      chunk.startTag = "";
    }
    if (chunk.startTag) {
      var hasDigits = /\d+[.]/.test(chunk.startTag);
      chunk.startTag = "";
      chunk.selection = chunk.selection.replace(/\n[ ]{4}/g, "\n");
      this.unwrap(chunk);
      chunk.skipLines();
      if (hasDigits) {
        // Have to renumber the bullet points if this is a numbered list.
        chunk.after = chunk.after.replace(nextItemsRegex, getPrefixedItem);
      }
      if (isNumberedList == hasDigits) {
        return;
      }
    }
    var nLinesUp = 1;
    chunk.before = chunk.before.replace(previousItemsRegex,
      function(itemText) {
        if (/^\s*([*+-])/.test(itemText)) {
          bullet = re.$1;
        }
        nLinesUp = /[^\n]\n\n[^\n]/.test(itemText) ? 1 : 0;
        return getPrefixedItem(itemText);
      });
    if (!chunk.selection) {
      chunk.selection = this.getString("litem");
    }
    var prefix = getItemPrefix();
    var nLinesDown = 1;
    chunk.after = chunk.after.replace(nextItemsRegex,
      function(itemText) {
        nLinesDown = /[^\n]\n\n[^\n]/.test(itemText) ? 1 : 0;
        return getPrefixedItem(itemText);
      });
    chunk.trimWhitespace(true);
    chunk.skipLines(nLinesUp, nLinesDown, true);
    chunk.startTag = prefix;
    var spaces = prefix.replace(/./g, " ");
    this.wrap(chunk, SETTINGS.lineLength - spaces.length);
    chunk.selection = chunk.selection.replace(/\n/g, "\n" + spaces);
  };
  commandProto.doHeading = function(chunk, postProcessing) {
    // Remove leading/trailing whitespace and reduce internal spaces to single spaces.
    chunk.selection = chunk.selection.replace(/\s+/g, " ");
    chunk.selection = chunk.selection.replace(/(^\s+|\s+$)/g, "");
    // If we clicked the button with no selected text, we just
    // make a level 2 hash header around some default text.
    if (!chunk.selection) {
      chunk.startTag = "## ";
      chunk.selection = this.getString("headingexample");
      chunk.endTag = " ##";
      return;
    }
    var headerLevel = 0; // The existing header level of the selected text.
    // Remove any existing hash heading markdown and save the header level.
    chunk.findTags(/#+[ ]*/, /[ ]*#+/);
    if (/#+/.test(chunk.startTag)) {
      headerLevel = re.lastMatch.length;
    }
    chunk.startTag = chunk.endTag = "";
    // Try to get the current header level by looking for - and = in the line
    // below the selection.
    chunk.findTags(null, /\s?(-+|=+)/);
    if (/=+/.test(chunk.endTag)) {
      headerLevel = 1;
    }
    if (/-+/.test(chunk.endTag)) {
      headerLevel = 2;
    }
    // Skip to the next line so we can create the header markdown.
    chunk.startTag = chunk.endTag = "";
    chunk.skipLines(1, 1);
    // We make a level 2 header if there is no current header.
    // If there is a header level, we subtract one from the header level.
    // If it's already a level 1 header, it's removed.
    var headerLevelToCreate = headerLevel == 0 ? 2 : headerLevel - 1;
    if (headerLevelToCreate > 0) {
      // The button only creates level 1 and 2 underline headers.
      // Why not have it iterate over hash header levels?  Wouldn't that be easier and cleaner?
      var headerChar = headerLevelToCreate >= 2 ? "-" : "=";
      var len = chunk.selection.length;
      if (len > SETTINGS.lineLength) {
        len = SETTINGS.lineLength;
      }
      chunk.endTag = "\n";
      while (len--) {
        chunk.endTag += headerChar;
      }
    }
  };
  commandProto.doHorizontalRule = function(chunk, postProcessing) {
    chunk.startTag = "----------\n";
    chunk.selection = "";
    chunk.skipLines(2, 1, true);
  }

  

})();