<div x-data="{ showDebitor: @entangle('showDebitor'), showAbladestelle: @entangle('showAbladestelle'), showLagerort: @entangle('showLagerort') }" x-init="Livewire.on('init', () => {
    Alpine.store('livewire', {
        showDebitor: @entangle('showDebitor').defer,
        showAbladestelle: @entangle('showAbladestelle').defer,
        showLagerort: @entangle('showLagerort').defer
    })
});" class="w-5/6 m-auto">

    <h1 class="text-2xl font-bold mt-4">Debitoren</h1>

    <div class="flex justify-end pr-2">
        <x-mary-button wire:click="editDebitor(true)" label="Neuen Debitor anlegen" icon="o-plus-circle" class="btn-primary bg-sky-600 text-white px-4" />
    </div>


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
                        <div>
                            @if ($berechtigung === 'voll')
                                <button title="Neue Abladestelle anlegen" class="h-5 w-5 text-sky-600"
                                    wire:click="editAbladestelle(true, {{ $debitor->nr }})" icon="o-plus-circle">
                                </button>
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
                    <div class="flex flex-row w-full px-1 hover:bg-slate-200" wire:key="abladestelle-{{ $abladestelle->id }}">
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
                                    @if ($berechtigung === 'voll')
                                        <x-mary-button title="Abladestelle kopieren" class="h-5 w-5 text-sky-600"
                                            wire:click="editAbladestelle(true, {{ $debitor->nr }}, {{ $abladestelle->id }} )" icon="o-plus-circle" />

                                        <x-mary-button title="Lagerort anlegen" class="h-5 w-5 text-sky-600"
                                            wire:click="editLagerort(true, {{ $abladestelle->id }} )" icon="o-plus-circle" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($abladestelle->lagerorte as $lagerort)
                    <div class="flex flex-col w-full pl-40 hover:bg-slate-200" wire:key="lagerort-{{ $lagerort->id }}">
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
                            @if ($berechtigung === 'voll')
                                <x-mary-button title="Neue Abladestelle anlegen" class="h-5 w-5 text-sky-600"
                                    wire:click="editAbladestelle(true, {{ $debitor->nr }})" icon="o-plus-circle" />
                            @endif
                        </div>
                    </div>
                </div>
            @endif



        </div>
    @endforeach


    <div x-show="showDebitor" x-cloak class="fixed inset-0 z-10 bg-black/40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-6/12">
            <h2 class="text-xl font-bold mb-4">Debitor {{ $isEditDebitor === true ? 'ändern' : 'anlegen/kopieren' }}
            </h2>

            <div class="flex flex-col w-full ">
                <div class="flex flex-row w-full items-center">
                    <div class="w-2/12 items-center">
                        Nr:
                    </div>
                    <div class="w-10/12">
                        <x-mary-input numeric="true" wire:model="debitorNr" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Name:
                    </div>
                    <div class="w-10/12">
                        <x-mary-input wire:model="debitorName" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Netzregion:
                    </div>
                    <div class="w-10/12">
                        <x-mary-input wire:model="debitorNetzregion" />
                    </div>
                </div>


            </div>


            <div class="flex flex-row justify-end gap-4">
                <x-mary-button type="secondary" @click="showDebitor = false">
                    Schließen
                </x-mary-button>

                <x-mary-button type="primary" wire:click="saveDebitor">
                    Speichern
                </x-mary-button>
            </div>
        </div>
    </div>

    <div x-show="showAbladestelle" x-cloak
        class="fixed inset-0 bg-slate-100/60 z-20 flex items-center justify-center backdrop-blur-[2px]">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-5/12">
            <div class="text-xl font-bold mb-4">Abladestelle
                {{ $isEditAbladestelle === true ? 'ändern' : "anlegen für Debitor: $debitorNr - $debitorName" }}
            </div>

            <div class="flex flex-col w-full ">

                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Name 1:
                    </div>
                    <div class="w-10/12">
                        <input type="hidden" wire:model="abladestelleId" />
                        <x-mary-input wire:model="abladestelleName" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Name 2:
                    </div>
                    <div class="w-10/12">
                        <x-mary-input wire:model="abladestelleName2" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Strasse:
                    </div>
                    <div class="w-10/12">
                        <x-mary-input wire:model="abladestelleStrasse" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 flex items-center">
                        PLZ-Ort:
                    </div>
                    <div class="w-2/12">
                        <x-mary-input wire:model="abladestellePlz" />
                    </div>
                    <div class="w-1/12 text-center">
                        -
                    </div>
                    <div class="w-7/12">
                        <x-mary-input wire:model="abladestelleOrt" />
                    </div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="w-2/12 items-center">
                        Kostenstelle:
                    </div>
                    <div class="w-9/12">
                        <x-mary-input wire:model="abladestelleKostenstelle" />
                    </div>
                </div>

                <div class="flex flex-row items-center">
                    <label>Bestellrhythmus</label>
                    <select wire:model="abladestelleBestellrhythmus"
                        class="w-full border border-gray-300 rounded p-1 disabled:bg-gray-100">
                        <option value="0">Manuell</option>
                        <optgroup label="Montag">
                            <option value="11">1. Montag im Monat</option>
                            <option value="12">2. Montag im Monat</option>
                            <option value="13">3. Montag im Monat</option>
                            <option value="14">4. Montag im Monat</option>
                        </optgroup>

                        <optgroup label="Dienstag">
                            <option value="21">1. Dienstag im Monat</option>
                            <option value="22">2. Dienstag im Monat</option>
                            <option value="23">3. Dienstag im Monat</option>
                            <option value="24">4. Dienstag im Monat</option>
                        </optgroup>

                        <optgroup label="Mittwoch">
                            <option value="31">1. Mittwoch im Monat</option>
                            <option value="32">2. Mittwoch im Monat</option>
                            <option value="33">3. Mittwoch im Monat</option>
                            <option value="34">4. Mittwoch im Monat</option>
                        </optgroup>

                        <optgroup label="Donnerstag">
                            <option value="41">1. Donnerstag im Monat</option>
                            <option value="42">2. Donnerstag im Monat</option>
                            <option value="43">3. Donnerstag im Monat</option>
                            <option value="44">4. Donnerstag im Monat</option>
                        </optgroup>

                        <optgroup label="Freitag">
                            <option value="51">1. Freitag im Monat</option>
                            <option value="52">2. Freitag im Monat</option>
                            <option value="53">3. Freitag im Monat</option>
                            <option value="54">4. Freitag im Monat</option>
                        </optgroup>
                    </select>
                </div>

                <div class="flex flex-row justify-end items-center gap-4">
                    <x-mary-button type="secondary"
                        @click="showAbladestelle = false">Schließen</x-mary-button>
                    <x-mary-button type="primary" wire:click="saveAbladestelle">Speichern</x-mary-button>
                </div>

            </div>


        </div>
    </div>

    <div x-show="showLagerort" x-cloak
        class="fixed inset-0 bg-slate-100/60 z-30 flex items-center justify-center backdrop-blur-[2px]">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-4/12">
            <div class="text-xl font-bold mb-4 border-b pb-2">
                {{ $isEditLagerort ? 'Lagerort bearbeiten' : 'Neuen Lagerort anlegen' }}
                <div class="text-sm font-normal text-gray-600">
                    zur Abladestelle: "{{ $lagerortAbladestelleName }}"
                </div>
            </div>
            <div class="flex flex-col w-full ">
                <div class="flex flex-row items-center">
                    <div class="w-2/12 mr-2">
                        Bezeichnung:
                    </div>
                    <div class="w-10/12">
                        <x-mary-input type="hidden" wire:model="lagerortNr" />
                        <x-mary-input type="hidden" wire:model="lagerortAbladestelleId" />
                        <x-mary-input wire:model="lagerortBezeichnung" />
                    </div>
                </div>

                <div class="flex flex-row justify-end items-center gap-4">
                    <x-mary-button type="secondary"
                        @click="showLagerort = false">Schließen</x-mary-button>
                    <x-mary-button type="primary" wire:click="saveLagerort">Speichern</x-mary-button>
                </div>

            </div>
        </div>
    </div>

</div>
