/**
 * EditorJS-like block UI wrapper for Cherry Markdown
 */
class CherryEditorJSLike {
    constructor(cherryInstance, options = {}) {
        this.cherry = cherryInstance;
        this.options = {
            containerSelector: '#markdown-container',
            debug: false,
            ...options
        };
        
        this.container = document.querySelector(this.options.containerSelector);
        this.codeMirror = null;
        this.currentBlock = null;
        this.blockMenu = null;
        this.gutterBtn = null;
        this.isPreview = false;
        
        this.init();
    }
    
    init() {
        // Wrap container
        this.container.classList.add('cherry-editorjs-like');
        
        // Add CSS
        this.addStyles();
        
        // Initialize gutter button
        this.createGutterButton();
        
        // Initialize block menu
        this.createBlockMenu();
        
        // Resolve CodeMirror instance and then bind
        this.resolveCodeMirror().then((cm) => {
            this.codeMirror = cm;
            this.bindEvents();
            this.positionGutterButton();
            // Initial rendering of all lines
            this.codeMirror.eachLine(line => {
                this.renderBlock(line.lineNo());
            });
            this.log('Initialized EditorJS-like wrapper');
        }).catch((e) => this.log('Failed to resolve CodeMirror: ' + e));
    }

    resolveCodeMirror() {
        return new Promise((resolve, reject) => {
            let attempts = 0;
            const maxAttempts = 40; // ~2s
            const tryResolve = () => {
                attempts++;
                // Try via official exposure first
                let cm = null;
                if (this.cherry && this.cherry.editor && (this.cherry.editor.codemirror || this.cherry.editor.editor)) {
                    cm = this.cherry.editor.codemirror || this.cherry.editor.editor;
                }
                // Fallback: from DOM element
                if (!cm) {
                    const cmEl = this.container.querySelector('.CodeMirror');
                    if (cmEl && cmEl.CodeMirror) cm = cmEl.CodeMirror;
                }
                if (cm && typeof cm.getCursor === 'function') {
                    return resolve(cm);
                }
                if (attempts >= maxAttempts) return reject('timeout');
                setTimeout(tryResolve, 50);
            };
            tryResolve();
        });
    }

    addStyles() {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = '/assets/js/editor/editorjs-like.css';
        document.head.appendChild(link);
    }
    
    createGutterButton() {
        // Gutter container
        this.gutterContainer = document.createElement('div');
        this.gutterContainer.className = 'cmi-gutter-container hidden';
        this.container.appendChild(this.gutterContainer);

        // + button
        this.gutterBtn = document.createElement('div');
        this.gutterBtn.className = 'cmi-gutter-btn';
        this.gutterBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>`;
        this.gutterContainer.appendChild(this.gutterBtn);
        
        this.gutterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.showBlockMenu(e);
        });

        // Preview button
        this.previewBtn = document.createElement('div');
        this.previewBtn.className = 'cmi-gutter-btn cmi-preview-btn';
        this.previewBtn.title = 'Просмотр';
        this.updatePreviewIcon();
        this.gutterContainer.appendChild(this.previewBtn);

        this.previewBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.togglePreview();
        });
    }
    
    createBlockMenu() {
        this.blockMenu = document.createElement('div');
        this.blockMenu.className = 'cmi-block-menu';
        this.blockMenu.style.display = 'none';
        
        const blocks = [
            { key: 'text', icon: 'T', title: 'Текст', desc: 'Обычный текст' },
            { key: 'h1', icon: '<i class="ch-icon ch-icon-h1"></i>', title: 'Заголовок 1', desc: 'Крупный заголовок' },
            { key: 'h2', icon: '<i class="ch-icon ch-icon-h2"></i>', title: 'Заголовок 2', desc: 'Средний заголовок' },
            { key: 'h3', icon: '<i class="ch-icon ch-icon-h3"></i>', title: 'Заголовок 3', desc: 'Малый заголовок' },
            { key: 'list', icon: '<i class="ch-icon ch-icon-ul"></i>', title: 'Список', desc: 'Маркированный список' },
            { key: 'checklist', icon: '<i class="ch-icon ch-icon-checklist"></i>', title: 'Чек-лист', desc: 'Список с отметками' },
            { key: 'quote', icon: '<i class="ch-icon ch-icon-blockquote"></i>', title: 'Цитата', desc: 'Выделенная цитата' },
            { key: 'code', icon: '<i class="ch-icon ch-icon-codeBlock"></i>', title: 'Код', desc: 'Блок кода' },
            { key: 'table', icon: '<i class="ch-icon ch-icon-table"></i>', title: 'Таблица', desc: 'Таблица с данными' },
            { key: 'image', icon: '<i class="ch-icon ch-icon-image"></i>', title: 'Изображение', desc: 'Вставить изображение' }
        ];
        
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'cmi-search';
        searchInput.placeholder = 'Фильтр...';
        this.blockMenu.appendChild(searchInput);
        
        const menuItemsContainer = document.createElement('div');
        menuItemsContainer.className = 'cmi-menu-items-container';
        this.blockMenu.appendChild(menuItemsContainer);

        blocks.forEach(block => {
            const item = document.createElement('div');
            item.className = 'cmi-menu-item';
            item.dataset.key = block.key;
            item.innerHTML = `
                <span class="cmi-ic">${block.icon}</span>
                <div>
                    <div class="cmi-title">${block.title}</div>
                    <div class="cmi-desc">${block.desc}</div>
                </div>
            `;
            
            item.addEventListener('click', () => {
                this.insertBlock(block.key);
                this.hideBlockMenu();
            });
            
            menuItemsContainer.appendChild(item);
        });
        
        // Search filter
        searchInput.addEventListener('input', (e) => {
            const filter = e.target.value.toLowerCase();
            const items = this.blockMenu.querySelectorAll('.cmi-menu-item');
            items.forEach(item => {
                const title = item.querySelector('.cmi-title').textContent.toLowerCase();
                item.style.display = title.includes(filter) ? 'flex' : 'none';
            });
        });
        
        this.container.appendChild(this.blockMenu);
this.blockMenu.style.position = 'absolute';
    }
    
    bindEvents() {
        // Position gutter button on cursor/scroll
        this.codeMirror.on('cursorActivity', this.positionGutterButton.bind(this));
        this.codeMirror.on('scroll', this.positionGutterButton.bind(this));
        this.codeMirror.on('change', this.handleChange.bind(this));
        
        // Hide menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.blockMenu.contains(e.target) && !this.gutterBtn.contains(e.target)) {
                this.hideBlockMenu();
            }
        });
        
        // ESC to close menu
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideBlockMenu();
            }
        });

        // Reposition menu on resize (for mobile keyboard)
        window.addEventListener('resize', () => {
            if (this.blockMenu.style.display !== 'none') {
                this.updateMenuPosition();
            }
        });

        // Reposition menu on editor scroll
        this.codeMirror.on('scroll', () => {
            if (this.blockMenu.style.display !== 'none') {
                this.updateMenuPosition();
            }
        });
    }

    updateMenuPosition() {
        let buttonTop = parseFloat(this.gutterBtn.style.top) || 0;
        let buttonLeft = parseFloat(this.gutterBtn.style.left) || 10;
        let buttonHeight = this.gutterBtn.offsetHeight;

        let finalTop = buttonTop + buttonHeight + 6;
        let finalLeft = buttonLeft;

        const containerWidth = this.container.offsetWidth;
        const menuWidth = this.blockMenu.offsetWidth;

        if (finalLeft + menuWidth > containerWidth) {
            finalLeft = containerWidth - menuWidth - 10;
        }
        if (finalLeft < 0) {
            finalLeft = 10;
        }

        this.blockMenu.style.top = `${finalTop}px`;
        this.blockMenu.style.left = `${finalLeft}px`;
    }

    renderBlock(lineNumber) {
        const lineContent = this.codeMirror.getLine(lineNumber);
        const lineHandle = this.codeMirror.getLineHandle(lineNumber);
        if (!lineHandle) return;

        // Remove all previous block-specific classes
        const classesToRemove = ['cm-block-h1', 'cm-block-h2', 'cm-block-h3', 'cm-block-list', 'cm-block-quote', 'cm-block-code', 'cm-block-table', 'cm-block-text'];
        classesToRemove.forEach(cls => {
            this.codeMirror.removeLineClass(lineNumber, 'background', cls);
        });

        let className = 'cm-block-text'; // Default to text

        // Clear existing marks to prevent duplicates
        this.codeMirror.findMarksAt({line: lineNumber, ch: 0}).forEach(mark => {
            if (mark.className === 'cm-hidden-markdown') {
                mark.clear();
            }
        });

        // Determine block type based on Markdown syntax and apply hidden class
        if (/^#{1,3}\s/.test(lineContent)) {
            if (lineContent.startsWith('# ')) {
                className = 'cm-block-h1';
                this.codeMirror.markText({line: lineNumber, ch: 0}, {line: lineNumber, ch: lineContent.indexOf(' ') + 1}, {className: 'cm-hidden-markdown'});
            } else if (lineContent.startsWith('## ')) {
                className = 'cm-block-h2';
                this.codeMirror.markText({line: lineNumber, ch: 0}, {line: lineNumber, ch: lineContent.indexOf(' ') + 1}, {className: 'cm-hidden-markdown'});
            } else if (lineContent.startsWith('### ')) {
                className = 'cm-block-h3';
                this.codeMirror.markText({line: lineNumber, ch: 0}, {line: lineNumber, ch: lineContent.indexOf(' ') + 1}, {className: 'cm-hidden-markdown'});
            }
        } else if (/^[-*+]\s/.test(lineContent) || /^[0-9]+\.\s/.test(lineContent)) {
            className = 'cm-block-list';
            this.codeMirror.markText({line: lineNumber, ch: 0}, {line: lineNumber, ch: lineContent.indexOf(' ') + 1}, {className: 'cm-hidden-markdown'});
        } else if (/^>\s/.test(lineContent)) {
            className = 'cm-block-quote';
            this.codeMirror.markText({line: lineNumber, ch: 0}, {line: lineNumber, ch: lineContent.indexOf(' ') + 1}, {className: 'cm-hidden-markdown'});
        } else if (/^```/.test(lineContent)) {
            className = 'cm-block-code';
            // For code blocks, hide the fence only
            this.codeMirror.markText({line: lineNumber, ch: 0}, {line: lineNumber, ch: 3}, {className: 'cm-hidden-markdown'});
        } else if (/^\|/.test(lineContent)) {
            className = 'cm-block-table';
            // Table markdown is complex, might need more specific hiding
        }

        this.codeMirror.addLineClass(lineNumber, 'background', className);
    }

    handleChange(instance, change) {
        // Re-render only the affected lines
        for (let i = change.from.line; i <= change.to.line; i++) {
            this.renderBlock(i);
        }
        // If lines were added, render them too
        if (change.text.length > 1) {
            for (let i = change.to.line + 1; i < change.to.line + change.text.length; i++) {
                this.renderBlock(i);
            }
        }
    }
    
    positionGutterButton() {
        if (!this.codeMirror) return;
        const cursor = this.codeMirror.getCursor();
        const coords = this.codeMirror.cursorCoords(cursor, 'local');
        const scrollInfo = this.codeMirror.getScrollInfo();
        
        // Position relative to editor viewport
        const lineHeight = this.codeMirror.defaultTextHeight();
        const buttonHeight = 28;
        const topOffset = (lineHeight - buttonHeight) / 2;
        const top = coords.top - scrollInfo.top + topOffset;
        
        this.gutterContainer.style.top = `${top}px`;
        this.gutterContainer.classList.remove('hidden');
        
        this.log(`Positioned gutter container at cursor line ${cursor.line}, top: ${top}px`);
    }
    
    showBlockMenu(event) {
        this.blockMenu.style.display = 'block';
        this.updateMenuPosition();
        
        // Focus search input
        const searchInput = this.blockMenu.querySelector('.cmi-search');
        searchInput.value = '';
        searchInput.focus();
        
        // Reset filter
        const items = this.blockMenu.querySelectorAll('.cmi-menu-item');
        items.forEach(item => item.style.display = 'flex');
        
        this.log('Showed block menu');
    }
    
    hideBlockMenu() {
        this.blockMenu.style.display = 'none';
        this.log('Hidden block menu');
    }
    
    insertBlock(blockType) {
        if (!this.codeMirror) return;
        const cursor = this.codeMirror.getCursor();
        const line = this.codeMirror.getLine(cursor.line);
        
        let text = '';
        let cursorOffset = 0;
        
        switch (blockType) {
            case 'h1':
                text = '# ';
                cursorOffset = 2;
                break;
            case 'h2':
                text = '## ';
                cursorOffset = 3;
                break;
            case 'h3':
                text = '### ';
                cursorOffset = 4;
                break;
            case 'list':
                text = '* Первый пункт\n* Второй пункт\n* Третий пункт';
                cursorOffset = 14; // After '* Первый пункт'
                break;
            case 'checklist':
                text = '- [ ] Item 1\n    - [ ] Item 1.1\n- [ ] Item 2';
                cursorOffset = 12; // After '- [ ] Item 1'
                break;
            case 'quote':
                text = '> ';
                cursorOffset = 2;
                break;
            case 'code':
                text = '```\ncode...\n```';
                cursorOffset = 4;
                break;
            case 'table':
                text = '| Заголовок 1 | Заголовок 2 |\n|-------------|-------------|\n| Ячейка 1    | Ячейка 2    |';
                cursorOffset = 0;
                break;
            case 'image':
                this.insertImage();
                return;
                break;
            default:
                // For 'text' or unknown, just position cursor
                cursorOffset = 0;
                break;
        }
        
        // Clear current line if empty, or go to new line
        if (line.trim() === '') {
            this.codeMirror.replaceRange(text, { line: cursor.line, ch: 0 }, { line: cursor.line, ch: line.length });
            this.codeMirror.setCursor({ line: cursor.line, ch: cursorOffset });
            this.renderBlock(cursor.line);
        } else {
            // Insert at end of current line
            this.codeMirror.replaceRange('\n' + text, { line: cursor.line, ch: line.length });
            this.codeMirror.setCursor({ line: cursor.line + 1, ch: cursorOffset });
            this.renderBlock(cursor.line + 1);
        }
        
        this.codeMirror.focus();
        this.log(`Inserted block: ${blockType}`);
    }
    
    insertImage() {
        if (!window.fileUpload) {
            console.error('fileUpload not defined');
            return;
        }
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (!file) return;
            window.fileUpload(file, (url) => {
                const alt = file.name || 'image';
                const md = `![${alt}](${url})`;
                const cursor = this.codeMirror.getCursor();
                this.codeMirror.replaceRange(md + '\n', cursor);
                this.codeMirror.setCursor({ line: cursor.line + 1, ch: 0 });
                this.codeMirror.focus();
            });
        };
        input.click();
    }
    
    log(message) {
        if (this.options.debug) {
            console.log(`[CherryEditorJSLike] ${message}`);
        }
    }

    togglePreview() {
        console.log('Toggle preview called. Current isPreview:', this.isPreview);
        let newMode = this.isPreview ? 'editOnly' : 'previewOnly';
        console.log('Switching to mode:', newMode);
        this.cherry.switchModel(newMode);
        console.log('Mode switched. cherry.previewOnly:', this.cherry.previewOnly);
        this.isPreview = !this.isPreview;
        this.updatePreviewIcon();
        console.log('Icon updated. New isPreview:', this.isPreview);
        // Hide cherry-toolbar with delay
        setTimeout(() => {
            const toolbar = document.querySelector('.cherry-toolbar');
            if (toolbar) {
                toolbar.style.display = 'none';
            }
        }, 100);
    }

    updatePreviewIcon() {
        if (this.isPreview) {
            this.previewBtn.innerHTML = '<i class="ch-icon ch-icon-previewClose"></i>';
            this.previewBtn.title = 'Редактирование';
        } else {
            this.previewBtn.innerHTML = '<i class="ch-icon ch-icon-preview"></i>';
            this.previewBtn.title = 'Просмотр';
        }
    }
}

// Auto-initialize if window.cherry exists
if (typeof window !== 'undefined') {
    window.CherryEditorJSLike = CherryEditorJSLike;
    
// Auto-init when Cherry is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Wait a bit for Cherry to initialize
        setTimeout(() => {
            if (window.cherry && document.querySelector('#markdown-container')) {
                window.cherryEditorJS = new CherryEditorJSLike(window.cherry, { debug: true });
            }
        }, 500);
    });
}