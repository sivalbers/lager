

<div class="w-5/6 m-auto"
    x-data="{ showBerechtigung: false }"
    <h1 class="text-2xl font-bold mt-4">Mitarbeiterverwaltung</h1>




<div class="flex justify-between items-center mt-4">

    <div class="w-1/3">
        <x-bladewind::input placeholder="Suche nach Name oder E-Mail" wire:model.live.debounce.500ms="search" />
    </div>
    @if(auth()->user()->hasBerechtigung('mitarbeiter anlegen'))
    <div class="flex pr-2">
        <x-bladewind::button wire:click="editUser(true)">
            Neuen Mitarbeiter anlegen <x-bladewind::icon name="plus-circle" />
        </x-bladewind::button>
    </div>
    @endif
</div>
<div class="pr-2">
        <div class="flex items-center justify-end">
            <label for="showBerechtigung" class="pr-2">Berechtigungen anzeigen</label>
        <input id="showBerechtigung" type="checkbox"  @click="showBerechtigung = ! showBerechtigung" >

        </input>
    </div>
</div>



    <div class="mt-4 border-t border-gray-300 mb-40">
        <div class="flex font-bold text-sky-600 py-2 border-b">
            <div class="w-2/12">Name</div>
            <div class="w-3/12">E-Mail</div>
            <div class="w-2/12">Debitor</div>
            <div class="w-2/12">Abladestelle(n)</div>
            <div class="w-2/12">Rechtegruppe</div>
            <div class="w-1/12"></div>
        </div>

        @foreach ($users as $user)
            <div class="flex py-2 border-b items-center hover:bg-slate-200" wire:key="user-{{ $user->id }}">
                <div class="w-2/12">{{ $user->name }}</div>
                <div class="w-3/12">{{ $user->email }}</div>
                <div class="w-2/12">{{ $user->debitor->nr ?? '-' }} {{ $user->debitor->name ?? '-' }}</div>
                <div class="w-2/12">
                    @foreach ($user->abladestellen as $stelle)
                        <span class="inline-block text-sm bg-gray-100 px-2 py-1 rounded mr-1">{{ $stelle->name }}</span>
                    @endforeach
                </div>
                <div class="w-2/12">{{ $user->rechtegruppe->name ?? '-' }}</div>
                <div class="w-1/12">
                    @if(auth()->user()->hasBerechtigung('mitarbeiter ändern'))
                    <a wire:click="editUser(false, {{ $user->id }})" class="text-sky-600 hover:underline cursor-pointer">✏️</a>
                    @endif
                    @if(auth()->user()->hasBerechtigung('mitarbeiter löschen'))
                    <a wire:click="confirmDelete({{ $user->id }})" class="text-red-500 hover:underline cursor-pointer">🗑️</a>
                    @endif
                </div>

            </div>
            <div class="ml-12 flex flex-row bg-gray-200 mb-5 p-2" x-show="showBerechtigung">
                <div class="mr-2">
                Berechtigungen:
                </div>
                <div class="">
                    @foreach($user->berechtigungen as $berechtigung)
                        <span class="inline-block  text-gray-800 text-sm px-2 py-1 rounded mr-1">{{ $berechtigung->bezeichnung }}</span>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>


    <div x-show="showBerechtigung" class="absolute border rounded-md shadow-md border-gray-300 mt-6 bg-gray-100 w-4/12 right-[10%] text-xs mb-10">
        <div class="rounded-md text-sm font-bold p-6 bg-gray-50 border-b">Legende der Berechtigungen:</div>
        <div class="grid grid-cols-2 gap-2 pl-6 pr-6 pt-2 pb-4">
            @foreach($legende as $berechtigung)

                    <div class="w-2/8 border-b">{{ $berechtigung->bezeichnung }}</div>
                    <div class="w-6/6 border-b">{{ $berechtigung->kommentar }}</div>

            @endforeach
        </div>
    </div>

    <div x-data="{ showUser: @entangle('showUser') }">
        <div x-show="showUser" x-cloak class="fixed inset-0 bg-black/40 z-10 flex justify-center items-center">
            <div class="bg-white p-6 rounded shadow w-5/12">
                <form method="POST" >
                    @csrf
                    <h2 class="text-xl font-bold mb-4">{{ $isEditUser ? 'Mitarbeiter bearbeiten' : 'Neuer Mitarbeiter' }}</h2>

                    <div class="space-y-4">
                        <x-bladewind::input label="Name" wire:model="name" />
                        @error('name')
                            <div class="w-2/3 md:w-full lg:w-2/3 text-red-500 text-left">
                                {{ $message }}
                            </div>
                        @enderror


                        <x-bladewind::input label="E-Mail-Adresse" wire:model="email" type="email" />
                        @error('email')
                            <div class="w-2/3 md:w-full lg:w-2/3 text-red-500 text-left">
                                {{ $message }}
                            </div>
                        @enderror

                        @if (!$isEditUser)
                            <x-bladewind::input label="Passwort" wire:model="password" type="password" />
                        @endif
                        @error('password')
                            <div class="w-2/3 md:w-full lg:w-2/3 text-red-500 text-left">
                                {{ $message }}
                            </div>
                        @enderror

                        <div>
                            <label>Debitor</label>
                            <select wire:model.live="debitor_nr" class="w-full border border-gray-300 rounded p-1">
                                <option value="">-</option>
                                @foreach($debitors as $debitor)
                                    <option value="{{ $debitor->nr }}">{{ $debitor->name }} ({{ $debitor->nr }})</option>
                                @endforeach
                            </select>
                        @error('debitor_nr')
                            <div class="w-2/3 md:w-full lg:w-2/3 text-red-500 text-left">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                        <div>
                            <label>Abladestelle</label>
                            <select name="abladestelle_ids"
                                    wire:model="abladestelle_ids"
                                    wire:key="abladestelle-select-{{ implode('-', $abladestelle_ids) }}"
                                    multiple
                                    class="w-full border border-gray-300 rounded p-1">
                                @foreach($abladestellen as $stelle)
                                    <option value="{{ $stelle->id }}">{{ $stelle->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div>
                            <label>Rechtegruppe</label>
                            <select name="rechtegruppe_id" wire:model="rechtegruppe_id" class="w-full border border-gray-300 rounded p-1">
                                <option value="">-</option>
                                @foreach($rechtegruppen as $gruppe)
                                    <option value="{{ $gruppe->id }}">{{ $gruppe->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-4 mt-4">
                            <x-bladewind::button type="secondary" @click="showUser = false">Schließen</x-bladewind::button>
                            <x-bladewind::button type="primary" wire:click="saveUser">Speichern</x-bladewind::button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <div x-data="{ showDelete: @entangle('confirmingDelete') }">
        <div x-show="showDelete" x-cloak class="fixed inset-0 bg-black/40 z-20 flex justify-center items-center">
            <div class="bg-white p-6 rounded shadow-md w-4/12">
                <h2 class="text-lg font-bold mb-2 text-red-600">Löschen bestätigen</h2>
                <p>Möchtest du diesen Benutzer wirklich löschen?</p>

                <div class="flex justify-end mt-4 gap-4">
                    <x-bladewind::button type="secondary" @click="showDelete = false">Abbrechen</x-bladewind::button>
                    <x-bladewind::button type="danger" wire:click="deleteUser">Löschen</x-bladewind::button>
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
