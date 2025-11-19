<div class="w-[95%] sm:w-5/6 m-auto "

    x-data
    x-init="
        const getBp = () => {
            const w = window.innerWidth;
            if (w >= 1536) return '2xl';
            if (w >= 1280) return 'xl';
            if (w >= 1024) return 'lg';
            if (w >= 768) return 'md';
            if (w >= 640) return '';
            return '';
        };
        $wire.set('screenWidth', getBp());
    ">

    <div class="flex flex-row items-center justify-between w-full ">
        <div>
            <h1 class="text-2xl font-bold mt-4 mb-6">Bestand auflisten</h1>
        </div>
        <div>
            <!-- button wire:click="importOData" class="hover:underline">Daten abrufen</button -->
        </div>
    </div>

    <div class="space-y-4">
        <!-- Kopfzeile -->
        <div class="flex font-bold ">
            <div class="flex-[2] basis-0 pr-2">Artikelnr.</div>
            <div class="flex-[5] basis-0 pr-2">Abladestelle</div>
            <div class="flex-[5] basis-0">Lagerort</div>
        </div>

        <!-- Eingabefelder -->
        <div class="flex mb-6 w-full">
            <div class="flex-[2] base-0 pr-2">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Suchen…"
                    class="input w-full">
            </div>
            <div class="flex-[5] base-0 pr-2">
                <select wire:model.change="abladestelle" class="select w-full">
                    <option value="0">– alle Abladestellen –</option>
                    @foreach ($abladestellen as $stelle)
                        <option value="{{ $stelle->id }}">{{ $stelle->name }} (Id: {{ $stelle->id }} )</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-[5] base-0 ">
                <select wire:model.change="lagerort" class="select w-full">
                    <option value="0">– alle Lagerorte –</option>
                    @foreach ($lagerorte as $ort)
                        <option value="{{ $ort->id }}">{{ $ort->bezeichnung }} (Id: {{ $ort->id }}, Abladestelle: {{ $ort->abladestelle->name }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        @php
            $zweiZeilig = $screenWidth === '';
/*
            if (!$zweiZeilig) { // also einzeilig
                $w = [ "w-2/12 bg-red-200",
                        "w-7/12 bg-blue-200",
                        "w-5/12 bg-orange-200",
                        ($lagerort > 0) ? "null bg-red-600" : "w-2/12 bg-red-600",
                        "w-1/12 bg-pink-200",
                        "w-1/12 bg-lime-200",
                        "w-1/12 bg-orange-200" ];

            }
            else { // Artikel in zwei Zeilen
                $w = [
                    "w-4/12 bg-red-200",
                    "w-full bg-blue-200",
                    "w-5/12 bg-orange-200",
                    ($lagerort > 0) ? "null bg-red-600" : "w-4/12 bg-red-600",
                    "w-4/12 bg-pink-200",
                    "w-4/12 bg-lime-200",
                    "w-1/12 bg-orange-200" ];
            }
*/
            if (!$zweiZeilig) { // also einzeilig
                $w = [ "w-2/12 ",
                        "w-7/12 ",
                        "w-5/12 ",
                        ($lagerort > 0) ? "null " : "w-2/12 ",
                        "w-1/12 ",
                        "w-1/12 ",
                        "w-1/12 " ];

            }
            else { // Artikel in zwei Zeilen
                $w = [
                    "w-4/12 ",
                    "w-full ",
                    "w-5/12 ",
                    ($lagerort > 0) ? "null " : "w-4/12 ",
                    "w-4/12 ",
                    "w-4/12 ",
                    "w-1/12 " ];
            }
        @endphp
        <div>
            @if ($zweiZeilig)
                <div class="text-sm text-gray-600 mb-2">Anzeige zweizeilig (kleiner sm)</div>
            @else
                <div class="text-sm text-gray-600 mb-2">Anzeige einzeilig ( ab Bildschirmgröße "sm" )</div>
            @endif
        </div>
        <div class="min-w-full">
            <div class="flex flex-col w-full">
                @if (!$zweiZeilig) <!-- also Einzeilige Darstellung -->
                    <div class="flex flex-row space-x-2 w-full font-bold mb-2 border-b border-gray-500">
                        <div class="text-left {{ $w[0] }}">Artikelnr. </div>
                        <div class="text-left {{ $w[1] }} sm:ml-2">Bezeichnung</div>
                        <div class="text-left {{ $w[2] }}">Abladestelle</div>
                        @if ($lagerort == 0)
                            <div class="text-left {{ $w[3]}} ">Lagerort</div>
                        @endif
                        <div class="text-left {{ $w[4] }}  ">Lager-Platz</div>
                        <div class="text-right {{ $w[5] }} ">Bestand</div>
                        <div class="text-right {{ $w[6] }} ">&nbsp;</div>
                    </div>
                @else
                    <div class="flex flex-row space-x-2 w-full font-bold ">
                        <div class="text-left {{ $w[0] }} ">Artikelnr. </div>
                        <div class="text-left {{ $w[2] }}">Abladestelle</div>
                         @if ($lagerort == 0)
                            <div class="text-left {{ $w[3]}} ">Lagerort</div>
                        @endif
                        <div class="text-left {{ $w[4] }} ">Lager-Platz</div>
                        <div class="text-right {{ $w[5] }} ">Bestand</div>
                        <div class="text-right {{ $w[6] }} ">&nbsp;</div>
                    </div>
                    <div class="{{ $w[1] }} pl-2 mb-2 font-bold border-b border-gray-500">
                        Bezeichnung
                    </div>

                @endif

                @forelse($items as $p)
                    @if (!$zweiZeilig)
                        <x-listbestand-einzeilig :artikel="$p" :w="$w" :lagerort="$lagerort"/>
                    @else
                        <x-listbestand-zweizeilig :artikel="$p" :w="$w" :lagerort="$lagerort"/>
                    @endif

                @empty
                    <div class="text-center">Keine Daten</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="mt-4 py-4 mx-32 m-auto border-t border-gray-500">
        {{ $items->links() }}
    </div>

<script>

    window.addEventListener('load', () => {
        const getTailwindBreakpoint = () => {
            const w = window.innerWidth;
            if (w >= 1536) return '2xl';
            if (w >= 1280) return 'xl';
            if (w >= 1024) return 'lg';
            if (w >= 768) return 'md';
            if (w >= 640) return '';
            return '';
        };

        // Hilfsfunktion, um Livewire-Property zu setzen
        const updateBreakpoint = () => {
            const bp = getTailwindBreakpoint();

            // Komponente anhand der ID ermitteln
            const component = window.Livewire.find(@js($this->id()));

            if (component) {
                component.set('screenWidth', bp);
            }
        };

        // Initial einmalig
        updateBreakpoint();

        // Bei jeder Fensteränderung erneut
        window.addEventListener('resize', updateBreakpoint);
    });
</script>
</div>
