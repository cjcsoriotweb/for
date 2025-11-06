@if(Auth::user()->formateur)
  <div class="relative">
    <button popovertarget="formateur-menu-product" class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
      Formateur
      <!-- Chevron -->
      <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 flex-none text-gray-400">
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z"/>
      </svg>
    </button>

    <el-popover id="formateur-menu-product" anchor="bottom" popover
      class="w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-lg outline-1 outline-gray-900/5 transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-transparent open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">

      <div class="p-4">
        <!-- Vos formations -->
        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
          <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
            <!-- Icône tableau de bord / carnet -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                 class="size-6 text-gray-600 group-hover:text-indigo-600">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 4.5h8.5A2.75 2.75 0 0 1 18 7.25v10A2.75 2.75 0 0 1 15.25 20H6.75A2.25 2.25 0 0 1 4.5 17.75V6.75A2.25 2.25 0 0 1 6.75 4.5Z"/>
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 8.75h5.25M9 12h5.25M9 15.25h3.25"/>
            </svg>
          </div>
          <div class="flex-auto">
            <a href="{{ route('formateur.home') }}" class="block font-semibold text-gray-900">
              Vos formations
              <span class="absolute inset-0"></span>
            </a>
            <p class="mt-1 text-gray-600">Consultez et gérez vos sessions de formation.</p>
          </div>
        </div>

        <!-- Nouvelle formation -->
        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
          <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
            <!-- Icône ajout -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                 class="size-6 text-gray-600 group-hover:text-indigo-600">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v12M6 12h12"/>
              <rect x="3.75" y="3.75" width="16.5" height="16.5" rx="2.25" ry="2.25"/>
            </svg>
          </div>
          <div class="flex-auto">
            <a href="{{ route('formateur.formations.create') }}" class="block font-semibold text-gray-900">
              Nouvelle formation
              <span class="absolute inset-0"></span>
            </a>
            <p class="mt-1 text-gray-600">Créez un programme et planifiez vos sessions.</p>
          </div>
        </div>

        <!-- Importer formation -->
        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
          <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
            <!-- Icône import (nuage vers le haut) -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                 class="size-6 text-gray-600 group-hover:text-indigo-600">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 18.75h9A3.75 3.75 0 0 0 20.25 15 4.5 4.5 0 0 0 16 10.7 5.25 5.25 0 0 0 6.52 9.6 3.75 3.75 0 0 0 3.75 13.25c0 3.03 2.22 5.5 3.75 5.5Z"/>
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 15.75V9.75m0 0 3 3m-3-3-3 3"/>
            </svg>
          </div>
          <div class="flex-auto">
            <a href="{{ route('formateur.import') }}" class="block font-semibold text-gray-900">
              Importer formation
              <span class="absolute inset-0"></span>
            </a>
            <p class="mt-1 text-gray-600">Importez un catalogue ou des supports existants.</p>
          </div>
        </div>

      </div>

      <!-- Pied de popover -->
      <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
        <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-100">
          <!-- Icône lecture / démo -->
          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 flex-none text-gray-400">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z"/>
          </svg>
          Documentation
        </a>
        <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-100">
          <!-- Icône contact -->
          <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 flex-none text-gray-400">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M2 3.5A1.5 1.5 0 0 1 3.5 2h13A1.5 1.5 0 0 1 18 3.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 2 16.5v-13Zm2.06 2.56a.75.75 0 0 0 0 1.06l3.75 3.75a2.5 2.5 0 0 0 3.54 0l3.75-3.75a.75.75 0 1 0-1.06-1.06L9.79 9.31a1 1 0 0 1-1.41 0L4.94 6.06a.75.75 0 0 0-1.06 0Z"/>
          </svg>
          Nous contacter
        </a>
      </div>

    </el-popover>
  </div>
@endif
