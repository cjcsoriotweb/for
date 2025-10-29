<div
    id="page-notes-widget"
    class="fixed bottom-6 left-6 m-5 z-50 flex flex-col items-end space-y-3 ">
    <button
        type="button"
        id="page-notes-toggle"
        class="flex items-center space-x-2 rounded-full bg-blue-600 px-10 py-2 text-sm font-semibold text-white shadow-lg transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <span class="material-symbols-outlined text-lg">note_alt</span>
        <span>Notes page developpeur</span>
        <span
            id="page-notes-counter"
            class="ml-2 hidden rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold text-white"></span>
    </button>

    <div
        id="page-notes-panel"
        class="hidden w-[22rem] max-w-sm rounded-xl border border-slate-200 bg-white p-4 text-slate-800 shadow-2xl dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
        <div class="flex items-start justify-between space-x-4">
            <div>
                <h2 class="text-base font-semibold">Notes pour cette page</h2>
                <p
                    id="page-notes-path"
                    class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400"></p>
            </div>
            <button
                type="button"
                id="page-notes-close"
                class="rounded-md p-1 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <div
            id="page-notes-status"
            class="mt-3 hidden rounded-md border px-3 py-2 text-xs"></div>

        <form
            id="page-notes-form"
            class="mt-4 space-y-3">
            <div>
                <label
                    for="page-notes-title"
                    class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Titre (optionnel)</label>
                <input
                    id="page-notes-title"
                    name="title"
                    type="text"
                    class="mt-1 w-full rounded-md border border-slate-200 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" />
            </div>

            <div>
                <label
                    for="page-notes-content"
                    class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Votre note *</label>
                <textarea
                    id="page-notes-content"
                    name="content"
                    rows="4"
                    required
                    class="mt-1 w-full rounded-md border border-slate-200 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
            </div>

            <div class="flex items-center justify-between space-x-2">
                <button
                    type="button"
                    id="page-notes-cancel"
                    class="hidden rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                    Annuler
                </button>
                <button
                    type="submit"
                    id="page-notes-submit"
                    class="ml-auto inline-flex items-center space-x-1 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <span class="material-symbols-outlined text-base">add</span>
                    <span>Ajouter</span>
                </button>
            </div>
        </form>

        <div
            id="page-notes-list"
            class="mt-5 space-y-3 text-sm"></div>
    </div>
</div>

@push('scripts')
<script>
    (() => {
        if (window.__pageNotesWidgetInitialized) {
            return;
        }

        window.__pageNotesWidgetInitialized = true;

        const toggleButton = document.getElementById('page-notes-toggle');
        const panel = document.getElementById('page-notes-panel');
        const closeButton = document.getElementById('page-notes-close');
        const statusBox = document.getElementById('page-notes-status');
        const notesList = document.getElementById('page-notes-list');
        const titleInput = document.getElementById('page-notes-title');
        const contentInput = document.getElementById('page-notes-content');
        const form = document.getElementById('page-notes-form');
        const cancelButton = document.getElementById('page-notes-cancel');
        const submitButton = document.getElementById('page-notes-submit');
        const pathDisplay = document.getElementById('page-notes-path');
        const counterBadge = document.getElementById('page-notes-counter');

        const openTriggers = Array.from(document.querySelectorAll('[data-open-page-notes]'));

        if (!toggleButton || !panel || !closeButton || !form || !notesList || !statusBox) {
            console.warn('[PageNotes] Élément(s) manquant(s), arrêt de l’initialisation.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        toggleButton.setAttribute('aria-controls', 'page-notes-panel');
        toggleButton.setAttribute('aria-expanded', 'false');

        if (!csrfToken) {
            console.warn('[PageNotes] CSRF token introuvable. Le widget est désactivé.');
            toggleButton.disabled = true;
            toggleButton.classList.add('cursor-not-allowed', 'opacity-60');
            return;
        }

        const state = {
            notes: [],
            isOpen: false,
            editingNoteId: null,
        };

        let normalizedPath = window.location.pathname || '/';
        if (normalizedPath.length > 1 && normalizedPath.endsWith('/')) {
            normalizedPath = normalizedPath.slice(0, -1);
        }

        const pageKey = normalizedPath || '/';

        pathDisplay.textContent = pageKey;

        const endpoints = {
            list: (path) => `/superadmin/page-notes?path=${encodeURIComponent(path)}`,
            store: `/superadmin/page-notes`,
            update: (id) => `/superadmin/page-notes/${id}`,
            remove: (id) => `/superadmin/page-notes/${id}`,
        };

        const defaultHeaders = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        function setStatus(message, type = 'info') {
            if (!message) {
                statusBox.classList.add('hidden');
                statusBox.textContent = '';
                return;
            }

            statusBox.classList.remove('hidden');
            statusBox.textContent = message;

            statusBox.classList.remove(
                'border-blue-200',
                'bg-blue-50',
                'text-blue-700',
                'border-green-200',
                'bg-green-50',
                'text-green-700',
                'border-red-200',
                'bg-red-50',
                'text-red-700'
            );

            if (type === 'success') {
                statusBox.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
            } else if (type === 'error') {
                statusBox.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
            } else {
                statusBox.classList.add('border-blue-200', 'bg-blue-50', 'text-blue-700');
            }
        }

        function escapeHtml(value) {
            return value
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatDate(value) {
            if (!value) {
                return '';
            }

            const date = new Date(value);

            if (Number.isNaN(date.getTime())) {
                return '';
            }

            return new Intl.DateTimeFormat('fr-FR', {
                dateStyle: 'short',
                timeStyle: 'short',
            }).format(date);
        }

        async function request(method, url, body = null) {
            const headers = {
                ...defaultHeaders,
            };

            if (method !== 'GET' && method !== 'HEAD') {
                headers['Content-Type'] = 'application/json';
                headers['X-CSRF-TOKEN'] = csrfToken;
            }

            const response = await fetch(url, {
                method,
                headers,
                credentials: 'same-origin',
                body: body ? JSON.stringify(body) : null,
            });

            if (!response.ok) {
                let errorMessage = 'Une erreur est survenue.';

                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message ?? errorMessage;
                } catch (_) {
                    // ignore JSON parsing errors
                }

                throw new Error(errorMessage);
            }

            if (response.status === 204) {
                return null;
            }

            try {
                return await response.json();
            } catch (_) {
                return null;
            }
        }

        function renderNotes() {
            notesList.innerHTML = '';

            if (!state.notes.length) {
                const empty = document.createElement('p');
                empty.className = 'rounded-md border border-dashed border-slate-200 px-3 py-4 text-center text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400';
                empty.textContent = 'Aucune note pour cette page pour le moment.';
                notesList.appendChild(empty);
                updateCounter();
                return;
            }

            state.notes.forEach((note) => {
                const card = document.createElement('div');
                card.className = `rounded-lg border px-3 py-3 shadow-sm transition ${
                    note.is_resolved
                        ? 'border-emerald-200 bg-emerald-50/60 dark:border-emerald-700/40 dark:bg-emerald-900/20'
                        : 'border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800'
                }`;

                const title = note.title ? `<p class="text-sm font-semibold">${escapeHtml(note.title)}</p>` : '';
                const content = `<p class="mt-1 whitespace-pre-wrap text-sm ${
                    note.is_resolved ? 'text-slate-500 line-through' : 'text-slate-700 dark:text-slate-100'
                }">${escapeHtml(note.content)}</p>`;
                const metaParts = [];

                if (note.author) {
                    metaParts.push(`Par ${escapeHtml(note.author)}`);
                }

                const formattedDate = formatDate(note.updated_at || note.created_at);
                if (formattedDate) {
                    metaParts.push(formattedDate);
                }

                const meta = metaParts.length
                    ? `<p class="mt-2 text-xs text-slate-400 dark:text-slate-400">${metaParts.join(' • ')}</p>`
                    : '';

                card.innerHTML = `
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            ${title}
                            ${content}
                            ${meta}
                        </div>
                        <span class="mt-0.5 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ${
                            note.is_resolved
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-800/60 dark:text-emerald-200'
                                : 'bg-amber-100 text-amber-700 dark:bg-amber-800/60 dark:text-amber-200'
                        }">
                            ${note.is_resolved ? 'Résolu' : 'À traiter'}
                        </span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button data-action="toggle" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
                            ${note.is_resolved ? 'Réouvrir' : 'Marquer comme résolu'}
                        </button>
                        <button data-action="edit" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
                            Modifier
                        </button>
                        <button data-action="delete" class="rounded-md border border-red-200 px-2 py-1 text-xs font-medium text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-400 dark:border-red-600 dark:text-red-300 dark:hover:bg-red-900/40">
                            Supprimer
                        </button>
                    </div>
                `;

                const toggleButton = card.querySelector('[data-action="toggle"]');
                const editButton = card.querySelector('[data-action="edit"]');
                const deleteButton = card.querySelector('[data-action="delete"]');

                toggleButton.addEventListener('click', async () => {
                    try {
                        setStatus('Mise à jour de la note…');
                        const response = await request('PATCH', endpoints.update(note.id), {
                            is_resolved: !note.is_resolved,
                        });

                        const updated = response?.data;
                        if (updated) {
                            state.notes = state.notes.map((item) => (item.id === updated.id ? updated : item));
                            renderNotes();
                            setStatus(updated.is_resolved ? 'Note marquée comme résolue.' : 'Note réouverte.', 'success');
                        } else {
                            throw new Error();
                        }
                    } catch (error) {
                        setStatus(error.message, 'error');
                    }
                });

                editButton.addEventListener('click', () => {
                    state.editingNoteId = note.id;
                    titleInput.value = note.title ?? '';
                    contentInput.value = note.content;
                    submitButton.querySelector('span:last-child').textContent = 'Mettre à jour';
                    cancelButton.classList.remove('hidden');
                    setStatus('Modification de la note en cours.', 'info');
                    contentInput.focus();
                });

                deleteButton.addEventListener('click', async () => {
                    const confirmation = window.confirm('Supprimer définitivement cette note ?');
                    if (!confirmation) {
                        return;
                    }

                    try {
                        setStatus('Suppression de la note…');
                        await request('DELETE', endpoints.remove(note.id));
                        state.notes = state.notes.filter((item) => item.id !== note.id);
                        renderNotes();
                        setStatus('Note supprimée.', 'success');
                    } catch (error) {
                        setStatus(error.message, 'error');
                    }
                });

                notesList.appendChild(card);
            });

            updateCounter();
        }

        function updateCounter() {
            const total = state.notes.length;
            const pending = state.notes.filter((note) => !note.is_resolved).length;

            if (total === 0) {
                counterBadge.classList.add('hidden');
                counterBadge.textContent = '';
                toggleButton.classList.remove('animate-pulse');
                updateTriggerBadges(total, pending);
                return;
            }

            counterBadge.classList.remove('hidden');
            counterBadge.textContent = pending > 0 ? `${pending}/${total}` : `${total}`;
            toggleButton.classList.toggle('animate-pulse', pending > 0);
            updateTriggerBadges(total, pending);
        }

        function updateTriggerBadges(total, pending) {
            openTriggers.forEach((trigger) => {
                const badge = trigger.querySelector('[data-page-notes-count]');

                if (!badge) {
                    trigger.toggleAttribute('data-page-notes-has-items', total > 0);
                    trigger.setAttribute('data-page-notes-total', String(total));
                    trigger.setAttribute('data-page-notes-pending', String(pending));
                    return;
                }

                if (total === 0) {
                    badge.classList.add('hidden');
                    badge.textContent = '';
                    return;
                }

                badge.classList.remove('hidden');
                badge.textContent = pending > 0 ? `${pending}/${total}` : `${total}`;
            });
        }

    async function loadNotes() {
            try {
                setStatus('Chargement des notes…');
                const data = await request('GET', endpoints.list(pageKey));
                state.notes = Array.isArray(data?.data) ? data.data : [];
                renderNotes();
                setStatus('');
            } catch (error) {
                setStatus(error.message, 'error');
            }
        }

        function resetForm() {
            form.reset();
            state.editingNoteId = null;
            submitButton.disabled = false;
            submitButton.querySelector('span:last-child').textContent = 'Ajouter';
            cancelButton.classList.add('hidden');
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const content = contentInput.value.trim();
            const title = titleInput.value.trim();

            if (!content) {
                setStatus('La note ne peut pas être vide.', 'error');
                contentInput.focus();
                return;
            }

            submitButton.disabled = true;
            submitButton.classList.add('opacity-60');

            try {
                if (state.editingNoteId) {
                    const response = await request('PATCH', endpoints.update(state.editingNoteId), {
                        title: title || null,
                        content,
                    });

                    const updated = response?.data;
                    if (updated) {
                        state.notes = state.notes.map((item) => (item.id === updated.id ? updated : item));
                        renderNotes();
                        setStatus('Note mise à jour.', 'success');
                    }
                } else {
                    const response = await request('POST', endpoints.store, {
                        path: pageKey,
                        title: title || null,
                        content,
                    });

                    const created = response?.data;
                    if (created) {
                        state.notes = [created, ...state.notes];
                        renderNotes();
                        setStatus('Note créée.', 'success');
                    }
                }

                resetForm();
            } catch (error) {
                setStatus(error.message, 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-60');
            }
        });

        cancelButton.addEventListener('click', () => {
            resetForm();
            setStatus('Modification annulée.', 'info');
        });

        function togglePanel(force) {
            const shouldOpen = typeof force === 'boolean' ? force : !state.isOpen;
            state.isOpen = shouldOpen;

            if (state.isOpen) {
                panel.classList.remove('hidden');
                toggleButton.setAttribute('aria-expanded', 'true');
            } else {
                panel.classList.add('hidden');
                toggleButton.setAttribute('aria-expanded', 'false');
            }
        }

        toggleButton.addEventListener('click', () => {
            togglePanel();
        });

        closeButton.addEventListener('click', () => {
            togglePanel(false);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && state.isOpen) {
                togglePanel(false);
            }
        });

        openTriggers.forEach((trigger) => {
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                togglePanel(true);
                setTimeout(() => {
                    contentInput.focus();
                }, 100);
            });
        });

        // Initial load
        loadNotes();
    })();
</script>
@endpush
