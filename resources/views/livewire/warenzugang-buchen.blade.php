<div class="p-6"  x-data="{ tab: 'lieferschein' }">
    <h2 class="text-2xl font-bold mb-4">Warenzugang</h2>
    <x-mary-toast />


    <div class="mb-6 print:hidden">
        <nav class="flex space-x-4 border-b-2 border-sky-600" aria-label="Tabs">
            <button @click="tab = 'lieferschein'"
                :class="tab === 'lieferschein' ? 'text-sky-600  bg-white rounded-t-md border-l border-t border-r border-sky-600 p-2 px-4' :
                    'text-sky-600  bg-gray-300 rounded-t-md border-l border-t border-r border-sky-600 p-2 px-4 hover:text-white'"
                class="whitespace-nowrap pb-2 px-1 font-medium text-sm bg-gray-600">Warenzugang über Lieferschein</button>

            <button @click="tab = 'artikelliste'"
                :class="tab === 'artikelliste' ? 'border-sky-500 text-sky-600  bg-white rounded-t-md border-l border-t border-r border-sky-600 p-2 px-4' :
                    'text-sky-600  bg-gray-300 rounded-t-md border-l border-t border-r border-sky-600 p-2 px-4 hover:text-white'"
                class="whitespace-nowrap pb-2 px-1 font-medium text-sm">Textfeld</button>

        </nav>
    </div>



    <div x-show="tab === 'lieferschein'" class="space-y-2 my-6 print:hidden">
        <div class="flex flex-col ">
            <div class="flex flex-row items-center space-x-4">
                <label class="font-medium">Lieferschein-Nr: LS000</label>
                <input type="text" wire:model="lieferscheinNr" class="border px-2 py-1 rounded w-32" placeholder="z.B. 70824">

                <div class="flex flex-row items-center space-x-4">
                    <button wire:click="readLieferschein" class="bg-sky-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                        Lieferschein holen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="tab === 'artikelliste'" class="space-y-2 my-6 print:hidden">
        <div class="flex flex-col">

                <div>
                <div class="font-medium">Artikelliste übernehmen</div>
                <div class="text-xs">Format:  Artikelnr;Abladestelle;Lagerort;Lagerplatz;Menge</div>
                <div class="text-xs">Beispiel:  95004916;BM Leer;Allgemein - Weser Str. 3;ABC;12</div>

                </div>
                <div class="flex flex-row">
                <textarea
                    name="artikelliste"
                    wire:model="artikelliste"
                    rows="15"
                    class="border px-2 py-1 rounded w-full sm:w-6/12 resize-y"
                ></textarea>
                <textarea
                    disabled
                    name="ergebnisliste"
                    wire:model="ergebnisliste"
                    rows="15"
                    class="border px-2 py-1 rounded w-full sm:w-6/12 resize-y bg-gray-100"
                ></textarea>
                </div>




                <div class="flex flex-row justify-between sm:w-6/12 space-x-4 mt-2">
                    <button wire:click="checkArtikelliste" class="bg-sky-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                        Liste prüfen
                    </button>

                    <button wire:click="readArtikelliste" class="bg-sky-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                        Liste verarbeiten
                    </button>
                </div>

        </div>
    </div>





    @if ($message)
        <div class="text-red-500 mb-4">{{ $message }}</div>
    @endif

    @if (count($positionen) > 0)
        <div class="overflow-auto">
            <table class="min-w-full text-sm border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Artikel</th>
                        <th class="border px-2 py-1 text-left">Bezeichnung</th>
                        <th class="border px-2 py-1 text-left">Einheit</th>
                        <th class="border px-2 py-1 text-left">Abladestelle</th>
                        <th class="border px-2 py-1 text-left">Lagerort</th>
                        <th class="border px-2 py-1 text-left">Lagerplatz</th>
                        <th class="border px-2 py-1 text-right">Menge</th>
                        <th class="border px-2 py-1 text-center">Etik.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positionen as $index => $pos)
                        <tr wire:key="row-{{ $index }}">
                            <td class="border px-2 py-1">{{ $pos['artikelnr'] }}</td>
                            <td class="border px-2 py-1">{{ $pos['bezeichnung'] }}</td>
                            <td class="border px-2 py-1">{{ $pos['einheit'] }}</td>
                            <td class="border px-2 py-1">


                            <select
                                wire:model.change="positionen.{{ $index }}.abladestelle"
                                class="w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <option value="">---</option>
                                @foreach($abladestellenList as $stelle)
                                    <option value="{{ (string) $stelle->id }}">
                                        {{ $stelle->name }}
                                    </option>
                                @endforeach
                            </select>

                            </td>

                            <td class="border px-2 py-1">
                                <select
                                    wire:model="positionen.{{ $index }}.lagerort"
                                    class="w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                    <option value="">---</option>
                                    @php
                                        echo "<pre>";
                                            var_dump($pos['lagerort']);
                                        echo "</pre>";
                                    @endphp

                                    @php
                                    /*foreach($pos['lagerorte'] as $bezeichnung => $id)
                                        <option value="{{ $id }}" @selected($id == $pos['lagerort'])>
                                            {{ $bezeichnung }}
                                        </option>
                                    @endforeach
                                    */
                                    @endphp
                                    @foreach($pos['lagerorte'] as $lort)
                                        <option value="{{ $lort['id'] }}" @selected($pos['lagerort'] === $lort['id'])>
                                            {{ $lort['bezeichnung'] }}
                                        </option>
                                    @endforeach


                                </select>
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" wire:model="positionen.{{ $index }}.lagerplatz" class="border rounded px-1 py-0.5 w-full">
                            </td>
                            <td class="border px-2 py-1 text-right">
                                <input type="number" wire:model="positionen.{{ $index }}.menge" class="border rounded px-1 py-0.5 w-16 text-right">
                            </td>
                            <td class="border px-2 py-1 text-center">
                                <input type="checkbox" wire:model="positionen.{{ $index }}.etikett" class="border rounded px-1 py-0.5 ">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-row space-x-4">
            <button wire:click="weBuchen" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Buchen
            </button>
        </div>
    @endif


    <div wire:loading class="fixed inset-0 z-50 bg-white bg-opacity-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="mt-2 text-gray-700 text-sm text-center">Lade Daten...</p>
        </div>
    </div>
</div>
