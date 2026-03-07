@props(['size' => 'w-8 h-8', 'color' => 'text-green-500'])

<div {{ $attributes->merge(['class' => "inline-flex items-center justify-center $size bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-md"]) }}>
    <svg class="w-1/2 h-1/2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
        <line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="2"/>
        <path d="M8 9a3 3 0 0 1 3-3m-3 9a3 3 0 0 0 3 3" stroke="currentColor" stroke-width="2" fill="none"/>
    </svg>
</div>
