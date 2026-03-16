@props(['active', 'href' => '#'])

@php
    $isActive = is_string($active) ? request()->routeIs($active) : $active ?? false;

    // Determine color scheme based on current route
    if (request()->routeIs('admin.*')) {
        $activeClasses = 'bg-blue-700 text-white';
        $inactiveClasses = 'text-blue-100 hover:bg-blue-700 hover:text-white';
    } elseif (request()->routeIs('guru.*')) {
        $activeClasses = 'bg-green-700 text-white';
        $inactiveClasses = 'text-green-100 hover:bg-green-700 hover:text-white';
    } elseif (request()->routeIs('siswa.*')) {
        $activeClasses = 'bg-green-700 text-white';
        $inactiveClasses = 'text-green-100 hover:bg-green-700 hover:text-white';
    } else {
        $activeClasses = 'bg-gray-700 text-white';
        $inactiveClasses = 'text-gray-100 hover:bg-gray-700 hover:text-white';
    }

    $classes = $isActive ? $activeClasses : $inactiveClasses;
    $classes .= ' flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200';
@endphp

<a href="{{ $href }}" class="{{ $classes }}">
    {{ $slot }}
</a>
