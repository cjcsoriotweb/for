<template>
  <div class="tiptap-editor">
    <div class="editor-toolbar mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border">
      <div class="flex flex-wrap gap-2">
        <!-- Text Formatting -->
        <button
          @click="editor?.chain().focus().toggleBold().run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('bold') }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Gras"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5 4v12h6.5c1.5 0 2.5-1 2.5-2.5 0-1-0.5-1.75-1.25-2.25C13.5 10.75 14 9.5 14 8.5c0-1.5-1-2.5-2.5-2.5H5zm2 2h3.5c0.5 0 0.5 0.5 0.5 0.5s0 0.5-0.5 0.5H7V6zm0 4h4c0.5 0 0.5 0.5 0.5 0.5s0 0.5-0.5 0.5H7v-1z"/>
          </svg>
        </button>

        <button
          @click="editor?.chain().focus().toggleItalic().run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('italic') }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Italique"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M7 4h6v2l-2 8h2v2H7v-2l2-8H7V4z"/>
          </svg>
        </button>

        <!-- Headings -->
        <button
          @click="editor?.chain().focus().toggleHeading({ level: 1 }).run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('heading', { level: 1 }) }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Titre 1"
        >
          H1
        </button>

        <button
          @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('heading', { level: 2 }) }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Titre 2"
        >
          H2
        </button>

        <button
          @click="editor?.chain().focus().toggleHeading({ level: 3 }).run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('heading', { level: 3 }) }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Titre 3"
        >
          H3
        </button>

        <!-- Lists -->
        <button
          @click="editor?.chain().focus().toggleBulletList().run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('bulletList') }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Liste à puces"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M7 2h12v2H7V2zm0 4h12v2H7V6zm0 4h12v2H7v-2zM3 2v2h2V2H3zm0 4v2h2V6H3zm0 4v2h2v-2H3z"/>
          </svg>
        </button>

        <button
          @click="editor?.chain().focus().toggleOrderedList().run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive('orderedList') }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Liste numérotée"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 3h2v2H3V3zm0 4h2v2H3V7zm0 4h2v2H3v-2zm14-4v2H7V7h10zm0 4v2H7v-2h10zm0 4v2H7v-2h10z"/>
          </svg>
        </button>

        <!-- Text Alignment -->
        <button
          @click="editor?.chain().focus().setTextAlign('left').run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive({ textAlign: 'left' }) }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Aligner à gauche"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4h14v2H3V4zm0 4h10v2H3V8zm0 4h14v2H3v-2zm0 4h8v2H3v-2z"/>
          </svg>
        </button>

        <button
          @click="editor?.chain().focus().setTextAlign('center').run()"
          :class="{ 'bg-blue-500 text-white': editor?.isActive({ textAlign: 'center' }) }"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          title="Centrer"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4h14v2H3V4zm2 4h10v2H5V8zm2 4h6v2H7v-2zm2 4h10v2H9v-2z"/>
          </svg>
        </button>

        <!-- Undo/Redo -->
        <button
          @click="editor?.chain().focus().undo().run()"
          :disabled="!editor?.can().undo()"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50"
          title="Annuler"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
          </svg>
        </button>

        <button
          @click="editor?.chain().focus().redo().run()"
          :disabled="!editor?.can().redo()"
          class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50"
          title="Rétablir"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 8.586L8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586z"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="editor-content">
      <editor-content :editor="editor" class="prose dark:prose-invert max-w-none min-h-[300px] p-4 border rounded-lg bg-white dark:bg-gray-900 focus-within:ring-2 focus-within:ring-blue-500" />
    </div>

    <!-- Hidden input to store the HTML content -->
    <input type="hidden" :value="content" :name="name" />
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  name: {
    type: String,
    default: 'content'
  },
  placeholder: {
    type: String,
    default: 'Commencez à écrire...'
  }
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
  ],
  editorProps: {
    attributes: {
      class: 'prose dark:prose-invert max-w-none focus:outline-none',
    },
  },
  onUpdate: ({ editor }) => {
    const html = editor.getHTML()
    emit('update:modelValue', html)
  },
  parseOptions: {
    preserveWhitespace: false,
  },
})

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (editor.value && editor.value.getHTML() !== newValue) {
    editor.value.commands.setContent(newValue)
  }
})

// Expose editor instance for parent components
defineExpose({
  editor,
  getHTML: () => editor.value?.getHTML() || '',
  setContent: (content) => editor.value?.commands.setContent(content),
})
</script>

<style scoped>
.tiptap-editor {
  width: 100%;
}

.editor-toolbar button {
  transition: all 0.2s ease;
}

.editor-toolbar button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.ProseMirror {
  outline: none;
  min-height: 300px;
}

.ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}

.ProseMirror:focus {
  outline: none;
}
</style>
