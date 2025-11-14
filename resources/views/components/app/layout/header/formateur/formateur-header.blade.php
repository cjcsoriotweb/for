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
      class="w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-lg outline-1 outline-gray-900/5 transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-black/40 backdrop-blur-sm open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">

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

        

      </div>

    </el-popover>
  </div>
@endif
