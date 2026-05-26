<div class="flex flex-col gap-y-3 pb-2">

    {{-- Header alert box --}}
    <div class="flex items-start gap-x-3 rounded-lg border border-danger-200 bg-danger-50 px-4 py-3 dark:border-danger-800 dark:bg-danger-950/50">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-danger-600 dark:text-danger-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
        </svg>
        <p class="text-sm font-medium text-danger-800 dark:text-danger-200">
            Formulir tidak dapat diproses. Silakan perbaiki kesalahan berikut:
        </p>
    </div>

    {{-- Error list --}}
    <ul class="divide-y divide-gray-100 dark:divide-white/5 rounded-lg border border-gray-200 dark:border-white/10 overflow-hidden">
        @foreach ($errors as $field => $fieldErrors)
            @foreach ($fieldErrors as $error)
                <li class="flex items-start gap-x-3 px-4 py-3 bg-white dark:bg-gray-900">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-danger-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                        <path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0Zm0 1.5a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $error }}</span>
                </li>
            @endforeach
        @endforeach
    </ul>

</div>
