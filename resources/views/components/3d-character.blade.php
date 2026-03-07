{{-- 3D Character Component --}}
@props(['size' => 'normal', 'interactive' => true])

@php
    $sizeClasses = [
        'small' => 'w-16 h-16',
        'normal' => 'w-30 h-30',
        'large' => 'w-40 h-40'
    ];
    
    $headSizes = [
        'small' => 'w-12 h-12',
        'normal' => 'w-20 h-20', 
        'large' => 'w-32 h-32'
    ];
@endphp

<div class="character-container">
    <div class="character {{ $sizeClasses[$size] ?? $sizeClasses['normal'] }} {{ $interactive ? 'interactive' : '' }}" 
         role="img" 
         aria-label="Mascot character">
        <div class="character-head {{ $headSizes[$size] ?? $headSizes['normal'] }}">
            <div class="character-eyes">
                <div class="eye"></div>
                <div class="eye"></div>
            </div>
            <div class="character-mouth"></div>
            <div class="character-hands">
                <div class="hand"></div>
                <div class="hand"></div>
            </div>
        </div>
    </div>
</div>

@if($interactive)
<style>
.character.interactive {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.character.interactive:hover {
    transform: scale(1.05);
}
</style>
@endif