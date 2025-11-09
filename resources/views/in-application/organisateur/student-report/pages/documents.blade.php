<x-organisateur-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('in-application.organisateur.student-report.header-and-tabs', ['activeTab' => 'documents'])

    <div class="mt-6">
      @include('in-application.organisateur.student-report.pdf')
    </div>
  </div>
</x-organisateur-layout>

