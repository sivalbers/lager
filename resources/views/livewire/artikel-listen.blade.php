<div x-data="{ showArtikel: @entangle('showArtikel'),
                showEinrichtung: @entangle('showEinrichtung') }" class="w-11/12 m-auto">

    <h1 class="text-2xl font-bold mt-4">Artikelverwaltung</h1>

    @if (auth()->user()->hasBerechtigung('artikel anlegen'))
        <div class="flex justify-end pr-2">
            <x-mary-button class="btn-primary bg-sky-600 text-white px-2" wire:click="editArtikel" icon="o-plus-circle"
                label="Neuen Artikel anlegen" />
        </div>
    @endif

    <div class="flex flex-col w-full border-b border-gray-600 mt-2">
        <div class="flex flex-row md:font-bold text-sky-600 border-b border-sky-600 px-1 text-xs md:text-base">
            <div class="w-2/12">Artikelnr.</div>
            <div class="w-5/12">Bezeichnung</div>
            <div class="w-2/12">Einheit</div>
            <div class="w-3/12">Materialgruppe</div>
        </div>
    </div>

    @foreach ($artikel as $art)
        <div class="flex flex-col pb-2 " wire:key="artikel-{{ $art->artikelnr }}">
            <div class="flex flex-row hover:bg-slate-200 px-1">
                <div class="w-2/12">
                    @if (auth()->user()->hasBerechtigung('artikel ändern'))
                        <a href="#" wire:click="editArtikel(false, '{{ $art->artikelnr }}')"
                            class="hover:underline text-sky-600">{{ $art->artikelnr }}</a>
                    @else
                        {{ $art->artikelnr }}
                    @endif
                </div>
                <div class="w-5/12">{{ $art->bezeichnung }}</div>
                <div class="w-2/12">{{ $art->einheit }}</div>
                <div class="w-3/12 flex flex-row justify-between" >
                    <div>{{ $art->materialgruppe }}</div>
                    @if (auth()->user()->hasBerechtigung('artikel anlegen'))
                        <div>
                            <button title="Neue Einrichtung anlegen" class="h-5 w-5 text-sky-600"
                                wire:click="editEinrichtung(true, null, '{{ $art->artikelnr }}')">
                                <x-mary-icon name="o-plus-circle" />
                            </button>
                        </div>
                    @endif

                </div>
            </div>

            @if (count($art->einrichtungen) > 0)
            <!--
                <div class="flex flex-row w-full  px-1">
                    <div class="w-full flex justify-between text-xs text-gray-500">
                        <div class="pl-4 border-t w-full">Einrichtungen:</div>
                    </div>
                </div>
            -->


                <div class="flex justify-between">
                    <div class="flex flex-row w-full md:font-bold text-sky-600 px-1 text-xs md:text-base">
                        <div class="w-1/12 "></div>
                        <div class="w-3/12 border-b border-gray-600">
                            <span class="hidden md:flex">Abladestelle</span>
                            <span class="flex md:hidden">Abl.</span>
                        </div>
                        <div class="w-4/12 border-b border-gray-600">
                            <span class="hidden md:flex">Lagerort</span>
                            <span class="flex md:hidden">L-Ort.</span>
                        </div>
                        <div class="w-2/12 border-b border-gray-600">
                            <span class="hidden md:flex">Mindestbest.</span>
                            <span class="flex md:hidden">Min.</span>
                        </div>
                        <div class="w-2/12 border-b border-gray-600">
                            <span class="hidden md:flex">Bestellm.</span>
                            <span class="flex md:hidden">Best.</span>
                        </div>
                    </div>
                    <div class=" border-gray-600 w-10"></div>
                </div>
            @endif


            @foreach ($art->einrichtungen as $einr)
                <div class="flex justify-between hover:bg-slate-200 ">
                    <div class="flex flex-row w-full px-1 text-sm" wire:key="einrichtung-{{ $einr->id }}">
                        <div class="w-1/12"></div>
                        <div class="w-3/12">
                            @if (auth()->user()->hasBerechtigung('artikel ändern'))
                                <a href="#" wire:click="editEinrichtung(false, {{ $einr->id }})"
                                    class="hover:underline text-sky-600">
                                    {{ $einr->abladestelle->name ?? '-' }}
                                </a>
                            @else
                                {{ $einr->abladestelle->name ?? '-' }}
                            @endif
                        </div>
                        <div class="w-4/12">{{ $einr->lagerort?->bezeichnung ?? '-' }}</div>
                        <div class="w-2/12">{{ $einr->mindestbestand }}</div>
                        <div class="w-2/12">{{ $einr->bestellmenge }}</div>
                    </div>

                    <div class="flex justify-end w-10">
                        @if (auth()->user()->hasBerechtigung('artikel löschen'))
                            <a wire:click="confirmDelete({{ $einr->id }})"
                                class="text-red-500 hover:underline cursor-pointer">🗑️</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach



    {{-- Modal: Artikel --}}
    <div x-data="{ nr: '{{ $artikelnr }}' }" x-show="showArtikel" key="{{ now() }}" x-cloak
        class="fixed inset-0 z-10 bg-black/40 flex justify-center items-center">

        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-full sm:w-6/12 sm:bg-yellow-300">
            <h2 class="text-xl font-bold mb-4">Artikel {{ $isEditArtikel ? 'ändern' : 'anlegen' }} </h2>

            <div class="flex flex-col gap-2">
                <div class="flex flex-row items-end">

                    <div x-data="{ nr: '{{ $artikelnr }}' }" class="flex flex-row items-center w-full">
                        <x-mary-input  label="Artikelnr." x-model="nr" type="text" class="w-20" :disabled="$isEditArtikel" class="!outline-none focus:!outline-none focus:!ring-0" />
                        <x-mary-button class="btn-primary mt-6 ml-4 bg-sky-600 text-white h-8 px-4"

                            @click="$wire.set('artikelnr', nr).then(() => $wire.loadFromFaveo(nr))"

                            label="Artikel holen" />


                    </div>
                </div>
                <x-mary-input label="Bezeichnung" wire:model="artikelBezeichnung" class="!outline-none focus:!outline-none focus:!ring-0" />
                <x-mary-input label="Einheit" wire:model="artikelEinheit" class="!outline-none focus:!outline-none focus:!ring-0" />
                <x-mary-input label="Materialgruppe" wire:model="artikelMaterialgruppe" class="!outline-none focus:!outline-none focus:!ring-0" />
                <x-mary-input label="EK-Preis (€)" wire:model="artikelEkpreis" numeric="true" with_dots="true" class="!outline-none focus:!outline-none focus:!ring-0" />
            </div>

            <div class="flex justify-end gap-4 mt-4">
                <x-mary-button type="secondary" @click="showArtikel = false" label="Schließen" class="mt-6 ml-4 bg-gray-500 text-white h-8 px-4 shadow-md shadow-gray-500"/>
                <x-mary-button type="primary" wire:click="saveArtikel" label="Speichern" class="mt-6 ml-4 bg-sky-600 text-white h-8 px-4 shadow-md shadow-gray-500"/>
            </div>
        </div>
    </div>

    {{-- Modal: Einrichtung --}}
    <div x-show="showEinrichtung" x-cloak class="fixed inset-0 z-20 bg-black/40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-6/12">
            <h2 class="text-xl font-bold mb-4 justify-between flex">
                <div>Einrichtung {{ $isEditEinrichtung ? 'ändern' : 'anlegen' }}</div>
                <div class="text-xs text-gray-500"> {{ $artikelnr }} - {{ $artikelBezeichnung }}</div>
            </h2>
            <input type="hidden" wire:model="artikelnr" />

            <div x-data="" class="grid grid-cols-2 gap-4 align-bottom">
                <div>
                    <label>Abladestelle</label>
                    <select wire:model.live="abladestelle_id" class="w-full border border-gray-300 rounded p-1 disabled:bg-gray-100"
                        {{ $isEditEinrichtung == true ? 'disabled' : '' }}>
                        <option value="0">Bitte wählen</option>

                        @foreach ($abladestellen as $stelle)
                            <option value="{{ $stelle->id }}">{{ $stelle->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Lagerort</label>
                    <select wire:model="lagerort_id"
                        class="w-full border border-gray-300 rounded p-1 disabled:bg-gray-100">

                        <option value="0" {{ $lagerort_id === 0 ? 'selected' : '' }}>
                            Bitte wählen
                        </option>

                        @foreach ($lagerortAuswahl as $lagerort)
                            <option value="{{ (int) $lagerort->id }}"
                                {{ $lagerort_id === $lagerort->id ? 'selected' : '' }}>
                                {{ $lagerort->bezeichnung }}</option>
                        @endforeach
                    </select>
                </div>
                <x-mary-input label="Mindestbestand" wire:model="mindestbestand" numeric="true" right />
                <x-mary-input label="Bestellmenge" wire:model="bestellmenge" numeric="true" right />
            </div>

            <div class="flex justify-end gap-4 mt-4">
                <x-mary-button type="secondary" @click="showEinrichtung = false" label="Schließen" class="px-4 bg-gray-500 text-white" />
                <x-mary-button type="primary" wire:click="saveEinrichtung" label="Speichern" class="px-4 bg-sky-600 text-white" />
            </div>
        </div>
    </div>

    <div x-data="{ showDelete: @entangle('confirmingDelete') }">
        <div x-show="showDelete" x-cloak
            class="fixed inset-0 bg-black/40 z-20 flex justify-center items-center">
            <div class="bg-white p-6 rounded shadow-md w-4/12">
                <h2 class="text-lg font-bold mb-2 text-red-600">Löschen bestätigen</h2>
                <p>Möchtest du diese Einrichtung wirklich löschen?</p>

                <div class="flex justify-end mt-4 gap-4">
                    <x-mary-button type="secondary" @click="showDelete = false" label="Abbrechen" class="btn-primary bg-gray-500 text-white px-4" />
                    <x-mary-button type="danger" wire:click="deleteEinrichtung" label="Löschen" class="btn-warning bg-red-600 text-white px-4" />
                </div>
            </div>
        </div>
    </div>



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
