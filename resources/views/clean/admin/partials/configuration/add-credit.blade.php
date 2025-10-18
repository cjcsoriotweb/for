<div
    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6"
>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1 flex justify-between">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __("Crédit d'application") }}
                    </h3>
                    <p>
                        {{ __("Crédit actuel ") . $team->money. ' EUR'}}
                    </p>
                </div>

                <div class="px-4 sm:px-0"></div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <section
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6"
                >
                    <form
                        method="POST"
                        action="{{
                            route(
                                'application.admin.configuration.credit',
                                $team
                            )
                        }}"
                        class="max-w-sm mx-auto"
                    >
                        @csrf
                        <input
                            type="hidden"
                            name="team_id"
                            value="{{ $team->id }}"
                        />
                        <div class="mb-5">
                            <label
                                for="montant"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Montant</label
                            >
                            <input
                                type="number"
                                name="montant"
                                id="montant"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Montant"
                                required
                            />
                        </div>
                        <div class="mb-5">
                            <label
                                for="raison"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Raison</label
                            >
                            <input
                                type="text"
                                name="raison"
                                id="raison"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                            />
                        </div>
                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            {{ __("Ajouter du crédit") }}
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
