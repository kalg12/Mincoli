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
        }
    }"
    class="border border-zinc-300 rounded-lg overflow-hidden dark:border-zinc-700 bg-white dark:bg-zinc-900"
    wire:ignore
>
    <!-- Toolbar -->
    <div class="border-b border-zinc-200 bg-gray-50 px-3 py-2 flex flex-wrap gap-2 dark:border-zinc-700 dark:bg-zinc-800">
        <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('bold') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h8a4 4 0 10-6.8-2.6L6 12zm0 0l1.2-1.6a4 4 0 106.8-2.6L6 12zM6 12v8" /></svg>
        </button>
        <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('italic') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l8 8M6 16l4 8" /></svg>
        </button>
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('heading', { level: 2 }) }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-bold text-sm">
            H2
        </button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('heading', { level: 3 }) }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-bold text-sm">
            H3
        </button>
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>
        <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('bulletList') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'bg-gray-200 dark:bg-zinc-700': editor?.isActive('orderedList') }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h12M7 13h12M7 19h12M3 7v.01M3 13v.01M3 19v.01" /></svg>
        </button>
        <div class="w-px h-6 bg-gray-300 dark:bg-zinc-600 mx-1"></div>
        <button type="button" @click="const url = window.prompt('URL:'); if (url) { editor.chain().focus().setImage({ src: url }).run() }" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
        </button>
    </div>

    <!-- Editor Area -->
    <div x-ref="editor"></div>
</div>
