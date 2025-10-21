<div wire:poll.3s="getQuiz"
  class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
  <div class="layout-container flex h-full grow flex-col">
    <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5">
      <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
        <div
          class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center flex-1">
          <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-4">Votre quiz va commencer</h1>
          <div class="relative flex items-center justify-center w-48 h-48">
            <div class="absolute inset-0 bg-primary/20 rounded-full animate-ping"></div>
            <div
              class="relative flex items-center justify-center w-36 h-36 bg-primary rounded-full text-white text-6xl font-black countdown-number">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <style>
    :root {
      --radio-dot-svg: url('data:image/svg+xml,%3csvg viewBox=%270 0 16 16%27 fill=%27%231173d4%27 xmlns=%27http://www.w3.org/2000/svg%27%3e%3ccircle cx=%278%27 cy=%278%27 r=%274%27/%3e%3c/svg%3e');
    }

    .dark:root {
      --radio-dot-svg: url('data:image/svg+xml,%3csvg viewBox=%270 0 16 16%27 fill=%27%231173d4%27 xmlns=%27http://www.w3.org/2000/svg%27%3e%3ccircle cx=%278%27 cy=%278%27 r=%274%27/%3e%3c/svg%3e');
    }

    .countdown-number::before {
      content: '3';
      animation: countdown-text 3s steps(1, end) forwards;
    }

    @keyframes countdown-text {
      0% {
        content: '3';
      }

      33% {
        content: '2';
      }

      66% {
        content: '1';
      }

      100% {
        content: 'GO!';
      }
    }
  </style>
</div>