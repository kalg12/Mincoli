@props(['model'])

<div
    x-data="{
        editor: null,
        content: @entangle($attributes->wire('model')),
        showPreviewModal: false,
        isFullscreen: false,
        wordCount: 0,
        init() {
            this.editor = new window.Editor({
                element: this.$refs.editor,
                extensions: [
                    window.StarterKit,
                    window.Image,
                    window.Link,
                    window.Typography,
                    window.Underline,
                    window.Placeholder.configure({
                        placeholder: 'Comienza a escribir tu artículo...',
                    }),
                    window.TextAlign.configure({
                        types: ['heading', 'paragraph'],
                    }),
                ],
                content: this.content,
                editorProps: {
                    attributes: {
                        class: 'prose prose-lg dark:prose-invert max-w-none focus:outline-none min-h-[400px] p-6 editor-content'
                    }
                },
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                    this.updateWordCount();
                }
            });

            this.$watch('content', (value) => {
                if (this.editor.getHTML() !== value) {
                    this.editor.commands.setContent(value, false);
                }
            });
            
            this.updateWordCount();
        },
        updateWordCount() {
            const text = this.editor.getText();
            this.wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        },
        openImageUpload() {
            this.$refs.imageInput.click();
        },
        async uploadImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('{{ route('dashboard.upload.image') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                    }
                });
                
                if (!response.ok) throw new Error('Upload failed');
                
                const data = await response.json();
                if (data.url) {
                    this.editor.chain().focus().setImage({ src: data.url }).run();
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                alert('Error al subir la imagen. Intente nuevamente.');
            }
            
            event.target.value = '';
        },
        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen;
            if (this.isFullscreen) {
                this.$refs.editorContainer.classList.add('fixed', 'inset-0', 'z-50', 'bg-white', 'dark:bg-zinc-900');
            } else {
                this.$refs.editorContainer.classList.remove('fixed', 'inset-0', 'z-50', 'bg-white', 'dark:bg-zinc-900');
            }
        }
    }"
    x-ref="editorContainer"
    class="border border-zinc-300 rounded-lg overflow-hidden dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm transition-all"
    wire:ignore
>
    <!-- Hidden Image Input -->
    <input type="file" x-ref="imageInput" class="hidden" accept="image/*" @change="uploadImage($event)">

    <!-- Toolbar -->
    <div class="border-b border-zinc-200 bg-white px-4 py-3 flex flex-wrap items-center gap-3 dark:border-zinc-700 dark:bg-zinc-900 sticky top-0 z-10">
        <!-- Text Formatting Group -->
        <div class="flex items-center gap-1 border-r border-zinc-200 dark:border-zinc-700 pr-3">
            <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('bold') }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all w-9 h-9 flex items-center justify-center" title="Negrita (Ctrl+B)">
                <span class="font-bold text-base font-serif">B</span>
            </button>
            <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('italic') }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all w-9 h-9 flex items-center justify-center" title="Cursiva (Ctrl+I)">
                <span class="italic text-base font-serif">I</span>
            </button>
            <button type="button" @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('underline') }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all w-9 h-9 flex items-center justify-center" title="Subrayado (Ctrl+U)">
                <span class="underline text-base font-serif">U</span>
            </button>
            <button type="button" @click="editor.chain().focus().toggleStrike().run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('strike') }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all w-9 h-9 flex items-center justify-center" title="Tachado">
                <span class="line-through text-base font-serif">S</span>
            </button>
        </div>
        
        <!-- Headings Group -->
        <div class="flex items-center gap-1 border-r border-zinc-200 dark:border-zinc-700 pr-3">
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('heading', { level: 2 }) }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-sm transition-all" title="Título 2">
                H2
            </button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('heading', { level: 3 }) }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-sm transition-all" title="Título 3">
                H3
            </button>
        </div>
        
        <!-- Alignment Group -->
        <div class="flex items-center gap-1 border-r border-zinc-200 dark:border-zinc-700 pr-3">
            <button type="button" @click="editor.chain().focus().setTextAlign('left').run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive({ textAlign: 'left' }) }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Alinear izquierda">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16" /></svg>
            </button>
            <button type="button" @click="editor.chain().focus().setTextAlign('center').run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive({ textAlign: 'center' }) }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Centrar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16" /></svg>
            </button>
            <button type="button" @click="editor.chain().focus().setTextAlign('right').run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive({ textAlign: 'right' }) }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Alinear derecha">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M14 12h6M4 18h16" /></svg>
            </button>
        </div>

        <!-- Lists Group -->
        <div class="flex items-center gap-1 border-r border-zinc-200 dark:border-zinc-700 pr-3">
            <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('bulletList') }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Lista con viñetas">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': editor?.isActive('orderedList') }" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Lista numerada">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h12M7 13h12M7 19h12M3 7v.01M3 13v.01M3 19v.01" /></svg>
            </button>
        </div>
        
        <!-- Media & Actions -->
        <div class="flex items-center gap-1 border-r border-zinc-200 dark:border-zinc-700 pr-3">
            <button type="button" @click="openImageUpload()" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Insertar Imagen">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </button>
            <button type="button" @click="editor.chain().focus().undo().run()" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Deshacer (Ctrl+Z)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
            </button>
            <button type="button" @click="editor.chain().focus().redo().run()" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Rehacer (Ctrl+Y)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6" /></svg>
            </button>
        </div>

        <!-- View Controls -->
        <div class="flex items-center gap-2 ml-auto">
            <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="`${wordCount} palabras`"></span>
            <button type="button" @click="showPreviewModal = true" class="px-3 py-1.5 rounded text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span>Vista Previa</span>
            </button>
            <button type="button" @click="toggleFullscreen()" class="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 transition-all" title="Pantalla completa">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!isFullscreen"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="isFullscreen" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    <!-- Editor Area -->
    <div class="relative" :class="{ 'h-[calc(100vh-4rem)]': isFullscreen, 'min-h-[400px]': !isFullscreen }">
        <div x-ref="editor" class="bg-white dark:bg-zinc-900 overflow-y-auto" :class="{ 'h-full': isFullscreen }"></div>
    </div>
    
    <!-- Preview Modal -->
    <div x-show="showPreviewModal" 
         x-cloak
         @click.self="showPreviewModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
         style="display: none;">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Vista Previa del Artículo</h3>
                <button @click="showPreviewModal = false" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 dark:text-zinc-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Use prose-preview for robust styling matching the editor -->
                <article class="prose-preview max-w-none dark:text-gray-100" x-html="content"></article>
            </div>
        </div>
    </div>
    
    <style>
        /* Placeholder styling */
        .ProseMirror p.is-editor-empty:first-child::before {
            content: attr(data-placeholder);
            float: left;
            color: #9ca3af;
            pointer-events: none;
            height: 0;
        }
        
        /* Editor base styles - Force appearance */
        .ProseMirror {
            outline: none;
        }

        /* Bulletproof Typography for Editor & Preview */
        .editor-content, .prose-preview {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.75;
        }

        .editor-content p, .prose-preview p {
            margin-bottom: 1.25em !important;
            font-size: 1.125rem !important; /* text-lg */
        }
        
        .editor-content h2, .prose-preview h2 {
            font-size: 1.875rem !important; /* 3xl */
            font-weight: 800 !important;
            margin-top: 2em !important;
            margin-bottom: 1em !important;
            line-height: 1.2 !important;
            color: inherit !important;
        }
        
        .editor-content h3, .prose-preview h3 {
            font-size: 1.5rem !important; /* 2xl */
            font-weight: 700 !important;
            margin-top: 1.5em !important;
            margin-bottom: 0.75em !important;
            line-height: 1.3 !important;
            color: inherit !important;
        }
        
        .editor-content strong, .prose-preview strong {
            font-weight: 800 !important;
            color: inherit !important;
        }
        
        .editor-content em, .prose-preview em {
            font-style: italic !important;
        }
        
        .editor-content u, .prose-preview u {
            text-decoration: underline !important;
            text-underline-offset: 4px !important;
        }
        
        .editor-content s, .prose-preview s {
            text-decoration: line-through !important;
            color: #71717a !important; /* zinc-500 */
        }
        
        .editor-content ul, .prose-preview ul {
            list-style-type: disc !important;
            padding-left: 1.625em !important;
            margin-bottom: 1.25em !important;
        }

        .editor-content ol, .prose-preview ol {
            list-style-type: decimal !important;
            padding-left: 1.625em !important;
            margin-bottom: 1.25em !important;
        }
        
        .editor-content li, .prose-preview li {
            margin-bottom: 0.5em !important;
            padding-left: 0.375em !important;
        }
        
        .editor-content img, .prose-preview img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 0.5rem !important;
            margin: 2em 0 !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
        }
        
        /* Force Alignment Styles - Critical Fix */
        .editor-content [style*="text-align: center"], .prose-preview [style*="text-align: center"] {
            text-align: center !important;
            display: block !important;
        }
        
        .editor-content [style*="text-align: right"], .prose-preview [style*="text-align: right"] {
            text-align: right !important;
            display: block !important;
        }
        
        .editor-content [style*="text-align: left"], .prose-preview [style*="text-align: left"] {
            text-align: left !important;
            display: block !important;
        }

        /* Blockquote */
        .editor-content blockquote, .prose-preview blockquote {
            font-weight: 500;
            font-style: italic;
            color: #111827;
            border-left-width: 0.25rem;
            border-left-color: #e5e7eb;
            margin-top: 1.6em;
            margin-bottom: 1.6em;
            padding-left: 1em;
        }
        .dark .editor-content blockquote, .dark .prose-preview blockquote {
            color: #f3f4f6;
            border-left-color: #374151;
        }

        /* Alpine x-cloak */
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
