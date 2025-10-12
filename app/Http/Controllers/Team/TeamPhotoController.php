<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamPhotoController extends Controller
{
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'photo' => ['required','image','max:1024','mimes:jpg,jpeg,png,webp,avif'],
        ]);

        // Supprimer l’ancienne si existe
        if ($team->profile_photo_path) {
            $team->deleteProfilePhoto(); // fourni par HasProfilePhoto
        }

        // Stocker la nouvelle
        $path = $request->file('photo')->storePublicly('team-photos', ['disk' => 'public']);

        $team->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        return back()->with('status', 'team-photo-updated');
    }

    public function destroy(Request $request, Team $team)
    {
        if ($team->profile_photo_path) {
            $team->deleteProfilePhoto();
            $team->forceFill(['profile_photo_path' => null])->save();
        }

        return back()->with('status', 'team-photo-removed');
    }
}
