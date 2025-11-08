<div class="fixed bottom-4 right-4 z-50">
    @if($isOpen)
        <div class="flex h-[28rem] w-96 flex-col bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden transition-all duration-300 ease-in-out">
            <header
                class="flex flex-wrap justify-between gap-3 p-3 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-background-dark/50 backdrop-blur-sm cursor-pointer"
                wire:click="toggleChat">
                <div class="flex items-center gap-3">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-8"
                        data-alt="CourseAI application logo"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuATKqMbAuowkTKHSsRIXf2VHBW3HhbXZ9PIf0QpIHOML1KZxhbi0yXTrxE5TSP-tHCGHrRFUURqxBIByGrJDoABZNEo8H5NcsTLi5v1wPrpWgvkYyr_eOYjb1m2mutjV8u3VHGj7Lfgl8af5dgn_WbyUYAkouCJdeqdSDw-Pbp7c0OudgYtqXdXfDGg7vpK_dP1yXVsjucCJdXEx_F0pvHpHFg3awPV3CgpaLKZPgMOane1g26y36dklQ95joJFRHZLKmvHvw1fJOE");'>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-[#111318] dark:text-white tracking-light text-lg font-bold leading-tight">AI
                            Assistant: Calculus I</p>
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <p class="text-[#616f89] dark:text-gray-400 text-xs font-normal leading-normal">Online</p>
                        </div>
                    </div>
                </div>
                <button class="flex items-center justify-center w-6 h-6 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </header>

            <div class="flex-1 overflow-y-auto p-4 space-y-6">
                <div class="flex items-end gap-3 p-0">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
                        data-alt="AI Assistant avatar"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB_vRN6vAiZJnISlUGk-0muIbz-vLGA1EKfeULNcBQnIlfVxM6rk2ZwBrFSI1wfzPoPPkR-LUO8ivgUNSiXjh5NP4X4-l8ro2sz8vfJ2y94cnBa26LUxt3sOJtZD5LhuJbf5hnab4Y0d3T7K5kad9bhKdCA6VB93b2JCVhlonbfJ9_W02OfWCbeCkWM2gdHRn60hdi1MLUVWHDv8jNbqxywKYBoY3RBaq2rixwoUhQtNdxZx_QRlMenDuwfwz__pUav9B8pxyhOsPY");'>
                    </div>
                    <div class="flex flex-1 flex-col gap-2 items-start">
                        <p class="text-[#616f89] dark:text-gray-400 text-[13px] font-normal leading-normal max-w-sm">AI
                            Assistant</p>
                        <p
                            class="text-base font-normal leading-normal flex max-w-2xl rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-[#111318] dark:text-white shadow-sm">
                            Hello! I'm your AI assistant for Calculus I. How can I help you today?</p>
                        <button
                            class="flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <span class="material-symbols-outlined text-base">thumb_down</span>
                            <span>Cette reponse ne me convient pas</span>
                        </button>
                    </div>
                </div>
                <div class="flex items-end gap-3 p-0 justify-end">
                    <div class="flex flex-1 flex-col gap-1 items-end">
                        <p
                            class="text-[#616f89] dark:text-gray-400 text-[13px] font-normal leading-normal max-w-sm text-right">
                            You</p>
                        <p
                            class="text-base font-normal leading-normal flex max-w-2xl rounded-lg px-4 py-3 bg-primary text-white shadow-sm">
                            Can you explain the fundamental theorem of calculus?</p>
                    </div>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
                        data-alt="User avatar"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB3mY840YngOLkgErE_2vArOiIw9WKiB2-KH6t26OPHwCSVHWkK3Ezw8ORu8X3qzsm6fYGb2IDWAmnE2rxFh6xLycW7VSO2sqdB5li6qnpE04XpMSISBcMEj4G4IjjT6HCF6ELXJJpnzYW2pTsDqVNKXYMT1btHLWDQzJSDAVaS16vUM7fIcEzEVZD5dVa-QcAtXb--WFcmh9Xh-6_qexnAsU8zKC6dZPSfSXY65_CgXPlv9s43ikqqRDHOKTTxByX4GkyAQDJLRLE");'>
                    </div>
                </div>
                <div class="flex items-end gap-3 p-0">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
                        data-alt="AI Assistant avatar"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDzg1KYLMfKVV63ws7AAwwCGwQFtjyvGLF7TmSdQU0xegxqdGS9w1j-TIHAqaDUoEBrqLueHjjUO0WSj9madcoS0An5WRsMlZxCkA6U1jDkOdB3GrynQwdiQBl8k5yhfoUwPvNM504E4F4v7EOWqxfyP6nDbiWEDK_gwEiupbw5hdobJrHlkqIDW1JZljC7I33WdaFIgjwxFqjOVLhcbU13zbHRdrYuZOqljqalF5jmUjwzhlzQGw_iXaAJ8hyfl5XQTYtOkFNJ3Og");'>
                    </div>
                    <div class="flex flex-1 flex-col gap-2 items-start">
                        <p class="text-[#616f89] dark:text-gray-400 text-[13px] font-normal leading-normal max-w-sm">AI
                            Assistant</p>
                        <div
                            class="text-base font-normal leading-relaxed flex max-w-2xl rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-[#111318] dark:text-white shadow-sm">
                            <div class="prose prose-sm dark:prose-invert max-w-none">
                                <p>Of course! The Fundamental Theorem of Calculus is a theorem that links the concept of
                                    differentiating a function with the concept of integrating a function.</p>
                                <p>It has two parts:</p>
                                <ol>
                                    <li><strong>First Part:</strong> If <em>f</em> is a continuous function on [a, b], then
                                        the function <em>g</em> defined by g(x) = ∫ₐˣ f(t) dt is continuous on [a, b] and
                                        differentiable on (a, b), and g'(x) = f(x).</li>
                                    <li><strong>Second Part:</strong> If <em>f</em> is a continuous function on [a, b], then
                                        ∫ₐᵇ f(x) dx = F(b) - F(a), where F is any antiderivative of f (that is, F' = f).
                                    </li>
                                </ol>
                                <p>In simpler terms, it provides a way to calculate definite integrals without using Riemann
                                    sums. Let me know if you would like a practical example!</p>
                            </div>
                        </div>
                        <button
                            class="flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <span class="material-symbols-outlined text-base">thumb_down</span>
                            <span>Cette reponse ne me convient pas</span>
                        </button>
                    </div>
                </div>
                <div class="flex items-end gap-3 p-0">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0"
                        data-alt="AI Assistant avatar"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDzg1KYLMfKVV63ws7AAwwCGwQFtjyvGLF7TmSdQU0xegxqdGS9w1j-TIHAqaDUoEBrqLueHjjUO0WSj9madcoS0An5WRsMlZxCkA6U1jDkOdB3GrynQwdiQBl8k5yhfoUwPvNM504E4F4v7EOWqxfyP6nDbiWEDK_gwEiupbw5hdobJrHlkqIDW1JZljC7I33WdaFIgjwxFqjOVLhcbU13zbHRdrYuZOqljqalF5jmUjwzhlzQGw_iXaAJ8hyfl5XQTYtOkFNJ3Og");'>
                    </div>
                    <div class="flex items-center gap-1 rounded-lg px-4 py-3 bg-white dark:bg-gray-800 shadow-sm">
                        <span
                            class="h-1.5 w-1.5 animate-[bounce_1s_infinite] rounded-full bg-gray-400 [animation-delay:-0.3s]"></span>
                        <span
                            class="h-1.5 w-1.5 animate-[bounce_1s_infinite] rounded-full bg-gray-400 [animation-delay:-0.15s]"></span>
                        <span class="h-1.5 w-1.5 animate-[bounce_1s_infinite] rounded-full bg-gray-400"></span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white dark:bg-background-dark/50 border-t border-gray-200 dark:border-gray-800">
                <div class="relative flex items-center">
                    <textarea
                        class="form-textarea w-full resize-none rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 py-3 pl-4 pr-24 text-sm focus:border-primary focus:ring-primary dark:text-white transition-colors"
                        placeholder="Ask a question about Calculus I..." rows="1"></textarea>
                    <div class="absolute right-3 flex items-center gap-2">
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-full text-[#616f89] dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-xl">mic</span>
                        </button>
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-full text-[#616f89] dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-xl">attach_file</span>
                        </button>
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors">
                            <span class="material-symbols-outlined text-xl">send</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <button
            class="flex items-center gap-3 p-3 bg-primary text-white rounded-full shadow-lg hover:bg-primary/90 transition-all duration-200 cursor-pointer animate-pulse"
            wire:click="toggleChat">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-8"
                data-alt="CourseAI application logo"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuATKqMbAuowkTKHSsRIXf2VHBW3HhbXZ9PIf0QpIHOML1KZxhbi0yXTrxE5TSP-tHCGHrRFUURqxBIByGrJDoABZNEo8H5NcsTLi5v1wPrpWgvkYyr_eOYjb1m2mutjV8u3VHGj7Lfgl8af5dgn_WbyUYAkouCJdeqdSDw-Pbp7c0OudgYtqXdXfDGg7vpK_dP1yXVsjucCJdXEx_F0pvHpHFg3awPV3CgpaLKZPgMOane1g26y36dklQ95joJFRHZLKmvHvw1fJOE");'>
            </div>
            <span class="font-medium">AI Assistant</span>
            <span class="material-symbols-outlined text-lg">chat</span>
        </button>
    @endif
</div>
