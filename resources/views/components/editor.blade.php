@props(['model'])

<div
    x-data="{
        editor: null,
        content: @entangle($attributes->wire('model')),
        init() {
            this.editor = new window.Editor({
                element: this.$refs.editor,
                extensions: [
                    window.StarterKit,
                    window.Image,
                    window.Link,
                    window.Placeholder.configure({
                        placeholder: 'Escribe aquí tu contenido increíble...',
                    }),
                    window.TextAlign.configure({
                        types: ['heading', 'paragraph'],
                    }),
                ],
                content: this.content,
                editorProps: {
                    attributes: {
                        class: 'prose dark:prose-invert max-w-none focus:outline-none min-h-[300px] p-4'
                    }
                },
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                }
            });

            this.$watch('content', (value) => {
                if (this.editor.getHTML() !== value) {
                    this.editor.commands.setContent(value, false);
                }
            });
        },
        openImageUpload() {
            this.$refs.imageInput.click();
        },
        async uploadImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            // Show loading state if desired, or just upload
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
            
            // Reset input
            event.target.value = '';
        }
    }"
    class="border border-zinc-300 rounded-lg overflow-hidden dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm"
    wire:ignore
>
    <!-- Hidden Image Input -->
    <input type="file" x-ref="imageInput" class="hidden" accept="image/*" @change="uploadImage($event)">

    <!-- Toolbar -->
    <div class="border-b border-zinc-200 bg-gray-50 px-3 py-2 flex flex-wrap gap-2 dark:border-zinc-700 dark:bg-zinc-800 sticky top-0 z-10">
        <!-- Text Formatting -->
        <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('bold') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors w-8 h-8 flex items-center justify-center" title="Negrita">
            <span class="font-bold text-lg font-serif">B</span>
        </button>
        <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('italic') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors w-8 h-8 flex items-center justify-center" title="Cursiva">
            <span class="italic text-lg font-serif">I</span>
        </button>
        
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>
        
        <!-- Headings -->
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('heading', { level: 2 }) }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-bold text-sm transition-colors" title="Título 2">
            H2
        </button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('heading', { level: 3 }) }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-bold text-sm transition-colors" title="Título 3">
            H3
        </button>
        
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>
        
        <!-- Alignment -->
        <button type="button" @click="editor.chain().focus().setTextAlign('left').run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive({ textAlign: 'left' }) }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors" title="Alinear izquierda">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16" /></svg>
        </button>
        <button type="button" @click="editor.chain().focus().setTextAlign('center').run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive({ textAlign: 'center' }) }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors" title="Centrar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16" /></svg>
        </button>
        
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>

        <!-- Lists -->
        <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('bulletList') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors" title="Lista con viñetas">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('orderedList') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors" title="Lista numerada">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h12M7 13h12M7 19h12M3 7v.01M3 13v.01M3 19v.01" /></svg>
        </button>
        
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>
        
        <!-- Media -->
        <button type="button" @click="openImageUpload()" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 transition-colors" title="Insertar Imagen">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
        </button>
    </div>

    <!-- Editor Area -->
    <div x-ref="editor" class="bg-gray-50/50 dark:bg-zinc-900/50"></div>
    
    <style>
        .ProseMirror p.is-editor-empty:first-child::before {
            content: attr(data-placeholder);
            float: left;
            color: #9ca3af;
            pointer-events: none;
            height: 0;
        }
        .ProseMirror {
            outline: none;
            min-height: 300px;
        }
    </style>
</div>
