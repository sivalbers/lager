@props(['w', 'artikel', 'lagerort'])
<div class="flex flex-row sm:space-x-2 w-full hover:bg-blue-200">
    <div class="text-left {{ $w[0] }}">
        {{ $artikel->artikelnr }}
    </div>
    <div class="text-left {{ $w[1] }} sm:ml-2 text-gray-800">
            {{ $artikel->artikel->bezeichnung ?? '' }}
    </div>
    <div class="text-left {{ $w[2] }} sm:ml-2 text-gray-800">
            {{ $artikel->abladestelle->name ?? '' }}
    </div>

    @if ($lagerort == 0)
        <div class="text-left {{ $w[3] }}">
            {{ $artikel->lagernr }} {{ $artikel->lagerort->bezeichnung ?? '' }}
        </div>
    @endif
    <div class="text-left {{ $w[4] }} ">{{ $artikel->lagerplatz }}</div>
    <div class="text-right {{ $w[5] }}">
        {{ number_format($artikel->bestand, 0, ',', '.') }}
        {{ $artikel->artikel->einheit }}
    </div>
    <div class="text-center {{ $w[6] }}">
        <button wire:click="edit({{ $artikel->id }})" class="hover:underline">
            <x-heroicon-o-information-circle class="h-6" />
        </button>
    </div>
</div>
