@props(['size' => 'w-6 h-6', 'variant' => 'default'])

@php
$variants = [
    'default' => 'from-green-500 to-emerald-600',
    'blue' => 'from-blue-500 to-blue-600', 
    'purple' => 'from-purple-500 to-purple-600',
    'orange' => 'from-orange-500 to-orange-600',
    'indigo' => 'from-indigo-500 to-indigo-600',
];
$gradient = $variants[$variant] ?? $variants['default'];
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center justify-center $size bg-gradient-to-r $gradient rounded-lg shadow-sm"]) }}>
    <svg class="w-3/5 h-3/5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
        <line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="2"/>
        <path d="M8 9a3 3 0 0 1 3-3m-3 9a3 3 0 0 0 3 3" stroke="currentColor" stroke-width="2" fill="none"/>
    </svg>
</div>
