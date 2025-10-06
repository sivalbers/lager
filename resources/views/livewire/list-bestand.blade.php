<div class="w-[95%] sm:w-5/6 m-auto ">
    <div class="flex flex-row items-center justify-between w-full ">
        <div>
            <h1 class="text-2xl font-bold mt-4 mb-6">Bestand auflisten</h1>
        </div>
        <div>
            <button wire:click="importOData" class="hover:underline">Daten abrufen</button>
        </div>
    </div>


    <div class="space-y-4">
        <!-- Kopfzeile -->
        <div class="flex font-bold ">
            <div class="flex-[2] pr-2">Artikelnr.</div>
            <div class="flex-[10]">Lagerort</div>
        </div>

        <!-- Eingabefelder -->
        <div class="flex mb-6 w-full">
            <div class="flex-[2] pr-2">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Suchen…"
                    class="input w-full">
            </div>
            <div class="flex-[10]">
                <select wire:model.live="lagerort" class="select w-full">
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
                    @if ($lagerort == 0)
                        <div class="text-left w-3/12 ">Lagerort</div>
                    @endif
                    <div class="text-right w-1/12 ">Lager-Platz</div>
                    <div class="text-right w-1/12 ">Bestand</div>


                </div>
                @forelse($items as $p)
                    <div class="flex flex-row space-x-2 w-full hover:bg-blue-200">
                        <div class="text-right w-1/12">{{ $p->artikelnr ?? '' }}</div>
                        <div class="text-left w-6/12">{{ $p->artikel->bezeichnung ?? '' }}</div>

                        @if ($lagerort == 0)
                            <div class="text-left w-3/12">{{ $p->lagernr }} {{ $p->lagerort->bezeichnung ?? '' }}</div>
                        @endif
                        <div class="text-right w-1/12">..</div>
                        <div class="text-right w-1/12">{{ number_format($p->bestand, 0, ',', '.') }}
                            {{ $p->artikel->einheit }}</div>
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
