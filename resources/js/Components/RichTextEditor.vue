<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import CharacterCount from '@tiptap/extension-character-count';
import Underline from '@tiptap/extension-underline';
import { computed, watch, onBeforeUnmount } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Mulai menulis artikel di sini...',
    },
});

const emit = defineEmits(['update:modelValue']);

/**
 * Resize an image File/Blob to max width, returning a base64 data URL.
 * Quality is reduced progressively until under maxSizeKB.
 */
const resizeImage = (file, maxWidth = 1200, maxSizeKB = 2048) => {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new window.Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                // Try progressively lower quality until under maxSizeKB
                let quality = 0.85;
                let dataUrl = canvas.toDataURL('image/jpeg', quality);
                while (dataUrl.length > maxSizeKB * 1024 * 1.37 && quality > 0.3) {
                    quality -= 0.1;
                    dataUrl = canvas.toDataURL('image/jpeg', quality);
                }

                resolve(dataUrl);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
};

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: { levels: [1, 2, 3] },
        }),
        Image.configure({
            inline: false,
            allowBase64: true,
            HTMLAttributes: {
                class: 'rounded-lg max-w-full mx-auto shadow-sm',
            },
        }),
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'text-blue-600 underline hover:text-blue-800',
            },
        }),
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
        CharacterCount,
        Underline,
    ],
    content: props.modelValue,
    editorProps: {
        attributes: {
            class: 'prose prose-lg max-w-none focus:outline-none min-h-[400px] px-6 py-4',
        },
        handlePaste(view, event) {
            const items = event.clipboardData?.items;
            if (!items) return false;

            for (const item of items) {
                if (item.type.startsWith('image/')) {
                    event.preventDefault();
                    const file = item.getAsFile();
                    if (file) {
                        resizeImage(file).then((dataUrl) => {
                            editor.value?.chain().focus().setImage({ src: dataUrl }).run();
                        });
                    }
                    return true;
                }
            }
            return false;
        },
        handleDrop(view, event) {
            const files = event.dataTransfer?.files;
            if (!files || files.length === 0) return false;

            for (const file of files) {
                if (file.type.startsWith('image/')) {
                    event.preventDefault();
                    resizeImage(file).then((dataUrl) => {
                        editor.value?.chain().focus().setImage({ src: dataUrl }).run();
                    });
                    return true;
                }
            }
            return false;
        },
    },
    onUpdate: ({ editor: ed }) => {
        emit('update:modelValue', ed.getHTML());
    },
});

// Sync external changes back into the editor
watch(() => props.modelValue, (val) => {
    if (editor.value && editor.value.getHTML() !== val) {
        editor.value.commands.setContent(val, false);
    }
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const wordCount = computed(() => {
    if (!editor.value) return 0;
    const text = editor.value.getText();
    return text.trim() ? text.trim().split(/\s+/).length : 0;
});

const readingTime = computed(() => {
    return Math.max(1, Math.ceil(wordCount.value / 200));
});

const charCount = computed(() => {
    return editor.value?.storage.characterCount.characters() || 0;
});

// Toolbar: insert image via file picker
const insertImage = () => {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = async (e) => {
        const file = e.target.files?.[0];
        if (file) {
            const dataUrl = await resizeImage(file);
            editor.value?.chain().focus().setImage({ src: dataUrl }).run();
        }
    };
    input.click();
};

// Toolbar: insert link
const insertLink = () => {
    const previousUrl = editor.value?.getAttributes('link').href;
    const url = window.prompt('Masukkan URL:', previousUrl || 'https://');

    if (url === null) return; // cancelled
    if (url === '') {
        editor.value?.chain().focus().extendMarkRange('link').unsetLink().run();
        return;
    }
    editor.value?.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

// Toolbar button definitions for clean template
const toolbarGroups = computed(() => [
    {
        name: 'format',
        buttons: [
            { icon: 'bold', title: 'Bold (Ctrl+B)', action: () => editor.value?.chain().focus().toggleBold().run(), isActive: () => editor.value?.isActive('bold') },
            { icon: 'italic', title: 'Italic (Ctrl+I)', action: () => editor.value?.chain().focus().toggleItalic().run(), isActive: () => editor.value?.isActive('italic') },
            { icon: 'underline', title: 'Underline (Ctrl+U)', action: () => editor.value?.chain().focus().toggleUnderline().run(), isActive: () => editor.value?.isActive('underline') },
            { icon: 'strikethrough', title: 'Strikethrough', action: () => editor.value?.chain().focus().toggleStrike().run(), isActive: () => editor.value?.isActive('strike') },
        ],
    },
    {
        name: 'heading',
        buttons: [
            { label: 'H1', title: 'Heading 1', action: () => editor.value?.chain().focus().toggleHeading({ level: 1 }).run(), isActive: () => editor.value?.isActive('heading', { level: 1 }) },
            { label: 'H2', title: 'Heading 2', action: () => editor.value?.chain().focus().toggleHeading({ level: 2 }).run(), isActive: () => editor.value?.isActive('heading', { level: 2 }) },
            { label: 'H3', title: 'Heading 3', action: () => editor.value?.chain().focus().toggleHeading({ level: 3 }).run(), isActive: () => editor.value?.isActive('heading', { level: 3 }) },
        ],
    },
    {
        name: 'list',
        buttons: [
            { icon: 'list-ul', title: 'Bullet List', action: () => editor.value?.chain().focus().toggleBulletList().run(), isActive: () => editor.value?.isActive('bulletList') },
            { icon: 'list-ol', title: 'Numbered List', action: () => editor.value?.chain().focus().toggleOrderedList().run(), isActive: () => editor.value?.isActive('orderedList') },
            { icon: 'blockquote', title: 'Blockquote', action: () => editor.value?.chain().focus().toggleBlockquote().run(), isActive: () => editor.value?.isActive('blockquote') },
        ],
    },
    {
        name: 'insert',
        buttons: [
            { icon: 'link', title: 'Insert Link', action: insertLink, isActive: () => editor.value?.isActive('link') },
            { icon: 'image', title: 'Insert Image', action: insertImage },
            { icon: 'code', title: 'Code Block', action: () => editor.value?.chain().focus().toggleCodeBlock().run(), isActive: () => editor.value?.isActive('codeBlock') },
            { icon: 'hr', title: 'Horizontal Rule', action: () => editor.value?.chain().focus().setHorizontalRule().run() },
        ],
    },
    {
        name: 'history',
        buttons: [
            { icon: 'undo', title: 'Undo (Ctrl+Z)', action: () => editor.value?.chain().focus().undo().run() },
            { icon: 'redo', title: 'Redo (Ctrl+Shift+Z)', action: () => editor.value?.chain().focus().redo().run() },
        ],
    },
]);
</script>

<template>
    <div class="rich-editor border border-gray-300 rounded-xl overflow-hidden shadow-sm bg-white">
        <!-- Toolbar -->
        <div v-if="editor" class="editor-toolbar bg-gradient-to-b from-gray-50 to-gray-100 border-b border-gray-200 px-3 py-2 flex flex-wrap items-center gap-1">
            <template v-for="(group, gi) in toolbarGroups" :key="gi">
                <div class="flex items-center gap-0.5">
                    <button
                        v-for="(btn, bi) in group.buttons"
                        :key="bi"
                        type="button"
                        @click="btn.action"
                        :title="btn.title"
                        class="toolbar-btn"
                        :class="{ 'is-active': btn.isActive?.() }"
                    >
                        <!-- SVG Icons -->
                        <template v-if="btn.icon === 'bold'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M15.6 10.79c.97-.67 1.65-1.77 1.65-2.79 0-2.26-1.75-4-4-4H7v14h7.04c2.09 0 3.71-1.7 3.71-3.79 0-1.52-.86-2.82-2.15-3.42zM10 6.5h3c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-3v-3zm3.5 9H10v-3h3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'italic'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'underline'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'strikethrough'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 19h4v-3h-4v3zM5 4v3h5v3h4V7h5V4H5zM3 14h18v-2H3v2z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'list-ul'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5S3.17 7.5 4 7.5 5.5 6.83 5.5 6 4.83 4.5 4 4.5zm0 12c-.83 0-1.5.68-1.5 1.5s.68 1.5 1.5 1.5 1.5-.68 1.5-1.5-.67-1.5-1.5-1.5zM7 19h14v-2H7v2zm0-6h14v-2H7v2zm0-8v2h14V5H7z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'list-ol'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'blockquote'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'link'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1 0 1.71-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'image'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'code'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'hr'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="11" width="18" height="2" rx="1"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'undo'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"/></svg>
                        </template>
                        <template v-else-if="btn.icon === 'redo'">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.4 10.6C16.55 8.99 14.15 8 11.5 8c-4.65 0-8.58 3.03-9.96 7.22L3.9 16c1.05-3.19 4.05-5.5 7.6-5.5 1.95 0 3.73.72 5.12 1.88L13 16h9V7l-3.6 3.6z"/></svg>
                        </template>
                        <template v-else-if="btn.label">
                            <span class="text-xs font-bold">{{ btn.label }}</span>
                        </template>
                    </button>
                </div>
                <div v-if="gi < toolbarGroups.length - 1" class="w-px h-6 bg-gray-300 mx-1"></div>
            </template>
        </div>

        <!-- Editor Content -->
        <EditorContent :editor="editor" class="editor-content" />

        <!-- Footer Stats -->
        <div v-if="editor" class="editor-footer bg-gray-50 border-t border-gray-200 px-4 py-2 flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    {{ wordCount }} kata
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    {{ charCount }} karakter
                </span>
            </div>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                ~{{ readingTime }} menit baca
            </span>
        </div>
    </div>
</template>

<style scoped>
.toolbar-btn {
    @apply p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-white hover:shadow-sm transition-all duration-150 cursor-pointer;
}

.toolbar-btn.is-active {
    @apply bg-white text-indigo-600 shadow-sm ring-1 ring-indigo-200;
}

.editor-content :deep(.tiptap) {
    min-height: 400px;
    outline: none;
}

.editor-content :deep(.tiptap p.is-editor-empty:first-child::before) {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
}

.editor-content :deep(.tiptap img) {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem auto;
    display: block;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.editor-content :deep(.tiptap pre) {
    background: #1e293b;
    color: #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 0.875rem;
    overflow-x: auto;
}

.editor-content :deep(.tiptap blockquote) {
    border-left: 4px solid #6366f1;
    padding-left: 1rem;
    margin-left: 0;
    color: #4b5563;
    font-style: italic;
}

.editor-content :deep(.tiptap h1) {
    font-size: 2rem;
    font-weight: 800;
    margin: 1.5rem 0 0.75rem;
    color: #111827;
}

.editor-content :deep(.tiptap h2) {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 1.25rem 0 0.5rem;
    color: #1f2937;
}

.editor-content :deep(.tiptap h3) {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 1rem 0 0.5rem;
    color: #374151;
}

.editor-content :deep(.tiptap ul),
.editor-content :deep(.tiptap ol) {
    padding-left: 1.5rem;
    margin: 0.5rem 0;
}

.editor-content :deep(.tiptap li) {
    margin: 0.25rem 0;
}

.editor-content :deep(.tiptap a) {
    color: #4f46e5;
    text-decoration: underline;
}

.editor-content :deep(.tiptap hr) {
    border: none;
    border-top: 2px solid #e5e7eb;
    margin: 1.5rem 0;
}
</style>
