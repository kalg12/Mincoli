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

            // Load Quill JS and Image Resize Module
            if (!window.Quill) {
                const script = document.createElement('script');
                script.src = 'https://cdn.quilljs.com/1.3.6/quill.js';
                script.onload = () => {
                   this.loadImageResizeModule();
                };
                document.head.appendChild(script);
            } else {
                if (!window.ImageResize) {
                     this.loadImageResizeModule();
                } else {
                     this.initQuill();
                }
            }
        },
        loadImageResizeModule() {
            if (!document.getElementById('quill-image-resize')) {
                const script = document.createElement('script');
                script.id = 'quill-image-resize';
                script.src = 'https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js';
                script.onload = () => {
                    this.initQuill();
                };
                document.head.appendChild(script);
            } else {
                this.initQuill();
            }
        },
        initQuill() {
            // Register ImageResize module
            if (window.ImageResize && !Quill.imports['modules/imageResize']) {
                Quill.register('modules/imageResize', window.ImageResize);
            }

            // EXTEND IMAGE BLOT: Preserve width, height, and style (alignment)
            var BaseImage = Quill.import('formats/image');
            const ATTRIBUTES = ['height', 'width', 'style'];

            class Image extends BaseImage {
                static formats(domNode) {
                    return ATTRIBUTES.reduce(function(formats, attribute) {
                        if (domNode.hasAttribute(attribute)) {
                            formats[attribute] = domNode.getAttribute(attribute);
                        }
                        return formats;
                    }, {});
                }
                format(name, value) {
                    if (ATTRIBUTES.indexOf(name) > -1) {
                        if (value) {
                            this.domNode.setAttribute(name, value);
                        } else {
                            this.domNode.removeAttribute(name);
                        }
                    } else {
                        super.format(name, value);
                    }
                }
            }
            Quill.register(Image, true);

            // USE INLINE STYLES FOR ALIGNMENT AND DIRECTION
            // This ensures text alignment (left, center, right) works on frontend without Quill CSS classes
            var AlignStyle = Quill.import('attributors/style/align');
            Quill.register(AlignStyle, true);

            const toolbarOptions = [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }], // Now uses inline styles
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
                    },
                    imageResize: {
                        displaySize: true
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
            
            // Show loading placeholder? For now just wait.
            const range = this.quill.getSelection(true);
            
            try {
                const response = await fetch('{{ route('dashboard.upload.image') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                       'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // Insert image
                    this.quill.insertEmbed(range.index, 'image', data.url);
                    this.quill.setSelection(range.index + 1);
                } else {
                    alert('Error al subir imagen');
                }
            } catch (e) {
                console.error('Upload failed', e);
                alert('Error al subir imagen');
            }
        }
    }"
    x-ref="quillWrapper"
    class="bg-white dark:bg-zinc-900 rounded-lg"
>
    <!-- Label -->
    @if($label)
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ $label }}</label>
    @endif

    <!-- Editor Container -->
    <div x-ref="quillEditor"></div>
    
    <style>
        /* Dark mode overrides for Quill */
        .dark .ql-toolbar {
            background-color: #18181b; /* zinc-950 */
            border-color: #3f3f46 !important; /* zinc-700 */
            color: #e4e4e7;
        }
        .dark .ql-container {
            background-color: #18181b;
            border-color: #3f3f46 !important;
            color: #e4e4e7;
        }
        .dark .ql-stroke {
            stroke: #e4e4e7 !important;
        }
        .dark .ql-fill {
            fill: #e4e4e7 !important;
        }
        .dark .ql-picker {
            color: #e4e4e7 !important;
        }
        /* Fix excessive spacing */
        .ql-editor p {
            margin-bottom: 0.5em;
        }
    </style>
</div>
