<div class="w-5/6 m-auto">
    <div class="flex flex-row items-center justify-between w-full ">
        <div>
            <h1 class="text-2xl font-bold mt-4 mb-6">Bestand auflisten</h1>
        </div>
        <div>
            <button wire:click="importOData" class="hover:underline">Daten abrufen</button>
        </div>
    </div>


    <div class="space-y-4">
        <div class="flex flex-row gap-2">
            <div class="w-2/12">Artikelnummer</div>
            <div class="w-1/12">Lagerort</div>
        </div>
        <div class="flex flex-row gap-2 mb-6">
            <div class="w-2/12">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Suchen…" class="input">
            </div>
            <div class="w-1/12">
                <select wire:model.live="lagerort" class="select">
                    <option value="0">– alle Lagerorte –</option>
                    @foreach ($lagerorte as $ort)
                        <option value="{{ $ort->nr }}">{{ $ort->bezeichnung }} ({{ $ort->nr }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="min-w-full">
            <div class="flex flex-col w-full">
                <div class="flex flex-row space-x-2 w-full font-bold mb-2 border-b border-gray-500">
                    <div class="text-right w-1/12 ">Artikelnr.</div>
                    <div class="text-left w-6/12 ">Bezeichnung</div>
                    <div class="text-left w-3/12 ">Lagerort</div>
                    <div class="text-right w-1/12 ">Lager-Platz</div>
                    <div class="text-right w-1/12 ">Bestand</div>


                </div>
                @forelse($items as $p)
                    <div class="flex flex-row space-x-2 w-full hover:bg-blue-200">
                        <div class="text-right w-1/12">{{ $p->artikelnr ?? '' }}</div>
                        <div class="text-left w-6/12">{{ $p->artikel->bezeichnung ?? '' }}</div>
                        <div class="text-left w-3/12">{{ $p->lagernr }} {{ $p->lagerort->bezeichnung ?? '' }}</div>
                        <div class="text-right w-1/12">..</div>
                        <div class="text-right w-1/12">{{ number_format($p->bestand, 0, ',', '.') }} {{ $p->artikel->einheit }}</div>
                    </div>

                @empty
                    <div class="text-center">Keine Daten</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="mt-4 py-4 mx-32 m-auto border-t border-gray-500">
        {{ $items->links() }}
    </div>
</div>

