<?php

namespace App\Http\Controllers\Clean\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        abort(404);
    }

    public function store(Request $request): JsonResponse
    {
        abort(404);
    }

    public function update(PageNote $pageNote, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'is_resolved' => ['sometimes', 'boolean'],
            'is_hidden' => ['sometimes', 'boolean'],
        ]);

        $pageNote->fill($validated);
        $pageNote->save();

        $pageNote->load('user:id,name');

        return response()->json([
            'data' => $this->transformNote($pageNote),
        ]);
    }

    public function toggleHidden(PageNote $pageNote): JsonResponse
    {
        abort(404);
    }

    public function storeReply(PageNote $pageNote, Request $request): JsonResponse
    {
        abort(404);
    }

    public function destroyReply(PageNoteReply $pageNoteReply): JsonResponse
    {
        abort(404);
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
            'team_id' => $note->team_id,
            'path' => $note->path,
            'route_name' => $note->route_name,
            'full_url' => $note->full_url,
            'model_type' => $note->model_type,
            'model_id' => $note->model_id,
            'context_hash' => $note->context_hash,
            'title' => $note->title,
            'content' => $note->content,
            'is_resolved' => $note->is_resolved,
            'is_hidden' => $note->is_hidden,
            'author' => $note->user?->name,
            'created_at' => $note->created_at?->toIso8601String(),
            'updated_at' => $note->updated_at?->toIso8601String(),
            'replies' => $note->replies->map(fn ($reply) => $this->transformReply($reply)),
            'replies_count' => $note->replies->count(),
        ];
    }

    private function transformReply(PageNoteReply $reply): array
    {
        return [
            'id' => $reply->id,
            'content' => $reply->content,
            'author' => $reply->user?->name,
            'created_at' => $reply->created_at?->toIso8601String(),
            'updated_at' => $reply->updated_at?->toIso8601String(),
        ];
    }
}
