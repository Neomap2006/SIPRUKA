<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 border border-transparent rounded-xl font-semibold text-sm text-white transition duration-150 ease-in-out shadow-lg shadow-indigo-600/15 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:bg-indigo-700']) }}>
    {{ $slot }}
</button>
