<div x-data="{ showArtikel: @entangle('showArtikel'), showEinrichtung: @entangle('showEinrichtung') }" class="w-5/6 m-auto">

    <h1 class="text-2xl font-bold mt-4">Artikelverwaltung</h1>

    @if(auth()->user()->hasBerechtigung('artikel anlegen'))
    <div class="flex justify-end pr-2">
        <x-bladewind::button wire:click="editArtikel(true)">
            Neuen Artikel anlegen <x-bladewind::icon name="plus-circle" />
        </x-bladewind::button>
    </div>
    @endif

    <div class="flex flex-col w-full border-b border-gray-600 mt-2">
        <div class="flex flex-row font-bold text-sky-600 border-b border-sky-600 px-1">
            <div class="w-2/12">Artikel-Nr</div>
            <div class="w-5/12">Bezeichnung</div>
            <div class="w-2/12">Einheit</div>
            <div class="w-3/12">Materialgruppe</div>
        </div>
    </div>

    @foreach ($artikel as $art)
        <div class="flex flex-col pb-6" wire:key="artikel-{{ $art->artikelnr }}">
            <div class="flex flex-row bg-slate-300 px-1">
                <div class="w-2/12">
                    @if(auth()->user()->hasBerechtigung('artikel ändern'))
                        <a href="#" wire:click="editArtikel(false, '{{ $art->artikelnr }}')" class="hover:underline text-sky-600">{{ $art->artikelnr }}</a>
                    @else
                        {{ $art->artikelnr }}
                    @endif
                </div>
                <div class="w-5/12">{{ $art->bezeichnung }}</div>
                <div class="w-2/12">{{ $art->einheit }}</div>
                <div class="w-3/12">{{ $art->materialgruppe }}</div>
            </div>

            <div class="flex flex-row w-full bg-slate-200 px-1">
                <div class="w-full flex justify-between text-xs text-gray-500">
                    <div>Einrichtungen:</div>
                    @if(auth()->user()->hasBerechtigung('artikel anlegen'))
                    <div>
                        <button title="Neue Einrichtung anlegen" class="h-5 w-5 text-sky-600"
                            wire:click="editEinrichtung(true, null, '{{ $art->artikelnr }}')">
                            <x-bladewind::icon name="plus-circle" />
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            @if (count($art->einrichtungen) > 0 )
                <div class="flex justify-between">
                    <div class="flex flex-row w-full font-bold text-sky-600 px-1 ">
                        <div class="w-1/12 "></div>
                        <div class="w-2/12 border-b border-gray-600">Abladestelle</div>
                        <div class="w-2/12 border-b border-gray-600">Lagerort</div>
                        <div class="w-2/12 border-b border-gray-600">Mindestbestand</div>
                        <div class="w-2/12 border-b border-gray-600">Bestellmenge</div>
                    </div>
                    <div class=" border-gray-600 w-10"></div>
                </div>
            @endif


            @foreach ($art->einrichtungen as $einr)
                <div class="flex justify-between hover:bg-slate-100">
                    <div class="flex flex-row w-full px-1 text-sm" wire:key="einrichtung-{{ $einr->id }}">
                        <div class="w-1/12"></div>
                        <div class="w-2/12">
                            @if(auth()->user()->hasBerechtigung('artikel ändern'))
                            <a href="#" wire:click="editEinrichtung(false, {{ $einr->id }})" class="hover:underline text-sky-600">
                                {{ $einr->abladestelle->name ?? '-' }}
                            </a>
                            @else
                                {{ $einr->abladestelle->name ?? '-' }}
                            @endif
                        </div>
                        <div class="w-2/12">{{ $einr->lagerort?->bezeichnung ?? '-' }}</div>
                        <div class="w-2/12">{{ $einr->mindestbestand }}</div>
                        <div class="w-2/12">{{ $einr->bestellmenge }}</div>
                    </div>

                    <div class="flex justify-end w-10">
                        @if(auth()->user()->hasBerechtigung('artikel löschen'))
                            <a wire:click="confirmDelete({{ $einr->id }})" class="text-red-500 hover:underline cursor-pointer">🗑️</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    {{-- Modal: Artikel --}}
    <div x-show="showArtikel" key="{{ now() }}" x-cloak class="fixed inset-0 z-10 bg-black/40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-6/12">
            <h2 class="text-xl font-bold mb-4">Artikel {{ $isEditArtikel ? 'ändern' : 'anlegen' }} => {{ $artikelnr }} </h2>

            <div class="flex flex-col gap-2">
                <div class="flex flex-row items-center bg-red-100">
        <div x-data="{ nr: '{{ $artikelnr }}' }">
        <input x-model="nr" type="text" />
                <x-bladewind::button
                @click="$wire.set('artikelnr', nr).then(() => $wire.loadFromFaveo(nr))">
                Artikel holen
                </x-bladewind::button>
            </div>

                </div>
                <x-bladewind::input label="Bezeichnung" wire:model="artikelBezeichnung" />
                <x-bladewind::input label="Einheit" wire:model="artikelEinheit" />
                <x-bladewind::input label="Materialgruppe" wire:model="artikelMaterialgruppe" />
                <x-bladewind::input label="EK-Preis (€)" wire:model="artikelEkpreis" numeric="true" with_dots="true" />
            </div>

            <div class="flex justify-end gap-4 mt-4">
                <x-bladewind::button type="secondary" @click="showArtikel = false">Schließen</x-bladewind::button>
                <x-bladewind::button type="primary" wire:click="saveArtikel">Speichern</x-bladewind::button>
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

            <div x-data="{ spezifisch: @entangle('abladestellenspezifisch') }" class="grid grid-cols-2 gap-4 align-bottom">
                <div class="col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" x-model="spezifisch" class="mr-2"> Abladestellenspezifisch
                    </label>
                </div>
                <div>
                    <label>Abladestelle</label>
                    <select wire:model="abladestelle_id" class="w-full border border-gray-300 rounded p-1 disabled:bg-gray-100"
                        x-bind:disabled="!spezifisch">
                        <option value="0">Bitte wählen</option>

                        @foreach ($abladestellen as $stelle)
                            <option value="{{ $stelle->id }}">{{ $stelle->name }}</option>
                        @endforeach
                    </select>
                </div>

                <x-bladewind::input label="Lagerort" wire:model="lagerort" x-bind:disabled="!spezifisch" />

                <div>
                    <label>Lagerort</label>
                    <select wire:model="lagerort_id" class="w-full border border-gray-300 rounded p-1 disabled:bg-gray-100"
                        x-bind:disabled="!spezifisch">
                        <option value="0">Bitte wählen</option>

                        @foreach ($lagerortAuswahl as $lagerort)
                            <option value="{{ $lagerort->id }}">{{ $lagerort->bezeichnung }}</option>
                        @endforeach
                    </select>
                </div>

                <x-bladewind::input label="Mindestbestand" wire:model="mindestbestand" numeric="true" />
                <x-bladewind::input label="Bestellmenge" wire:model="bestellmenge" numeric="true" />
            </div>

            <div class="flex justify-end gap-4 mt-4">
                <x-bladewind::button type="secondary" @click="showEinrichtung = false">Schließen</x-bladewind::button>
                <x-bladewind::button type="primary" wire:click="saveEinrichtung">Speichern</x-bladewind::button>
            </div>
        </div>
    </div>

    <div x-data="{ showDelete: @entangle('confirmingDelete') }">
        <div x-show="showDelete" x-cloak class="fixed inset-0 bg-black/40 z-20 flex justify-center items-center">
            <div class="bg-white p-6 rounded shadow-md w-4/12">
                <h2 class="text-lg font-bold mb-2 text-red-600">Löschen bestätigen</h2>
                <p>Möchtest du diese Einrichtung wirklich löschen?</p>

                <div class="flex justify-end mt-4 gap-4">
                    <x-bladewind::button type="secondary" @click="showDelete = false">Abbrechen</x-bladewind::button>
                    <x-bladewind::button type="danger" wire:click="deleteEinrichtung">Löschen</x-bladewind::button>
                </div>
            </div>
        </div>
    </div>



    <div wire:loading class="fixed inset-0 z-50 bg-white bg-opacity-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="mt-2 text-gray-700 text-sm text-center">Lade Daten...</p>
        </div>
    </div>
</div>
