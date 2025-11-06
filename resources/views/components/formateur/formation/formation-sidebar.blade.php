<div class="ml-8 w-80">
  <!-- Carte principale avec actions et status -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6 space-y-6">

    <!-- Section Actions -->
    <x-formateur.formation.action-buttons :formation="$formation" />

    <!-- Section Status -->
    <x-formateur.formation.status-badge :formation="$formation" />

  </div>
</div>