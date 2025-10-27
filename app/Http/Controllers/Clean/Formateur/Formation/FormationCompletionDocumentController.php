<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formateur\Formation\StoreFormationCompletionDocumentRequest;
use App\Models\Formation;
use App\Models\FormationCompletionDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class FormationCompletionDocumentController extends Controller
{
    public function store(StoreFormationCompletionDocumentRequest $request, Formation $formation): RedirectResponse
    {
        $uploadedFile = $request->file('file');

        $path = $uploadedFile->store('formation-completion-documents', 'public');

        $formation->completionDocuments()->create([
            'title' => $request->string('title')->trim()->toString(),
            'file_path' => $path,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize(),
        ]);

        return back()->with('success', 'Document ajoute avec succes.');
    }

    public function destroy(Formation $formation, FormationCompletionDocument $document): RedirectResponse
    {
        if ($document->formation_id !== $formation->id) {
            abort(404);
        }

        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return back()->with('success', 'Document supprime avec succes.');
    }
}
