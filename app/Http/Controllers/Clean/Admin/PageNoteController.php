<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageNoteController extends Controller
{
    public function overview()
    {
        $summaryRow = PageNote::query()
            ->selectRaw('count(*) as total_notes, sum(case when is_resolved = 0 then 1 else 0 end) as pending_notes')
            ->first();

        $paths = PageNote::query()
            ->select('path')
            ->selectRaw('count(*) as total_notes')
            ->selectRaw('sum(case when is_resolved = 0 then 1 else 0 end) as pending_notes')
            ->selectRaw('max(updated_at) as latest_activity')
            ->groupBy('path')
            ->orderByDesc('pending_notes')
            ->orderByDesc('total_notes')
            ->orderBy('path')
            ->get();

        $recentNotes = PageNote::query()
            ->with('user:id,name,email')
            ->latest()
            ->take(25)
            ->get();

        $summary = [
            'total' => (int) ($summaryRow->total_notes ?? 0),
            'pending' => (int) ($summaryRow->pending_notes ?? 0),
            'paths' => $paths->count(),
        ];

        return view('out-application.superadmin.superadmin-page-notes', compact('summary', 'paths', 'recentNotes'));
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'path' => ['required', 'string', 'max:255'],
        ]);

        $notes = PageNote::query()
            ->with('user:id,name')
            ->where('path', $validated['path'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $notes->map(fn (PageNote $note) => $this->transformNote($note)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'path' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $note = PageNote::create([
            'user_id' => Auth::id(),
            'path' => $validated['path'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'is_resolved' => false,
        ]);

        $note->load('user:id,name');

        return response()->json([
            'data' => $this->transformNote($note),
        ], 201);
    }

    public function update(PageNote $pageNote, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'is_resolved' => ['sometimes', 'boolean'],
        ]);

        $pageNote->fill($validated);
        $pageNote->save();

        $pageNote->load('user:id,name');

        return response()->json([
            'data' => $this->transformNote($pageNote),
        ]);
    }

    public function destroy(PageNote $pageNote): JsonResponse
    {
        $pageNote->delete();

        return response()->json([
            'message' => 'Note supprimée',
        ]);
    }

    private function transformNote(PageNote $note): array
    {
        return [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'is_resolved' => $note->is_resolved,
            'author' => $note->user?->name,
            'created_at' => $note->created_at?->toIso8601String(),
            'updated_at' => $note->updated_at?->toIso8601String(),
        ];
    }
}
