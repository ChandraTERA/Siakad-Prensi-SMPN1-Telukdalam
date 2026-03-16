@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 bg-white/70 backdrop-blur-sm placeholder-gray-400 text-gray-700']) }}>
