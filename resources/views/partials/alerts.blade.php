<div x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }}, showError: {{ session('error') ? 'true' : 'false' }}, showValidation: {{ $errors->any() ? 'true' : 'false' }} }" class="fixed top-20 right-4 z-[9999] space-y-3 w-full max-w-sm">
    @if (session('success'))
        <div
            x-show="showSuccess"
            x-transition
            x-init="setTimeout(() => showSuccess = false, 3500)"
            class="rounded-2xl border border-green-200 bg-white shadow-xl overflow-hidden"
        >
            <div class="h-1 bg-green-500"></div>
            <div class="p-4 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold">✓</div>
                <div class="flex-1">
                    <p class="font-semibold text-green-700">Success</p>
                    <p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p>
                </div>
                <button @click="showSuccess = false" class="text-gray-400 hover:text-gray-600">×</button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div
            x-show="showError"
            x-transition
            x-init="setTimeout(() => showError = false, 4500)"
            class="rounded-2xl border border-red-200 bg-white shadow-xl overflow-hidden"
        >
            <div class="h-1 bg-red-500"></div>
            <div class="p-4 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold">!</div>
                <div class="flex-1">
                    <p class="font-semibold text-red-700">Error</p>
                    <p class="text-sm text-gray-600 mt-1">{{ session('error') }}</p>
                </div>
                <button @click="showError = false" class="text-gray-400 hover:text-gray-600">×</button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div
            x-show="showValidation"
            x-transition
            class="rounded-2xl border border-amber-200 bg-white shadow-xl overflow-hidden"
        >
            <div class="h-1 bg-amber-500"></div>
            <div class="p-4 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">!</div>
                <div class="flex-1">
                    <p class="font-semibold text-amber-700">Please check the form</p>
                    <ul class="list-disc pl-5 mt-2 text-sm text-gray-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="showValidation = false" class="text-gray-400 hover:text-gray-600">×</button>
            </div>
        </div>
    @endif
</div>
