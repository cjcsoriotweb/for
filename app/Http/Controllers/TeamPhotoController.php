<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class TeamPhotoController extends Controller
{
    /**
     * Upload / remplace la photo de l'équipe.
     */
    public function update(Request $request, Team $team)
    {
        // Validation (image, 2 Mo max)
        $validated = $request->validate([
            'photo' => [
                'required',
                File::image()->max(2 * 1024)->types(['jpg', 'jpeg', 'png', 'webp']),
            ],
        ]);

        // Supprime l'ancienne si présente
        if ($team->profile_photo_path) {
            Storage::disk('public')->delete($team->profile_photo_path);
        }

        // Stocke la nouvelle image sur le disque "public"
        // -> dossier "team-profile-photos"
        $path = $request->file('photo')->storePublicly('team-profile-photos', 'public');

        // Sauvegarde le chemin sur le modèle Team
        $team->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        return back()->with('status', 'team-photo-updated');
    }

    /**
     * Supprime la photo de l'équipe.
     */
    public function destroy(Team $team)
    {
        if ($team->profile_photo_path) {
            Storage::disk('public')->delete($team->profile_photo_path);
            $team->forceFill(['profile_photo_path' => null])->save();
        }

        return back()->with('status', 'team-photo-deleted');
    }
}
