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
        @php
            $zweiZeilig = $screenWidth === '';

            if (!$zweiZeilig) { // also einzeilig
                if ($lagerort > 0){
                    $w = [ "w-2/12 bg-red-200", "w-7/12 bg-blue-200", "null bg-red-600", "w-1/12 bg-pink-200", "w-1/12 bg-lime-200", "w-1/12 bg-orange-200" ];
                }
                else {
                    $w = [ "w-1/12 bg-red-200", "w-6/12 bg-blue-200", "w-2/12 bg-red-600", "w-1/12 bg-pink-200", "w-1/12 bg-lime-200", "w-1/12 bg-orange-200" ];
                }
            }
            else { // Artikel in zwei Zeilen
                if ($lagerort > 0){ // Lagerort wird ausgeblendet
                    $w = [ "w-4/12 bg-red-200", "w-full bg-blue-200", "null bg-red-600", "w-4/12 bg-pink-200", "w-4/12 bg-lime-200", "w-1/12 bg-orange-200" ];
                }
                else {
                    $w = [ "w-3/12 bg-red-200", "w-full bg-blue-200", "w-4/12 bg-red-600", "w-2/12 bg-pink-200", "w-2/12 bg-lime-200", "w-1/12 bg-orange-200" ];
                }
            }

             if (!$zweiZeilig) {
                if ($lagerort > 0){
                    $w = [ "w-2/12", "w-7/12", "null", "w-1/12", "w-1/12", "w-1/12" ];
                }
                else {
                    $w = [ "w-1/12", "w-6/12", "w-2/12", "w-1/12", "w-1/12", "w-1/12" ];
                }
            }
            else { // Artikel in zwei Zeilen
                if ($lagerort > 0){ // Lagerort wird ausgeblendet
                    $w = [ "w-4/12", "w-full", "null", "w-4/12", "w-4/12", "w-1/12" ];
                }
                else {
                    $w = [ "w-3/12", "w-full", "w-4/12", "w-2/12", "w-2/12", "w-1/12" ];
                }

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
                @if (!$zweiZeilig)
                    <div class="flex flex-row space-x-2 w-full font-bold mb-2 border-b border-gray-500">
                        <div class="text-left {{ $w[0] }}">Artikelnr. </div>
                        <div class="text-left {{ $w[1] }}">Bezeichnung</div>
                        @if ($lagerort == 0)
                            <div class="text-left {{ $w[2]}} ">Lagerort</div>
                        @endif
                        <div class="text-right {{ $w[3] }} ">Lager-Platz</div>
                        <div class="text-right {{ $w[4] }} ">Bestand</div>
                        <div class="text-right {{ $w[5] }} ">&nbsp;</div>
                    </div>
                @else
                    <div class="flex flex-row space-x-2 w-full font-bold ">
                        <div class="text-left {{ $w[0] }} ">Artikelnr. </div>
                         @if ($lagerort == 0)
                            <div class="text-left {{ $w[2]}} ">Lagerort</div>
                        @endif
                        <div class="text-right {{ $w[3] }} ">Lager-Platz</div>
                        <div class="text-right {{ $w[4] }} ">Bestand</div>
                        <div class="text-right {{ $w[5] }} ">&nbsp;</div>
                    </div>
                    <div class="{{ $w[1] }} mb-2 font-bold border-b border-gray-500">
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
