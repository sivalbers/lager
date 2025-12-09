<div class="w-5/6 m-auto">
    <h1 class="text-2xl font-bold my-4 print:hidden">Etiketten erstellen</h1>

    <h4 class="mb-8 print:hidden">Es gibt mehrere Möglichkeiten Etiketten zu erzeugen:
        <ul class="list-disc pl-6">

            <li>Vorgeschlagen werden immer die Artikel die beim Wareneingang erzeugt wurden. (Möglichkeit 1)</li>
            <li>Über die Eingabefelder. (Möglichkeit 2)</li>
            <li>Über das Textfeld. (Möglichkeit 3)</li>
        </ul>
    </h4>


        <div class="print:hidden border-b-4 border-black w-full"></div>
        <div class="print:hidden border-b-8 border-sky-600 w-full"></div>


    <div class="space-y-2 my-6 print:hidden">
        {{-- 1. Zeile: Überschrift --}}
        <div class="text-lg font-semibold">Manuelle Eingabe (Möglichkeit 2)</div>

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
                    <option value="{{ $id }}" {{ $mLagerort_id === $id ? 'selected' : '' }}>
                        {{ $bezeichnung }}
                    </option>
                @endforeach
            </select>

            <input type="text" wire:model="mLagerplatz" class="h-10 border rounded px-2 text-sm" />

            <button type="submit" wire:click="manuelleErfassung"
                class="h-10 px-4 bg-sky-600 text-white rounded">Hinzufügen</button>
        </div>
    </div>



        <div class="print:hidden border-b-4 border-black w-full"></div>
        <div class="print:hidden border-b-8 border-sky-600 w-full"></div>


    <div class="mt-6 text-lg font-semibold my-4 print:hidden">Eingabe über Eingabefelder (Möglichkeit 3)</div>
    <!-- Eingabeformular -->
    <form wire:submit.prevent="createDataFromText" class="print:hidden mb-6">
        <label for="zeilen" class="block mb-2">Felder: "Artikelnummer, Abladestelle-ID, Lagerort-ID,
            Lagerplatz"</label>
        <textarea wire:model.defer="text" id="zeilen" rows="6" class="w-full border p-2"></textarea>
        <div class="flex justify-end print:hidden">
            <button type="submit" class="mt-3 px-4 py-2 bg-sky-600 text-white rounded">
                Etiketten aus Textfeld erzeugen
            </button>
        </div>
    </form>

    <!-- Ausgabe der QR-Codes -->
    @if (!empty($data))
        <div class="print:hidden border-b-4 border-black w-full"></div>
        <div class="print:hidden border-b-8 border-sky-600 w-full"></div>
        <h2 class="mt-6 text-lg font-bold mb-2 print:hidden">Erzeuge Etiketten</h2>
        <h2 class="text-lg font-bold mb-2 hidden print:flex">Etiketten:</h2>

            <div class="overflow-auto print:hidden">
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
                        @foreach ($data as $index => $pos)
                            <tr wire:key="row-{{ $index }}">
                                <td class="border px-2 py-1">
                                    <div class="flex flex-row items-center">

                                        {{ $pos['artikelnr'] }}  @if (!empty($pos['we'])) <x-img_warenzugang class="text-gray-500 ml-2 w-5" /> @endif
                                    </div>
                                </td>

                                <td class="border px-2 py-1">{{ $pos['bezeichnung'] }}</td>

                                <td class="border px-2 py-1">
                                    <select
                                        wire:model="data.{{ $index }}.abladestelle_id"
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
                                            <option value="{{ $id }}">
                                                {{ $bezeichnung }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="border px-2 py-1">
                                    <input type="text" wire:model="data.{{ $index }}.lagerplatz"
                                        class="border rounded px-1 py-0.5 w-full">
                                </td>

                                <td>
                                    @if (!empty($pos['we']))
                                    <x-mary-button wire:click="delEtikett({{ $pos['we'] }})" title="Etikett löschen aus Wareneingang" icon="o-trash" />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                {{-- Neuer Button: --}}
                <div class="flex justify-end print:hidden">
                <button type="button" wire:click="aktualisiereQRCodes"
                    class="h-10 px-4 bg-sky-600 text-white rounded">
                    QR-Codes aktualisieren
                </button>
            </div>
        </div>


        <div x-data="{ spalten: 5 }" class="mt-6">
            <div class="flex items-center gap-2 mb-4  print:hidden">
                <label for="spalten">Anzahl Etiketten nebeneinander:</label>
                <input id="spalten" type="number" min="1" max="12" x-model.number="spalten"
                    class="border p-1 w-16 text-center" />
                <button @click="window.print()" class="h-10 px-4 bg-sky-600 text-white rounded">Drucken</button>

            </div>

            <div class="grid gap-6" :style="'grid-template-columns: repeat(' + spalten + ', minmax(0, 1fr))'">
                @foreach ($data as $item)
                    <div class="p-2 border text-center">
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
                            <div>{!! QrCode::size(150)->generate(json_encode($data)) !!}</div>
                            <div class="text-left">
                                {{ $item['artikelnr'] }} | {{ $item['bezeichnung'] }} <br>
                                {{ $item['abladestelle_id'] }} | {{ $abladestelle }} <br>
                                {{ $item['lagerort_id'] }} | {{ $lagerort }} <br>
                                {{ $item['lagerplatz'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div wire:loading class="fixed inset-0 z-50 bg-white bg-opacity-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="mt-2 text-gray-700 text-sm text-center">Lade Daten...</p>
        </div>
    </div>


</div>
