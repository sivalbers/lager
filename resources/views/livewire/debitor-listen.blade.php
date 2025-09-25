<div x-data="{ showDebitor: @entangle('showDebitor'), showAbladestelle: @entangle('showAbladestelle'), showLagerort: @entangle('showLagerort') }" x-init="Livewire.on('init', () => {
    Alpine.store('livewire', {
        showDebitor: @entangle('showDebitor').defer,
        showAbladestelle: @entangle('showAbladestelle').defer,
        showLagerort: @entangle('showLagerort').defer
    })
});" class="w-5/6 m-auto">
    <h1 class="text-2xl font-bold mt-4">Debitoren</h1>

    <div class="flex justify-end pr-2">
        <x-bladewind::button wire:click="editDebitor(true)">
            Neuen Debitor anlegen <x-bladewind::icon name="plus-circle" />
        </x-bladewind::button>
    </div>


    <div class="flex flex-col w-full border-b border-gray-600">
        <div class="flex flex-row text-sky-600 border-b border-sky-600 px-1">
            <div class="w-1/12">
                Debitor-Nr
            </div>
            <div class="flex w-8/12">
                Name
            </div>
            <div class="w-3/12">
                Netzregion
            </div>
        </div>
    </div>

    @foreach ($debitoren as $debitor)
        <div class="flex flex-col pb-6  ">
            <div class="flex flex-row bg-slate-300 px-1">
                <div class="w-1/12">
                    <a href="#" wire:click="editDebitor(false, {{ $debitor->nr }})">{{ $debitor->nr }}</a>
                </div>
                <div class="w-8/12">
                    {{ $debitor->name }}
                </div>
                <div class="w-3/12">
                    {{ $debitor->netzregion }}
                </div>
            </div>

            <div class="flex flex-row w-full bg-slate-200 px-1">
                <div class="w-full">
                    Abladestellen:
                </div>
            </div>
            <div class="flex flex-row w-full text-sky-600 px-1">
                <div class="w-1/12">

                </div>
                <div class="w-2/12 border-b border-sky-600">
                    Name 1
                </div>
                <div class="w-3/12 border-b border-sky-600">
                    Name 2
                </div>
                <div class="w-3/12 border-b border-sky-600">
                    Strasse
                </div>
                <div class="w-3/12 border-b border-sky-600">
                    PLZ-Ort
                </div>
            </div>


            @if (!empty($debitor))
                @foreach ($debitor->abladestellen as $abladestelle)
                    <div class="flex flex-row w-full px-1">
                        <div class="w-1/12">

                        </div>
                        <div class="w-2/12">
                            {{ $abladestelle->name1 }}
                        </div>
                        <div class="w-3/12">
                            {{ $abladestelle->name2 }}
                        </div>
                        <div class="w-3/12">
                            {{ $abladestelle->strasse }}
                        </div>
                        <div class="w-3/12">
                            {{ $abladestelle->plz }} {{ $abladestelle->ort }}
                        </div>
                    </div>
                @endforeach
            @endif



        </div>
    @endforeach


    <div x-show="showDebitor" x-cloak class="fixed inset-0 z-10 bg-black/40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-6/12">
            <h2 class="text-xl font-bold mb-4">Debitor {{ $isEditDebitor === true ? 'ändern' : 'anlegen' }}</h2>

            <div class="flex flex-col w-full ">
                <div class="flex flex-row w-full items-center">
                    <div class="w-2/12 items-center">
                        Nr:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input numeric="true" wire:model="debitorNr" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Name:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="debitorName" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Netzregion:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="debitorNetzregion" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-start">
                        Abladestelle:
                    </div>
                    <div class="w-8/12 flex flex-col">
                        @php
                            
                        @endphp
                        @if ($edDebitor && $edDebitor->abladestellen->count() > 0)
<x-bladewind::select
    name="abladestelle"
    placeholder="Liste aller Abladestellen"
    searchable="true"
    :data="$edDebitor->abladestellen->map(fn($a) => [
        'label' => $a->lagerort . ' ' . $a->name1 . ' ' . $a->name2,
        'value' => $a->debitor_nr,  {{-- oder $a->id prüfen --}}
    ])->toArray()"
/>


                        @else
                            <p class="text-gray-500">Keine Abladestellen vorhanden</p>
                        @endif
                    </div>
                    <div class="w-2/12 -mt-4 px-2">
                        <x-bladewind::button title="Neue Abladestelle anlegen"
                            wire:click="editAbladestelle(true)"><x-bladewind::icon
                                name="plus-circle" /></x-bladewind::button>
                    </div>

                </div>
            </div>


            <div class="flex flex-row justify-end gap-4">
                <x-bladewind::button type="secondary" @click="showDebitor = false">
                    Schließen
                </x-bladewind::button>

                <x-bladewind::button type="primary" wire:click="saveDebitor">
                    Speichern
                </x-bladewind::button>
            </div>
        </div>
    </div>

    <div x-show="showAbladestelle" x-cloak
        class="fixed inset-0 bg-slate-100/60 z-20 flex items-center justify-center backdrop-blur-[2px]">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-5/12">
            <div class="text-xl font-bold mb-4"">Abladestelle
                {{ $isEditAbladestelle === true ? 'ändern' : 'anlegen' }}</div>

            <div class="flex flex-col w-full ">
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Abladestelle-ID:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="abladestelleId" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Name 1:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="abladestelleName1" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Name 2:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="abladestelleName2" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Strasse:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="abladestelleStrasse" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 flex items-center">
                        PLZ-Ort:
                    </div>
                    <div class="w-2/12">
                        <x-bladewind::input wire:model="abladestellePlz" />
                    </div>
                    <div class="w-1/12 text-center">
                        -
                    </div>
                    <div class="w-7/12">
                        <x-bladewind::input wire:model="abladestelleOrt" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Lagerort:
                    </div>
                    <div class="w-9/12">
                        <x-bladewind::input wire:model="abladestelleLagerort" />
                    </div>
                    <div class="w-1/12 -mt-4 px-2">
                        <x-bladewind::button title="Neuen Lagerort anlegen"
                            wire:click="editLagerort(true)"><x-bladewind::icon
                                name="plus-circle" /></x-bladewind::button>
                    </div>
                </div>

                <div class="flex flex-row justify-end items-center gap-4">
                    <x-bladewind::button type="secondary"
                        @click="showAbladestelle = false">Schließen</x-bladewind::button>
                    <x-bladewind::button type="primary" wire:click="saveAbladestelle">Speichern</x-bladewind::button>
                </div>

            </div>


        </div>
    </div>

    <div x-show="showLagerort" x-cloak
        class="fixed inset-0 bg-slate-100/60 z-30 flex items-center justify-center backdrop-blur-[2px]">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-4/12">
            <div class="text-xl font-bold mb-4"">Lagerort {{ $isEditLagerort === true ? 'ändern' : 'anlegen' }}
            </div>
            <div class="flex flex-col w-full ">
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Lagerort-Nr:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="lagerortNr" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Bezeichnung:
                    </div>
                    <div class="w-10/12">
                        <x-bladewind::input wire:model="lagerortBezeichnung" />
                    </div>
                </div>

                <div class="flex flex-row justify-end items-center gap-4">
                    <x-bladewind::button type="secondary"
                        @click="showLagerort = false">Schließen</x-bladewind::button>
                    <x-bladewind::button type="primary" wire:click="saveLagerort">Speichern</x-bladewind::button>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
