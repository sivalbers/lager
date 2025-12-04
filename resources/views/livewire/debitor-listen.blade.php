<div x-data="{ showDebitor: @entangle('showDebitor'), showAbladestelle: @entangle('showAbladestelle'), showLagerort: @entangle('showLagerort') }" x-init="Livewire.on('init', () => {
    Alpine.store('livewire', {
        showDebitor: @entangle('showDebitor').defer,
        showAbladestelle: @entangle('showAbladestelle').defer,
        showLagerort: @entangle('showLagerort').defer
    })
});" class="w-5/6 m-auto">

    <h1 class="text-2xl font-bold mt-4">Debitoren</h1>

    @if (auth()->user()->hasBerechtigung('debitor anlegen'))
        <div class="flex justify-end pr-2">
            <x-mary-button wire:click="editDebitor(true)" label="Neuen Debitor anlegen" icon="o-plus-circle"
                class="btn-primary bg-sky-600 text-white px-4" />
        </div>
    @endif


    <div class="flex flex-col w-full border-b border-gray-600">
        <div class="flex flex-row font-bold text-sky-600 border-b border-sky-600 px-1">
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
        <div class="flex flex-col pb-6" wire:key="debitor-{{ $debitor->nr }}">
            <div class="flex flex-row bg-slate-300 px-1 hover:bg-slate-200">
                <div class="w-1/12">
                    <a wire:click="editDebitor(false, {{ $debitor->nr }})"
                        class="cursor-pointer hover:underline text-sky-600">{{ $debitor->nr }}</a>
                </div>
                <div class="w-8/12">
                    {{ $debitor->name }}
                </div>
                <div class="w-3/12">
                    {{ $debitor->netzregion }}
                </div>
            </div>



            @if (!$debitor->abladestellen->isEmpty())
                <div class="flex flex-row w-full bg-slate-200 px-1">
                    <div class="w-full flex justify-between text-xs text-gray-500">
                        <div>
                            Abladestellen:
                        </div>
                        <div class="">
                            @if (auth()->user()->hasBerechtigung('debitor anlegen'))
                                <x-mary-button title="Neue Abladestelle anlegen" class="h-5 w-5 text-sky-600"
                                    wire:click="editAbladestelle(true, {{ $debitor->nr }})" icon="o-plus-circle" />
                            @else
                                <div>NO</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-row w-full font-bold text-sky-600 px-1">
                    <div class="w-1/12">

                    </div>
                    <div class="w-2/12 border-b border-sky-600">
                        Name 1
                    </div>
                    <div class="w-2/12 border-b border-sky-600">
                        Name 2
                    </div>
                    <div class="w-2/12 border-b border-sky-600">
                        Strasse
                    </div>
                    <div class="w-3/12 border-b border-sky-600">
                        PLZ-Ort
                    </div>
                    <div class="w-1/12 border-b border-sky-600">
                        Liefer-Rythmus
                    </div>
                    <div class="w-1/12 border-b border-sky-600">
                        N-Lieferung
                    </div>

                </div>


                @foreach ($debitor->abladestellen as $abladestelle)
                    <div class="flex flex-row w-full px-1 hover:bg-slate-200"
                        wire:key="abladestelle-{{ $abladestelle->id }}">
                        <div class="w-1/12">

                        </div>
                        <div class="w-2/12">
                            <a wire:click="editAbladestelle(false, {{ $debitor->nr }}, {{ $abladestelle->id }})"
                                class="cursor-pointer hover:underline text-sky-600">
                                {{ $abladestelle->name }}
                            </a>
                        </div>
                        <div class="w-2/12">
                            {{ $abladestelle->name2 }}
                        </div>
                        <div class="w-2/12">
                            {{ $abladestelle->strasse }}
                        </div>
                        <div class="w-3/12">
                            <div>
                                {{ $abladestelle->plz }} {{ $abladestelle->ort }}
                            </div>
                        </div>



                        <div class="w-1/12">{{ bestellrhythmus_text($abladestelle->bestellrhythmus) }}</div>
                        <div class="w-1/12">

                            <div class="flex flex-row justify-between">
                                <div>{{ $abladestelle->naechstes_belieferungsdatum?->format('d.m.Y') ?? '-' }}</div>
                                <div>
                                    @if (auth()->user()->hasBerechtigung('debitor anlegen'))
                                        <x-mary-button title="Abladestelle kopieren" class="h-5 w-5 text-sky-600"
                                            wire:click="editAbladestelle(true, {{ $debitor->nr }}, {{ $abladestelle->id }} )"
                                            icon="o-plus-circle" />

                                        <x-mary-button title="Lagerort anlegen" class="h-5 w-5 text-sky-600"
                                            wire:click="editLagerort(true, {{ $abladestelle->id }} )"
                                            icon="o-plus-circle" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($abladestelle->lagerorte as $lagerort)
                        <div class="flex flex-col w-full pl-40 hover:bg-slate-200"
                            wire:key="lagerort-{{ $lagerort->id }}">
                            <div class="w-80 ">

                                <a title="Lagerort ändern" class="h-5 w-5 text-sky-600 cursor-pointer"
                                    wire:click="editLagerort(false, {{ $lagerort->id }} )">
                                    - {{ $lagerort->bezeichnung }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @else
                <div class="flex flex-row w-full bg-slate-200 px-1">
                    <div class="w-full flex justify-between text-xs text-gray-500">
                        <div>
                            Keine Abladestellen vorhanden.
                        </div>
                        <div>
                            @if (auth()->user()->hasBerechtigung('debitor anlegen'))
                                <x-mary-button title="Neue Abladestelle anlegen" class="h-5 w-5 text-sky-600"
                                    wire:click="editAbladestelle(true, {{ $debitor->nr }})" icon="o-plus-circle" />
                            @endif
                        </div>
                    </div>
                </div>
            @endif



        </div>
    @endforeach


    <x-mary-modal wire:model="showDebitor"
        title="Debitor {{ $isEditDebitor === true ? 'ändern' : 'anlegen/kopieren' }}"
        class="backdrop-blur" box-class="w-11/12 max-w-3xl">



        <x-mary-input label="Nr." numeric="true" wire:model="debitorNr" class="!outline-none focus:!outline-none focus:!ring-0" />
        <x-mary-input label="Name" wire:model="debitorName" class="!outline-none focus:!outline-none focus:!ring-0" />
        <x-mary-input label="Netzregion" wire:model="debitorNetzregion" class="!outline-none focus:!outline-none focus:!ring-0" />


        <x-slot:actions>
            <x-mary-button type="secondary" @click="showDebitor = false" label="Schliessen"  class="bg-gray-500 text-white px-4 mr-4" />
            <x-mary-button label="Speichern" class="btn-primary" wire:click="saveDebitor"  class="bg-sky-600 text-white px-4" />
        </x-slot:actions>

    </x-mary-modal>






    <x-mary-modal wire:model="showAbladestelle"
        title="Abladestelle {{ $isEditAbladestelle === true ? 'ändern' : 'anlegen für Debitor: ' }} {{ $debitorNr }} {{ $debitorName }}"
        class="backdrop-blur" box-class="w-11/12 max-w-3xl">


        @php
            $grouped = [
                'Montag' => [
                    ['id' => 11, 'name' => '1. Montag im Monat'],
                    ['id' => 12, 'name' => '2. Montag im Monat'],
                    ['id' => 13, 'name' => '3. Montag im Monat'],
                    ['id' => 14, 'name' => '4. Montag im Monat'],
                ],
                'Dienstag' => [
                    ['id' => 21, 'name' => '1. Dienstag im Monat'],
                    ['id' => 22, 'name' => '2. Dienstag im Monat'],
                    ['id' => 23, 'name' => '3. Dienstag im Monat'],
                    ['id' => 24, 'name' => '4. Dienstag im Monat'],
                ],
                'Mittwoch' => [
                    ['id' => 31, 'name' => '1. Mittwoch im Monat'],
                    ['id' => 32, 'name' => '2. Mittwoch im Monat'],
                    ['id' => 33, 'name' => '3. Mittwoch im Monat'],
                    ['id' => 34, 'name' => '4. Mittwoch im Monat'],
                ],
                'Donnerstag' => [
                    ['id' => 41, 'name' => '1. Donnerstag im Monat'],
                    ['id' => 42, 'name' => '2. Donnerstag im Monat'],
                    ['id' => 43, 'name' => '3. Donnerstag im Monat'],
                    ['id' => 44, 'name' => '4. Donnerstag im Monat'],
                ],
                'Freitag' => [
                    ['id' => 51, 'name' => '1. Freitag im Monat'],
                    ['id' => 52, 'name' => '2. Freitag im Monat'],
                    ['id' => 53, 'name' => '3. Freitag im Monat'],
                    ['id' => 54, 'name' => '4. Freitag im Monat'],
                ],
            ];
        @endphp

        <div class="flex flex-col w-full ">

            <x-mary-input type="hidden" wire:model="abladestelleId" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-input label="Name 1" inline wire:model="abladestelleName" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-input label="Name 2" wire:model="abladestelleName2" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-input label="Strasse" wire:model="abladestelleStrasse" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-input label="PLZ-Ort" wire:model="abladestellePlz" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-input label="Ort" wire:model="abladestelleOrt" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-input label="kostenstelle" wire:model="abladestelleKostenstelle" class="!outline-none focus:!outline-none focus:!ring-0" />
            <x-mary-select-group label="Bestellrythmus" :options="$grouped" wire:model="abladestelleBestellrhythmus" class="!outline-none focus:!outline-none focus:!ring-0" />


        </div>

    <x-slot:actions>
        <x-mary-button type="secondary" @click="showAbladestelle = false" label="Schliessen"  class="bg-gray-500 text-white px-4 mr-4" />
        <x-mary-button label="Speichern" class="btn-primary" wire:click="saveAbladestelle"  class="bg-sky-600 text-white px-4" />
    </x-slot:actions>

    </x-mary-modal>


    <x-mary-modal wire:model="showLagerort"
        title="{{ $isEditLagerort ? 'Lagerort bearbeiten' : 'Neuen Lagerort anlegen' }}"
        class="backdrop-blur" box-class="w-11/12 max-w-3xl">

            <div class="text-sm font-normal text-gray-600">
                zur Abladestelle: "{{ $lagerortAbladestelleName }}"
            </div>
        <x-mary-input type="text" hidden wire:model="lagerortId" class="h-0" />
        <x-mary-input type="text" hidden wire:model="lagerortAbladestelleId"  class="h-0" />

        <x-mary-input label="Bezeichnung" wire:model="lagerortBezeichnung" class="!outline-none focus:!outline-none focus:!ring-0" />

        <x-slot:actions>
            <x-mary-button type="secondary" @click="showLagerort = false" label="Schliessen"  class="bg-gray-500 text-white px-4 mr-4" />
            <x-mary-button label="Speichern" class="btn-primary" wire:click="saveLagerort"  class="bg-sky-600 text-white px-4" />
        </x-slot:actions>

    </x-mary-modal>


</div>
