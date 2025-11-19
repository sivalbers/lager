<div x-data="{ showPsp: @entangle('showPsp') }" class="w-5/6 m-auto">

    <h1 class="text-2xl font-bold mt-4">PSP Ermittlung</h1>

    <div class="flex justify-end pr-2">
        <x-bladewind::button wire:click="editPsp(true)">
            Neues PSP anlegen <x-bladewind::icon name="plus-circle" />
        </x-bladewind::button>
    </div>

    <div class="flex flex-col w-full border-b border-gray-600">
        <div class="flex flex-row font-bold text-sky-600 border-b border-sky-600 px-1">

            <div class="w-1/12">Netzregion</div>
            <div class="w-1/12">Kostenstelle</div>
            <div class="w-1/12">Artikel</div>
            <div class="w-1/12">Materialgruppe</div>
            <div class="w-2/12">Format</div>
            <div class="w-6/12">Beschreibung</div>
        </div>
    </div>

    @foreach ($psps as $psp)
        <div class="flex flex-col pb-4" wire:key="psp-{{ $psp->id }}">
            <div class="flex flex-row bg-slate-300 px-1">
                <div class="w-1/12">
                    <a href="#" wire:click="editPsp(false, {{ $psp->id }})"
                       class="hover:underline text-sky-600">{{ $psp->netzregion }}</a>
                </div>
                <div class="w-1/12">{{ $psp->kostenstelle }}</div>
                <div class="w-1/12">{{ $psp->artikel }}</div>
                <div class="w-1/12">{{ $psp->materialgruppe }}</div>
                <div class="w-2/12">{{ $psp->format }}</div>
                <div class="w-6/12">{{ $psp->beschreibung }}</div>
            </div>

            @if (false)
                <div class="bg-slate-200 text-sm text-gray-700 px-2 py-1">
                    {{ $psp->beschreibung }}
                </div>
            @endif
        </div>
    @endforeach

        <form>
            <div class="flex flex-col w-full space-y-1 border rounded-md p-4" wire:key="psp-test-form">
                <div class="flex items-center">
                    <div class="w-3/12 font-bold text-2xl">PSP-Selektion Testen:</div>
                </div>
                <div class="flex items-center">
                    <div class="w-3/12">Netzregion:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspTestNetzregion" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Kostenstelle:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspTestKostenstelle" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Artikel:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspTestArtikel" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Materialgruppe:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspTestMaterialgruppe" /></div>
                </div>


            </div>

            <div class="flex flex-row justify-end gap-4 mt-6">

                <x-bladewind::button type="primary" wire:click="testFindPsp()">Test ausführen</x-bladewind::button>
            </div>
        </form>

        @if ($pspTestGefunden)
            <div class="mt-4 p-4 border border-green-500 bg-green-100 rounded-md">
                <h2 class="text-lg font-bold mb-2 text-green-700">PSP-Datensatz gefunden:</h2>
                <p><strong>Netzregion:</strong> {{ $pspTestGefunden->netzregion }}</p>
                <p><strong>Kostenstelle:</strong> {{ $pspTestGefunden->kostenstelle }}</p>
                <p><strong>Artikel:</strong> {{ $pspTestGefunden->artikel }}</p>
                <p><strong>Materialgruppe:</strong> {{ $pspTestGefunden->materialgruppe }}</p>
                <p><strong>Format:</strong> {{ $pspTestGefunden->format }}</p>
                <p><strong>Beschreibung:</strong> {{ $pspTestGefunden->beschreibung }}</p>
            </div>
        @else
            <div class="mt-4 p-4 border border-red-500 bg-red-100 rounded-md">
                <h2 class="text-lg font-bold mb-2 text-red-700">Kein PSP-Datensatz gefunden.</h2>
            </div>
        @endif

    <!-- Modal -->
    <div x-show="showPsp" x-cloak class="fixed inset-0 z-10 bg-black/40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-gray-500 shadow-md w-6/12">
            <h2 class="text-xl font-bold mb-4">PSP {{ $isEditPsp ? 'ändern' : 'anlegen' }}</h2>

            <div class="flex flex-col w-full space-y-3">
                <div class="flex items-center">
                    <div class="w-3/12">Netzregion:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspNetzregion" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Kostenstelle:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspKostenstelle" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Artikel:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspArtikel" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Materialgruppe:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspMaterialgruppe" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Format:</div>
                    <div class="w-9/12"><x-bladewind::input wire:model="pspFormat" /></div>
                </div>

                <div class="flex items-center">
                    <div class="w-3/12">Beschreibung:</div>
                    <div class="w-9/12">
                        <x-bladewind::textarea wire:model="pspBeschreibung" rows="4" />
                    </div>
                </div>
            </div>

            <div class="flex flex-row justify-end gap-4 mt-6">
                <x-bladewind::button type="secondary" @click="showPsp = false">Schließen</x-bladewind::button>
                <x-bladewind::button type="primary" wire:click="savePsp">Speichern</x-bladewind::button>
            </div>
        </div>
    </div>
</div>
