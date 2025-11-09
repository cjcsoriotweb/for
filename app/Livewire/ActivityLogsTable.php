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

    public $formationId;

    public ?int $editingIpId = null;

    public string $editingIpValue = '';

    protected array $rules = [
        'editingIpValue' => 'required|ip',
    ];

    public function mount($userId, $lessons = null, $formationId = null)
    {
        $this->userId = $userId;
        $this->lessons = $lessons ?? collect();
        $this->formationId = $formationId;
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
        $this->cancelEditingIp();
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
            $this->endDate ?: null,
            $this->formationId
        );

        // Load available lessons for the filter dropdown
        $this->loadAvailableLessons();
    }

    public function startEditingIp(int $activityId): void
    {
        $activity = UserActivityLog::find($activityId);

        if (! $activity) {
            return;
        }

        $this->editingIpId = $activityId;
        $this->editingIpValue = $activity->ip_address ?? '';
    }

    public function saveEditingIp(): void
    {
        if (! $this->editingIpId) {
            return;
        }

        $this->validate();

        $activity = UserActivityLog::find($this->editingIpId);

        if (! $activity) {
            $this->resetEditingIpState();
            return;
        }

        $activity->update([
            'ip_address' => $this->editingIpValue,
        ]);

        $this->loadActivityData();
        $this->resetEditingIpState();
    }

    public function cancelEditingIp(): void
    {
        $this->resetEditingIpState();
    }

    private function resetEditingIpState(): void
    {
        $this->editingIpId = null;
        $this->editingIpValue = '';
    }

    private function loadAvailableLessons()
    {
        $lessons = $this->lessons instanceof \Illuminate\Support\Collection
            ? $this->lessons
            : collect($this->lessons);

        $this->availableLessons = $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
            ];
        })->values();
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
            $this->formationId,
            true
        );

        return view('livewire.activity-logs-table', [
            'activityLogs' => $activityLogs,
        ]);
    }
}
