@props(['modelValue' => '', 'name' => 'content'])

<div class="tiptap-editor-wrapper">
    <div class="editor-toolbar mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border">
        <div class="flex flex-wrap gap-2">
            <!-- Text Formatting -->
            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="bold"
                title="Gras"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 4v12h6.5c1.5 0 2.5-1 2.5-2.5 0-1-0.5-1.75-1.25-2.25C13.5 10.75 14 9.5 14 8.5c0-1.5-1-2.5-2.5-2.5H5zm2 2h3.5c0.5 0 0.5 0.5 0.5 0.5s0 0.5-0.5 0.5H7V6zm0 4h4c0.5 0 0.5 0.5 0.5 0.5s0 0.5-0.5 0.5H7v-1z"/>
                </svg>
            </button>

            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="italic"
                title="Italique"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 4h6v2l-2 8h2v2H7v-2l2-8H7V4z"/>
                </svg>
            </button>

            <!-- Headings -->
            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="h1"
                title="Titre 1"
            >
                H1
            </button>

            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="h2"
                title="Titre 2"
            >
                H2
            </button>

            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="h3"
                title="Titre 3"
            >
                H3
            </button>

            <!-- Lists -->
            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="insertUnorderedList"
                title="Liste à puces"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 2h12v2H7V2zm0 4h12v2H7V6zm0 4h12v2H7v-2zM3 2v2h2V2H3zm0 4v2h2V6H3zm0 4v2h2v-2H3z"/>
                </svg>
            </button>

            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="insertOrderedList"
                title="Liste numérotée"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 3h2v2H3V3zm0 4h2v2H3V7zm0 4h2v2H3v-2zm14-4v2H7V7h10zm0 4v2H7v-2h10zm0 4v2H7v-2h10z"/>
                </svg>
            </button>

            <!-- Undo/Redo -->
            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="undo"
                title="Annuler"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
            </button>

            <button
                type="button"
                class="format-btn p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                data-editor="{{ $name }}"
                data-command="redo"
                title="Rétablir"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 8.586L8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="editor-content">
        <div
            id="editor-{{ $name }}"
            contenteditable="true"
            class="prose dark:prose-invert max-w-none min-h-[300px] p-4 border rounded-lg bg-white dark:bg-gray-900 focus-within:ring-2 focus-within:ring-blue-500"
        >{!! $modelValue !!}</div>
    </div>

    <!-- Hidden input to store the HTML content -->
    <input type="hidden" name="{{ $name }}" id="input-{{ $name }}" value="{!! htmlspecialchars($modelValue) !!}" />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all TipTap editors
    const editors = document.querySelectorAll('[id^="editor-"]');
    editors.forEach(function(editor) {
        const editorId = editor.id;
        const name = editorId.replace('editor-', '');
        const input = document.getElementById('input-' + name);

        if (editor && input) {
            // Initialize content
            editor.innerHTML = input.value;

            // Add event listeners for content changes
            editor.addEventListener('input', function() {
                input.value = editor.innerHTML;
            });

            editor.addEventListener('keyup', function() {
                input.value = editor.innerHTML;
            });

            editor.addEventListener('paste', function() {
                setTimeout(function() {
                    input.value = editor.innerHTML;
                }, 10);
            });
        }

        // Add event listeners for toolbar buttons for this editor
        const formatBtns = document.querySelectorAll('[data-editor="' + name + '"]');
        formatBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const command = this.getAttribute('data-command');

                // Handle special cases
                if (command.startsWith('h')) {
                    document.execCommand('formatBlock', false, command);
                } else {
                    document.execCommand(command, false, null);
                }

                // Update hidden input
                if (editor && input) {
                    input.value = editor.innerHTML;
                }

                // Focus back to editor
                if (editor) {
                    editor.focus();
                }
            });
        });
    });
});
</script>
