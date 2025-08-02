@props(['href', 'label'])

<a href="{{ $href }}"
    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring ring-blue-300 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition ease-in-out duration-150">
    {{ $label }}
</a>
