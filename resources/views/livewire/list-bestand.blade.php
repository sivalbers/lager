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
            <h1 class="text-2xl font-bold mt-4 mb-6">Artikel Lagerbestand</h1>
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
                <input type="text" wire:model.live.debounce.500="search" placeholder="Suchen…"
                    class="input w-full">
            </div>
            <div class="flex-[5] base-0 pr-2">
                <select wire:model.change="abladestelle" class="select w-full" {{ $abladestellenAuswahl->count() == 1 ? 'disabled' : '' }}>
                    <option value="0">- alle Abladestellen -</option>
                    @foreach ($abladestellenAuswahl as $stelle)
                        <option value="{{ $stelle->id }}">{{ $stelle->name }} (Id: {{ $stelle->id }} )</option>
                    @endforeach
                </select>
            </div>


        </div>

        @php
          $farbe_300 = 'bg-[#cdd503]';
          $farbe_200 = 'bg-[#dce14d]';
          $farbe_100 = 'bg-[#e7eb88]';
          $farbe_50  = 'bg-[#f6f8d3]';
        @endphp


        <div class="cursor-pointer font-bold bg-[#cdd503] border-b-2 border-black">
            <div class="flex w-full">
                    <div class="w-[30%] pl-2">Artikelnummer</div>
                    <div class="w-[50%]">Bezeichnung</div>
                    <div class="w-[10%] text-right pr-2">Bestand</div>
                    <div class="w-[10%] ">Einheit</div>
            </div>
        </div>


        @foreach ($this->artikelSummenPaginiert as $artikel)
            <div class="border mb-2 bg-orange-300 bg-orange-200 bg-orange-100 bg-orange-50 bg-[#cdd503]">
                {{-- Artikel-Zeile --}}
                <div wire:click="toggleArtikel('{{ $artikel['artikelnr'] }}')"
                    class="cursor-pointer bg-[#cdd503] px-4 py-2 w-full">

                    <div class="flex w-full">
                        <div class="w-[30%] font-medium flex items-center">
                            {{ $artikel['artikelnr'] }}
                            @if (in_array($artikel['artikelnr'], $offeneArtikel))
                                <x-heroicon-o-chevron-up class="w-5 h-5 mx-2" />
                            @else
                                <x-heroicon-o-chevron-down class="w-5 h-5 mx-2" />
                            @endif
                        </div>
                        <div class="w-[50%]">{{ $artikel['bezeichnung'] }}</div>
                        <div class="w-[10%] text-right">{{ $artikel['summe'] }}</div>
                        <div class="w-[10%] pl-2">{{ $artikel['einheit'] }}</div>
                    </div>
                </div>

                @if (in_array($artikel['artikelnr'], $offeneArtikel))

                @php
                /*
                    {{-- Abladestelle-Header --}}
                    <div class="bg-{{ $farbe }}-200 px-4 py-2">
                        <div class="flex w-full font-semibold pl-4">
                            <div class="w-[80%] border-b border-black">Abladestelle</div>

                            <div class="w-[10%] border-b border-black text-right"></div>
                            <div class="w-[10%] border-b border-black pl-2"></div>
                        </div>
                    </div>
                */
                @endphp

                    @foreach ($abladestellen[$artikel['artikelnr']] ?? [] as $abladestelle)
                        {{-- Abladestelle-Zeile --}}
                        <div wire:click="toggleAbladestelle('{{ $artikel['artikelnr'] }}', {{ $abladestelle['abladestelle_id'] }})"
                            class="cursor-pointer bg-[#dce14d] px-4 py-2 w-full">

                            <div class="flex w-full pl-4 items-center">
                                <div class="w-[80%] flex flex-row items-center">
                                    <span class="text-sm pr-2">Abladestelle: </span> ({{ $abladestelle['id'] }}) {{ $abladestelle['name'] }}

                                    @if (isset($lagerorte[$artikel['artikelnr']][$abladestelle['abladestelle_id']]))
                                        <x-heroicon-o-chevron-up class="w-5 h-5 mx-2" />
                                    @else
                                        <x-heroicon-o-chevron-down class="w-5 h-5 mx-2" />
                                    @endif
                                </div>

                                <div class="w-[10%] text-right">{{ $abladestelle['summe'] }}</div>
                                <div class="w-[10%] pl-2">{{ $artikel['einheit'] }}</div>
                            </div>
                        </div>


                        @if (isset($lagerorte[$artikel['artikelnr']][$abladestelle['abladestelle_id']]))


                            @php
                            /*
                            <div class="bg-[#e7eb88] px-4 py-2 w-full ">
                                <div class="flex w-full pl-8">
                                    <div class="w-[80%] border-b border-black">Lagerort</div>
                                    <div class="w-[10%] border-b border-black text-right"></div>
                                    <div class="w-[10%] border-b border-black pl-2"></div>
                                </div>
                            </div>
                            */
                            @endphp
                            @foreach ($lagerorte[$artikel['artikelnr']][$abladestelle['abladestelle_id']] ?? [] as $lagerort)
                                {{-- Lagerort-Zeile --}}

                                    <div wire:click="toggleLagerort('{{ $artikel['artikelnr'] }}', {{ $abladestelle['abladestelle_id'] }}, {{ $lagerort['lagerort_id'] }})"
                                        class="cursor-pointer bg-[#e7eb88] px-4 py-2 w-full">
                                        <div class="flex w-full pl-8">
                                            <div class="w-[80%] flex flex-row items-center">
                                                <span class="text-sm pr-2">Lagerort: </span>
                                                ({{ $lagerort['id'] }})
                                                {{ $lagerort['bezeichnung'] }}


                                                    @if (isset($lagerplaetze[$artikel['artikelnr']][$abladestelle['abladestelle_id']][$lagerort['lagerort_id']]))
                                                        <x-heroicon-o-chevron-up class="mx-2 w-5 h-5" />
                                                    @else
                                                        <x-heroicon-o-chevron-down class="w-5 h-5 mx-2" />
                                                    @endif
                                            </div>

                                            <div class="w-[10%] text-right">{{ $lagerort['summe'] }}</div>
                                            <div class="w-[10%] pl-2">{{ $artikel['einheit'] }}</div>
                                        </div>
                                    </div>
                                @if (isset($lagerplaetze[$artikel['artikelnr']][$abladestelle['abladestelle_id']][$lagerort['lagerort_id']]))
                                    @php
                                        /*
                                    <div class="bg-[#f6f8d3] px-4 py-2 w-full">
                                        <div class="flex w-full pl-12">
                                            <div class="w-[80%] border-b border-black">Lagerplatz</div>

                                            <div class="w-[10%] border-b border-black text-right"></div>
                                            <div class="w-[10%] border-b border-black pl-2"></div>
                                        </div>
                                    </div>
                                        */
                                    @endphp

                                    @foreach ($lagerplaetze[$artikel['artikelnr']][$abladestelle['abladestelle_id']][$lagerort['lagerort_id']] ?? [] as $platz)
                                        {{-- Lagerplatz-Zeile --}}
                                        <div class="bg-[#f6f8d3] px-4 py-2 w-full">
                                            <div class="flex w-full pl-12">
                                                <div class="w-[80%]"><span class="text-sm pr-2">Lagerplatz: </span>{{ $platz['lagerplatz'] }}</div>
                                                <div class="w-[10%] text-right">{{ $platz['bestand'] }}</div>
                                                <div class="w-[10%] pl-2">{{ $artikel['einheit'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </div>
        @endforeach



    <button class="cursor-pointer" wire:click="previousPage" {{ $currentPage === 1 ? 'disabled' : '' }}>Vorherige Seite | </button>
    <button class="cursor-pointer" wire:click="nextPage" {{ $currentPage * $perPage >= count($artikelSummen) ? 'disabled' : '' }}>Nächste Seite</button>

    <div>Seite {{ $currentPage }} von {{ ceil(count($artikelSummen) / $perPage) }}</div>
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
