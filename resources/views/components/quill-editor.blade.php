@props(['model', 'label' => 'Contenido'])

<div
    wire:ignore
    x-data="{
        content: @entangle($attributes->wire('model')),
        quill: null,
        init() {
            // Load Quill CSS
            if (!document.getElementById('quill-css')) {
                const link = document.createElement('link');
                link.id = 'quill-css';
                link.href = 'https://cdn.quilljs.com/1.3.6/quill.snow.css';
                link.rel = 'stylesheet';
                document.head.appendChild(link);
            }

            // Load Quill JS
            if (!window.Quill) {
                const script = document.createElement('script');
                script.src = 'https://cdn.quilljs.com/1.3.6/quill.js';
                script.onload = () => this.initQuill();
                document.head.appendChild(script);
            } else {
                this.initQuill();
            }
        },
        initQuill() {
            const toolbarOptions = [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ];

            this.quill = new Quill(this.$refs.quillEditor, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            image: () => this.imageHandler()
                        }
                    }
                },
                placeholder: 'Escribe tu artículo aquí...'
            });

            // Set initial content
            if (this.content) {
                this.quill.root.innerHTML = this.content;
            }

            // Update Livewire on change
            this.quill.on('text-change', () => {
                this.content = this.quill.root.innerHTML;
            });

            // Watch for external changes
            this.$watch('content', (value) => {
                if (this.quill.root.innerHTML !== value) {
                    this.quill.root.innerHTML = value;
                }
            });
            
            // Apply custom styles to match theme
            this.$refs.quillWrapper.querySelector('.ql-toolbar').style.borderTopLeftRadius = '0.5rem';
            this.$refs.quillWrapper.querySelector('.ql-toolbar').style.borderTopRightRadius = '0.5rem';
            this.$refs.quillWrapper.querySelector('.ql-toolbar').style.borderColor = '#e4e4e7'; // zinc-200
            this.$refs.quillWrapper.querySelector('.ql-container').style.borderBottomLeftRadius = '0.5rem';
            this.$refs.quillWrapper.querySelector('.ql-container').style.borderBottomRightRadius = '0.5rem';
            this.$refs.quillWrapper.querySelector('.ql-container').style.borderColor = '#e4e4e7';
            this.$refs.quillWrapper.querySelector('.ql-editor').style.minHeight = '300px';
            this.$refs.quillWrapper.querySelector('.ql-editor').style.fontSize = '1.125rem'; // text-lg
        },
        imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (/^image\//.test(file.type)) {
                    await this.uploadImage(file);
                } else {
                    console.warn('You could only upload images.');
                }
            };
        },
        async uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);
            
            const range = this.quill.getSelection(true);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                alert('Error: No se encontró el token CSRF. Recarga la página.');
                return;
            }
            
            try {
                const response = await fetch('{{ route('dashboard.upload.image') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                       'X-CSRF-TOKEN': csrfToken,
                       'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.url) {
                        this.quill.insertEmbed(range.index, 'image', data.url);
                        this.quill.setSelection(range.index + 1);
                    } else {
                         console.error('Server returned success but no URL:', data);
                         alert('Error al procesar la imagen.');
                    }
                } else {
                    console.error('Upload failed with status:', response.status);
                    alert('Error al subir imagen (Status ' + response.status + ')');
                }
            } catch (e) {
                console.error('Upload failed exception', e);
                alert('Error de conexión al subir imagen: ' + e.message);
            }
        },
        showPreviewModal: false
    }"
    x-ref="quillWrapper"
    class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden"
>
    <!-- Header with Label and Preview Button -->
    <div class="flex items-center justify-between px-4 py-2 bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
        @if($label)
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $label }}</label>
        @else
            <span></span>
        @endif
        
        <button type="button" @click="showPreviewModal = true" class="text-xs flex items-center gap-1 text-zinc-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            Vista Previa
        </button>
    </div>

    <!-- Editor Container -->
    <div x-ref="quillEditor" class="editor-content"></div>
    
    <!-- Preview Modal -->
    <div 
        x-show="showPreviewModal" 
        x-cloak
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    >
        <div 
            @click.away="showPreviewModal = false"
            class="bg-white dark:bg-zinc-900 rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Vista Previa</h3>
                <button type="button" @click="showPreviewModal = false" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 dark:text-zinc-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-8 bg-white dark:bg-zinc-950">
                <div class="prose prose-lg dark:prose-invert max-w-none ql-editor" x-html="content"></div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Quill Toolbar Styles */
        .ql-toolbar.ql-snow {
            border: none;
            border-bottom: 1px solid #e4e4e7;
            background-color: #ffffff;
            padding: 12px;
        }
        .ql-container.ql-snow {
            border: none;
            font-size: 1.125rem; /* text-lg */
        }
        
        /* Dark Mode */
        .dark .ql-toolbar.ql-snow {
            background-color: #18181b; /* zinc-950 */
            border-bottom-color: #3f3f46; /* zinc-700 */
        }
        .dark .ql-container.ql-snow {
            background-color: #18181b; /* zinc-950 */
            color: #f4f4f5; /* zinc-100 */
        }
        
        /* Icons Contrast */
        .dark .ql-snow .ql-stroke { stroke: #d4d4d8; }
        .dark .ql-snow .ql-fill { fill: #d4d4d8; }
        .dark .ql-snow .ql-picker { color: #d4d4d8; }
        
        /* Active State */
        .ql-snow .ql-active .ql-stroke { stroke: #2563eb !important; } /* blue-600 */
        .ql-snow .ql-active .ql-fill { fill: #2563eb !important; }
        
        /* Dropdowns (Pickers) - Critical for Contrast Fix */
        .dark .ql-snow .ql-picker-options {
            background-color: #18181b;
            border-color: #3f3f46;
        }
        .dark .ql-snow .ql-picker-item {
            color: #d4d4d8;
        }
        .dark .ql-snow .ql-picker-item:hover,
        .dark .ql-snow .ql-picker-item.ql-selected {
            color: #2563eb;
        }
        
        /* Editor Min Height */
        .ql-editor {
            min-height: 350px;
            padding: 1.5rem;
        }
        
        /* Adjust roundness */
        .rounded-lg { border-radius: 0.5rem; }
    </style>
</div>
