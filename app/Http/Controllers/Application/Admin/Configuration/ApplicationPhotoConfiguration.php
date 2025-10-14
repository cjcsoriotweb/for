<?php

namespace App\Http\Controllers\Application\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;


class ApplicationAdminPhotoConfiguration extends Controller
{
    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'photo' => [
                'required',
                File::image()->max(2 * 1024) // 2 MB
            ],
        ]);

        // Chemin où stocker
        $path = "teams/{$team->id}/photo.".$data['photo']->extension();

        // Stocker sur le disque 'public'
        $data['photo']->storeAs("teams/{$team->id}", basename($path), 'public');

        // Sauvegarder le chemin (ajoute une colonne 'photo_path' si besoin)
        $team->profile_photo_path = $path;
        $team->save();

        return back()->with('ok', 'Photo mise à jour.');
    }

    public function destroy(Team $team)
    {
        if ($team->photo_path) {
            Storage::disk('public')->delete($team->photo_path);
            $team->photo_path = null;
            $team->save();
        }
        return back()->with('ok', 'Photo supprimée.');
    }

}
