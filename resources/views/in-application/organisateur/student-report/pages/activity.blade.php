<x-organisateur-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('in-application.organisateur.student-report.header-and-tabs', ['activeTab' => 'activity'])

    <div class="mt-6">
      @include('in-application.organisateur.student-report.activity')
    </div>
  </div>
</x-organisateur-layout>

