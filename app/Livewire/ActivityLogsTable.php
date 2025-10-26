<?php

namespace App\Livewire;

use App\Models\UserActivityLog;
use App\Services\UserActivityService;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogsTable extends Component
{
    use WithPagination;

    public $userId;
    public $search = '';
    public $lessonFilter = '';
    public $startDate = '';
    public $endDate = '';
    public $perPage = 20;

    public $activitySummary;
    public $availableLessons = [];
    public $lessons = [];

    public function mount($userId, $lessons = null)
    {
        $this->userId = $userId;
        $this->lessons = $lessons ?? collect();
        $this->loadActivityData();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadActivityData();
    }

    public function updatedLessonFilter()
    {
        $this->resetPage();
        $this->loadActivityData();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
        $this->loadActivityData();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
        $this->loadActivityData();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->loadActivityData();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->lessonFilter = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
        $this->loadActivityData();
    }

    public function refreshData()
    {
        $this->loadActivityData();
    }

    private function loadActivityData()
    {
        $activityService = app(UserActivityService::class);

        $this->activitySummary = $activityService->getUserActivitySummary(
            $this->userId,
            $this->startDate ?: null,
            $this->endDate ?: null
        );

        // Load available lessons for the filter dropdown
        $this->loadAvailableLessons();
    }

    private function loadAvailableLessons()
    {
        // This will be populated from the parent component
        // For now, we'll leave it empty and populate it from the parent
        $this->availableLessons = [];
    }

    public function render()
    {
        $activityService = app(UserActivityService::class);

        $activityLogs = $activityService->getUserActivityLogs(
            $this->userId,
            $this->perPage,
            $this->startDate ?: null,
            $this->endDate ?: null,
            $this->search ?: null,
            $this->lessonFilter ?: null,
            true
        );

        return view('livewire.activity-logs-table', [
            'activityLogs' => $activityLogs,
        ]);
    }
}
