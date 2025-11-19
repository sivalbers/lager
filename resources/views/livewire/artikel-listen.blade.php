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

            <div class="flex flex-row w-full font-bold text-sky-600 px-1 ">
                <div class="w-1/12 "></div>
                <div class="w-2/12 border-b border-gray-600">Abladestelle</div>
                <div class="w-2/12 border-b border-gray-600">Lagerort</div>
                <div class="w-2/12 border-b border-gray-600">Mindestbestand</div>
                <div class="w-2/12 border-b border-gray-600">Bestellmenge</div>
            </div>

            @foreach ($art->einrichtungen as $einr)
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
                    <div class="w-2/12">{{ $einr->lagerort }}</div>
                    <div class="w-2/12">{{ $einr->mindestbestand }}</div>
                    <div class="w-2/12">{{ $einr->bestellmenge }}</div>
                </div>
            @endforeach
        </div>
    @endforeach

    {{-- Modal: Artikel --}}
    <div x-show="showArtikel" x-cloak class="fixed inset-0 z-10 bg-black/40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-6/12">
            <h2 class="text-xl font-bold mb-4">Artikel {{ $isEditArtikel ? 'ändern' : 'anlegen' }}</h2>

            <div class="flex flex-col gap-2">
                <x-bladewind::input label="Artikel-Nr" wire:model="artikelnr" numeric="{{ $isEditArtikel ? 'false' : 'true' }}" />
                <x-bladewind::input label="Bezeichnung" wire:model="artikelBezeichnung" />
                <x-bladewind::input label="Einheit" wire:model="artikelEinheit" />
                <x-bladewind::input label="Materialgruppe" wire:model="artikelMaterialgruppe" />
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

                <x-bladewind::input label="Mindestbestand" wire:model="mindestbestand" numeric="true" />
                <x-bladewind::input label="Bestellmenge" wire:model="bestellmenge" numeric="true" />

            </div>

            <div class="flex justify-end gap-4 mt-4">
                <x-bladewind::button type="secondary" @click="showEinrichtung = false">Schließen</x-bladewind::button>
                <x-bladewind::button type="primary" wire:click="saveEinrichtung">Speichern</x-bladewind::button>
            </div>
        </div>
    </div>
</div>
