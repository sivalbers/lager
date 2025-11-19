@props(['w', 'artikel', 'lagerort'])
<div class="flex flex-col  hover:bg-blue-200 mb-2 border-b border-gray-300">
    <div class="flex flex-row space-x-2 items-center w-full  ">

        <div class="text-left {{ $w[0] }}">{{ $artikel->artikelnr }}</div>

        <div class="text-left {{ $w[2] }}">
            {{ $artikel->abladestelle->name }}
        </div>

        @if ($lagerort == 0)
            <div class="text-left {{ $w[3] }}">{{ $artikel->lagernr }}
                {{ $artikel->lagerort->bezeichnung ?? '' }}
            </div>
        @endif
        <div class="text-left {{ $w[4] }}">
            {{ $artikel->lagerplatz }}
        </div>
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
    <div class="flex flex-col {{ $w[1]}} pl-2">
        {{ $artikel->artikel->bezeichnung ?? '' }}

    </div>
</div>
