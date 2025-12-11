<div class="w-5/6 m-auto" x-data="{ tab: 'warenzugang' }">



    <h1 class="text-2xl font-bold my-4 print:hidden">Etiketten erstellen</h1>

    <div class="mb-6 print:hidden">
        <nav class="flex space-x-4" aria-label="Tabs">
            <button @click="tab = 'warenzugang'"
                :class="tab === 'warenzugang' ? 'border-sky-500 text-sky-600' :
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm">Wareneingang</button>
            <button @click="tab = 'eingabe'"
                :class="tab === 'eingabe' ? 'border-sky-500 text-sky-600' :
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm">Textfeld</button>
            <button @click="tab = 'manuell'"
                :class="tab === 'manuell' ? 'border-sky-500 text-sky-600' :
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm">Manuell</button>
        </nav>
    </div>



    <div x-show="tab === 'manuell'" class="space-y-2 my-6 print:hidden">
        <!-- @ include('etiketten.partials.manuell') -->
        <div class="space-y-2 my-6 print:hidden">
            {{-- 1. Zeile: Überschrift --}}
            {{-- 2. Zeile: Labels --}}
            <div class="grid grid-cols-6 gap-4 text-sm text-gray-500">
                <div>Artikel</div>
                <div>Bezeichnung</div>
                <div>Abladestelle</div>
                <div>Lagerort</div>
                <div>Lagerplatz</div>
                <div>&nbsp;</div>
            </div>

            {{-- 3. Zeile: Eingabefelder --}}

            <div class="grid grid-cols-6 gap-4 items-end">
                <input type="text" wire:model.blur="mArtikelNr" class="h-10 border rounded px-2 text-sm" />
                <input type="text" wire:model="mBezeichnung"
                    class="h-10 border rounded px-2 bg-gray-100 text-sm text-gray-600" readonly />

                <select wire:model.blur="mAbladestelle_id" class="h-10 border rounded px-2 text-sm"
                    {{ !empty($abladestellenList) && count($abladestellenList) == 1 ? 'disabled' : '' }}>
                    <option value="">Bitte wählen</option>
                    @foreach ($abladestellenList as $stelle)
                        <option value="{{ $stelle['id'] }}">{{ $stelle['name'] }}</option>
                    @endforeach
                </select>

                <select wire:model.blur="mLagerort_id" class="h-10 border rounded px-2 text-sm">
                    <option value="">Bitte wählen</option>
                    @foreach ($lagerorteList as $bezeichnung => $id)
                        <option value="{{ $id }}" >
                            {{ $bezeichnung }} {{ $id }}
                        </option>
                    @endforeach
                </select>

                <input type="text" wire:model="mLagerplatz" class="h-10 border rounded px-2 text-sm" />

                <button type="submit" wire:click="manuelleErfassung"
                    class="h-10 px-4 bg-sky-600 text-white rounded">Hinzufügen</button>
            </div>
        </div>
    </div>



    <div x-show="tab === 'eingabe'" class="mt-6 print:hidden">

        <label for="zeilen" class="block mb-2">Felder: "Artikelnummer, Abladestelle-ID, Lagerort-ID,
            Lagerplatz"</label>
        <textarea wire:model.defer="text" id="zeilen" rows="6" class="w-full border p-2"></textarea>
        <div class="flex justify-between print:hidden">
            <button wire:click="weZuText()" class="mt-3 px-4 py-2 bg-sky-600 text-white rounded">
                Wareneingang übernehmen
            </button>

            <button wire:click="createDataFromText()" class="mt-3 px-4 py-2 bg-sky-600 text-white rounded">
                Etiketten erzeugen
            </button>
        </div>

    </div>


    <div x-show="tab === 'warenzugang'" class="print:hidden">
        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1 text-left">Artikel</th>
                    <th class="border px-2 py-1 text-left">Bezeichnung</th>
                    <th class="border px-2 py-1 text-left">Abladestelle</th>
                    <th class="border px-2 py-1 text-left">Lagerort</th>
                    <th class="border px-2 py-1 text-left">Lagerplatz</th>
                    <th class="border px-2 py-1 text-left">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($etiketten as $etikett)
                    <tr wire:key="etiketten-{{ $etikett->id }}">
                        <td class="border px-2 py-1">
                            <div class="flex flex-row items-center">
                                {{ $etikett->artikelnr }}
                            </div>
                        </td>

                        <td class="border px-2 py-1">{{ $etikett->artikel->bezeichnung }} </td>

                        <td class="border px-2 py-1">
                            {{ $etikett->abladestelle->name }}
                        </td>

                        <td class="border px-2 py-1">
                            {{ $etikett->lagerort->bezeichnung }}
                        </td>

                        <td class="border px-2 py-1">
                            {{ $etikett->lagerplatz }}
                        </td>

                        <td>
                            <x-mary-button wire:click="delEtikett({{ $etikett->id }})"
                                title="Etikett löschen aus Wareneingang" icon="o-trash" />
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <div class="flex justify-end print:hidden">
            <button wire:click="createDataFromTable" class="mt-3 px-4 py-2 bg-sky-600 text-white rounded">
                Etiketten erzeugen
            </button>
        </div>
    </div>

    <!-- Ausgabe der QR-Codes -->
    @if (!empty($data))
        <div class="bg-white rounded-md border border-black p-4 my-8 print:hidden">

            <div class="flex flex-row justify-between space-x-4">
                <h2 class="text-lg font-bold mb-2 print:hidden">Erzeugte Etiketten</h2>
                <button type="button" wire:click="clearData"
                    class="h-6 px-4 bg-sky-600 text-white rounded">
                    Alle Etiketten löschen
                </button>
            </div>

            <h2 class="text-lg font-bold mb-2 hidden print:flex">Etiketten:</h2>

            <div class="overflow-auto print:hidden">
                <table class="min-w-full text-sm border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">&nbsp;</th>
                            <th class="border px-2 py-1 text-left">Artikel</th>
                            <th class="border px-2 py-1 text-left">Bezeichnung</th>
                            <th class="border px-2 py-1 text-left">Abladestelle</th>
                            <th class="border px-2 py-1 text-left">Lagerort</th>
                            <th class="border px-2 py-1 text-left">Lagerplatz</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $pos)
                            <tr wire:key="row-{{ $index }}">
                                <td class="border py-1">
                                    <x-mary-button wire:click="delDataIx({{ $index }})" title="Etikett löschen" icon="o-trash" />

                                </td>
                                <td class="border px-2 py-1">
                                    <div class="flex flex-row items-center">

                                        {{ $pos['artikelnr'] }} @if (!empty($pos['we']))
                                            <x-img_warenzugang class="text-gray-500 ml-2 w-5" />
                                        @endif
                                    </div>
                                </td>

                                <td class="border px-2 py-1">{{ $pos['bezeichnung'] }}</td>

                                <td class="border px-2 py-1">
                                    <select wire:model="data.{{ $index }}.abladestelle_id"
                                        wire:blur="abladestelleGeaendert({{ $index }})"
                                        class="w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                        <option value="">---</option>
                                        @foreach ($abladestellenList as $stelle)
                                            <option value="{{ (string) $stelle->id }}">
                                                {{ $stelle->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="border px-2 py-1">
                                    <select wire:model="data.{{ $index }}.lagerort_id"
                                        class="w-full text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                        <option value="">---</option>
                                        @foreach ($pos['lagerorte'] as $bezeichnung => $id)
                                            <option value="{{ $id }}" {{ $data[$index]['lagerort_id'] === $id ? 'selected' : '' }}>
                                                {{ $bezeichnung }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="border px-2 py-1">
                                    <input type="text" wire:model="data.{{ $index }}.lagerplatz"
                                        class="border rounded px-1 py-0.5 w-full">
                                </td>


                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>


        <div x-data="{ spalten: 1 }" class="mt-6 w-full">

            <div class="flex flex-col w-full print:hidden">
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex items-center w-10/12 space-x-2">
                        <label for="spalten" class="w-4/12 text-right">Anzahl QR-Codes nebeneinander:</label>
                        <input id="spalten" type="number" min="1" max="12" x-model.number="spalten"
                            class="border p-1 w-16 text-center" />
                    </div>

                    <div class="flex justify-end w-2/12">
                        <button type="button" wire:click="aktualisiereQRCodes" class="mt-2 h-8 px-4 bg-sky-600 text-white rounded">
                            QR-Codes aktualisieren
                        </button>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center w-full">
                    <div class="flex items-center w-10/12 space-x-2">
                        <label for="spalten" class="w-4/12 text-right">QR-Code größe:</label>
                        <input id="spalten" type="number" min="1" max="200" wire:model="qrGroesse"
                            class="border p-1 w-16 text-center" />
                    </div>

                    <div class="flex justify-end w-2/12">
                        <button @click="window.print()" class="h-8 px-4 bg-sky-600 text-white rounded">Drucken</button>
                    </div>
                </div>
            </div>


            <div class="w-full border-black border-t my-8 font-bold text-2xl print:hidden" >
                QR-Code Vorschau
            </div>

            <div class="grid gap-6" :style="'grid-template-columns: repeat(' + spalten + ', minmax(0, 1fr))'">
                @foreach ($data as $item)
                    <div class="p-2 text-center">
                        <div class="flex flex-col gap-6">
                            @php
                                $data = [
                                    'artikelnr' => $item['artikelnr'],
                                    'abladestelle' => $item['abladestelle_id'],
                                    'lagerort' => $item['lagerort_id'],
                                    'lagerplatz' => $item['lagerplatz'],
                                ];
                                $abladestelle = $abladestellenList->find($item['abladestelle_id'])->name ?? 'Unbekannt';

                                $lagerort = array_search($item['lagerort_id'], $pos['lagerorte']) ?? 'Unbekannt';

                            @endphp
                            <div class="flex flex-row items-center space-x-4">
                                <div>{!! QrCode::size($qrGroesse)->generate(json_encode($data)) !!}</div>
                                <div class="text-left text-xl">
                                    <!--
                                    {{ $item['artikelnr'] }} | {{ $item['bezeichnung'] }} <br>
                                    {{ $item['abladestelle_id'] }} | {{ $abladestelle }} <br>
                                    {{ $item['lagerort_id'] }} | {{ $lagerort }} <br>
                                    {{ $item['lagerplatz'] }}
                                    -->
                                    <span class="font-bold text-3xl">{{ $item['bezeichnung'] }}</span><br>
                                    {{ $item['artikelnr'] }} | {{ $abladestelle }} | {{ $lagerort }} <br>
                                    <span class="font-bold text-3xl">{{ $item['lagerplatz'] }}</span>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div wire:loading class="fixed inset-0 z-50 bg-white bg-opacity-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="mt-2 text-gray-700 text-sm text-center">Lade Daten...</p>
        </div>
    </div>


</div>
