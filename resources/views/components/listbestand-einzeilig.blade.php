@props(['w', 'artikel', 'lagerort'])
<div class="flex flex-row sm:space-x-2 w-full hover:bg-blue-200">
    <div class="text-left {{ $w[0] }}">
        {{ $artikel->artikelnr }}
    </div>
    <div class="text-left {{ $w[1] }} sm:ml-2 text-gray-800">
            {{ $artikel->artikel->bezeichnung ?? '' }}
    </div>

    @if ($lagerort == 0)
        <div class="text-left {{ $w[2] }}">
            {{ $artikel->lagernr }} {{ $artikel->lagerort->bezeichnung ?? '' }}
        </div>
    @endif
    <div class="text-left {{ $w[3] }}">..</div>
    <div class="text-right {{ $w[4] }}">
        {{ number_format($artikel->bestand, 0, ',', '.') }}
        {{ $artikel->artikel->einheit }}
    </div>
    <div class="text-center {{ $w[5] }}">
        <button wire:click="edit({{ $artikel->id }})" class="hover:underline">
            <x-fluentui-info-12-o class="h-6" />
        </button>
    </div>
</div>
