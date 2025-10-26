<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
  <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Rapport PDF</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Visualisez ou exportez une version imprimable du rapport de l’élève.
        </p>
      </div>
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('organisateur.formations.students.report.pdf', [$team, $formation, $student]) }}"
          target="_blank"
          class="inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
          </svg>
          Ouvrir dans un nouvel onglet
        </a>

        <a href="{{ route('organisateur.formations.students.report.pdf.download', [$team, $formation, $student]) }}"
          class="inline-flex items-center px-3 py-2 border border-green-300 dark:border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          Télécharger
        </a>
      </div>
    </div>
  </div>
  <div class="px-4 py-5 sm:px-6">
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
      Si l’aperçu ne s’affiche pas, téléchargez le fichier ou ouvrez-le dans un nouvel onglet.
    </p>
    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-900">
      <iframe src="{{ route('organisateur.formations.students.report.pdf', [$team, $formation, $student]) }}"
        class="w-full h-96 border-0" title="Rapport PDF - {{ $student->name }}">
        <p class="p-4 text-gray-600 dark:text-gray-400">
          Votre navigateur ne supporte pas les iframes.
          <a href="{{ route('organisateur.formations.students.report.pdf', [$team, $formation, $student]) }}"
            target="_blank" class="text-blue-600 hover:text-blue-800 underline">
            Cliquez ici pour voir le PDF dans un nouvel onglet
          </a>.
        </p>
      </iframe>
    </div>
  </div>
</div>
