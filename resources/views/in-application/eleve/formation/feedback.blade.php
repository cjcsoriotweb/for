<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
  <div class="flex items-center gap-3 mb-6">
    <div class="flex-shrink-0">
      <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
      </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Donnez votre retour sur cette formation</h3>
  </div>

  <form action="{{ route('eleve.formation.feedback', [$team, $formationWithProgress]) }}" method="POST" class="space-y-6">
    @csrf

    <!-- Note globale -->
    <div>
      <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
        Note globale (sur 5 étoiles) *
      </label>
      <div class="flex items-center gap-2">
        <div class="flex items-center" id="rating-stars">
          @for($i = 1; $i <= 5; $i++)
          <button type="button" class="star-btn text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </button>
          @endfor
        </div>
        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2" id="rating-text">Cliquez pour noter</span>
      </div>
      <input type="hidden" name="rating" id="rating-input" required>
      @error('rating')
      <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
      @enderror
    </div>

    <!-- Commentaire -->
    <div>
      <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Votre commentaire (optionnel)
      </label>
      <textarea
        id="comment"
        name="comment"
        rows="4"
        maxlength="1000"
        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
        placeholder="Partagez votre expérience, vos suggestions d'amélioration, ce que vous avez aimé ou moins aimé..."
      >{{ old('comment') }}</textarea>
      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
        <span id="comment-count">0</span>/1000 caractères
      </p>
      @error('comment')
      <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
      @enderror
    </div>

    <!-- Boutons -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
      <button type="button" onclick="closeFeedbackModal()"
        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        Annuler
      </button>
      <button type="submit"
        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
        Envoyer mon retour
      </button>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const stars = document.querySelectorAll('.star-btn');
  const ratingInput = document.getElementById('rating-input');
  const ratingText = document.getElementById('rating-text');
  const commentTextarea = document.getElementById('comment');
  const commentCount = document.getElementById('comment-count');

  // Gestion des étoiles
  stars.forEach((star, index) => {
    star.addEventListener('click', function() {
      const rating = index + 1;
      ratingInput.value = rating;

      // Mettre à jour l'affichage des étoiles
      stars.forEach((s, i) => {
        if (i <= index) {
          s.classList.remove('text-gray-300');
          s.classList.add('text-yellow-400');
        } else {
          s.classList.remove('text-yellow-400');
          s.classList.add('text-gray-300');
        }
      });

      // Mettre à jour le texte
      const texts = ['Très insatisfait', 'Insatisfait', 'Moyen', 'Satisfait', 'Très satisfait'];
      ratingText.textContent = texts[rating - 1];
    });

    star.addEventListener('mouseenter', function() {
      const rating = index + 1;
      const texts = ['Très insatisfait', 'Insatisfait', 'Moyen', 'Satisfait', 'Très satisfait'];
      ratingText.textContent = texts[rating - 1];
    });
  });

  // Remettre le texte par défaut quand on quitte les étoiles
  document.getElementById('rating-stars').addEventListener('mouseleave', function() {
    if (!ratingInput.value) {
      ratingText.textContent = 'Cliquez pour noter';
    }
  });

  // Compteur de caractères pour le commentaire
  if (commentTextarea && commentCount) {
    commentTextarea.addEventListener('input', function() {
      commentCount.textContent = this.value.length;
    });
  }
});

function closeFeedbackModal() {
  // Fermer la modal ou cacher le formulaire
  const modal = document.querySelector('[data-feedback-modal]');
  if (modal) {
    modal.style.display = 'none';
  }
}
</script>
