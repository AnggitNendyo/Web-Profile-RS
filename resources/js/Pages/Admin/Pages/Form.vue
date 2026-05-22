<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

// Tiptap Editor
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

const props = defineProps({
    page: Object,
});

const isEditing = !!props.page;

const form = useForm({
    title: props.page?.title || '',
    content: props.page?.content || '',
    is_active: props.page ? !!props.page.is_active : true,
});

const editor = useEditor({
    extensions: [StarterKit],
    content: form.content,
    editorProps: {
        attributes: {
            class: 'prose max-w-none focus:outline-none min-h-[300px] px-4 py-3',
        },
    },
    onUpdate: ({ editor }) => {
        form.content = editor.getHTML();
    },
});

const submit = () => {
    if (isEditing) {
        form.put(route('admin.pages.update', props.page.id));
    } else {
        form.post(route('admin.pages.store'));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Halaman' : 'Tambah Halaman'" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isEditing ? 'Edit Halaman' : 'Tambah Halaman Baru' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <div>
                                <InputLabel for="title" value="Judul Halaman" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    placeholder="Contoh: Hak dan Kewajiban Pasien"
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel value="Konten Halaman" />
                                <div class="mt-1 border border-gray-300 rounded-md overflow-hidden" v-if="editor">
                                    <div class="bg-gray-50 border-b border-gray-300 px-3 py-2 flex flex-wrap gap-2">
                                        <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-gray-200': editor.isActive('bold') }" class="px-2 py-1 rounded text-sm hover:bg-gray-200">Bold</button>
                                        <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-gray-200': editor.isActive('italic') }" class="px-2 py-1 rounded text-sm hover:bg-gray-200">Italic</button>
                                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'bg-gray-200': editor.isActive('heading', { level: 2 }) }" class="px-2 py-1 rounded text-sm hover:bg-gray-200">H2</button>
                                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'bg-gray-200': editor.isActive('heading', { level: 3 }) }" class="px-2 py-1 rounded text-sm hover:bg-gray-200">H3</button>
                                        <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-gray-200': editor.isActive('bulletList') }" class="px-2 py-1 rounded text-sm hover:bg-gray-200">Bullet List</button>
                                        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'bg-gray-200': editor.isActive('orderedList') }" class="px-2 py-1 rounded text-sm hover:bg-gray-200">Number List</button>
                                    </div>
                                    <EditorContent :editor="editor" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.content" />
                            </div>

                            <div class="block">
                                <label class="flex items-center">
                                    <Checkbox name="is_active" v-model:checked="form.is_active" />
                                    <span class="ms-2 text-sm text-gray-600">Publikasikan Halaman (Aktif)</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('admin.pages.index')" class="text-sm text-gray-600 hover:text-gray-900 underline mr-4">
                                    Batal
                                </Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Simpan Halaman
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
