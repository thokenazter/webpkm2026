@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
    $alignmentClasses = $align === 'left' ? 'origin-top-left left-0' : 'origin-top-right right-0';
    $widthClass = [
        '48' => 'w-48',
        '60' => 'w-60',
        '72' => 'w-72',
        '80' => 'w-80',
    ][$width] ?? 'w-48';
@endphp

<div x-data="{ open: false }" class="relative">
    <div @click="open = !open" @keydown.escape.window="open = false">
        {{ $trigger }}
    </div>

    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="open = false"
         class="absolute z-50 mt-2 {{ $widthClass }} rounded-md shadow-lg {{ $alignmentClasses }}">
        <div class="rounded-md ring-1 ring-black/5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

