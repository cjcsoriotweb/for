<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;

Route::resource('formations', FormationsController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'formations.index',
                'show'  => 'formations.show',
            ]);