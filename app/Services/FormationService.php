<?php

namespace App\Services;

use App\Models\Formation;
use App\Services\Formation\AdminFormationService;
use App\Services\Formation\ChapterFormationService;
use App\Services\Formation\LessonFormationService;
use App\Services\Formation\StudentFormationService;
use Illuminate\Support\Facades\Auth;

class FormationService
{
    public function __construct(
        private readonly AdminFormationService $adminService,
        private readonly StudentFormationService $studentService,
        private readonly ChapterFormationService $chapterService,
        private readonly LessonFormationService $lessonService
    ) {}

    public function admin(): AdminFormationService
    {
        return $this->adminService;
    }

    public function chapters()
    {
        return $this->chapterService;
    }

    public function lessons()
    {
        return $this->lessonService;
    }

    public function createFormation(array $attributes = []): Formation
    {
        $payload = array_replace([
            'title' => 'Titre par defaut',
            'description' => 'Description par defaut',
            'level' => 'debutant',
            'money_amount' => 0,
            'user_id' => Auth::user()->id,
        ], $attributes);

        return Formation::create($payload);
    }

    public function student(): StudentFormationService
    {
        return $this->studentService;
    }
}
