


<div class="w-[95%] sm:w-5/6 m-auto" x-data>
    <div class="flex flex-row items-center justify-between w-full">
        <div>
            <h1 class="text-2xl font-bold mt-4 mb-6">Protokoll der Artikelbewegungen</h1>
        </div>
    </div>

    <!-- Filter -->
    <div class="space-y-4 mb-4">
        <div class="flex font-bold">
            <div class="flex-[2] pr-2">Suchen</div>
            <div class="flex-[3] pr-2">Datum von</div>
            <div class="flex-[3] pr-2">Datum bis</div>
            <div class="flex-[2] pr-2">Abladestelle</div>
            <div class="flex-[2] pr-2">Lagerort</div>
            <div class="flex-[2]">Buchungsgrund</div>
        </div>

        <!-- Filter Inputs -->
        <div class="flex mb-6 w-full">
            <div class="flex-[2] pr-2">
                <input type="text" wire:model.live.debounce.500ms="search" class="input w-full" placeholder="Suchen...">
            </div>
            <div class="flex-[3] pr-2">
                <input type="date" wire:model.change="dateFrom" class="input w-full">
            </div>
            <div class="flex-[3] pr-2">
                <input type="date" wire:model.change="dateTo" class="input w-full">
            </div>
            <div class="flex-[2] pr-2">
                <select wire:model.change="abladestelle" class="select w-full">
                    <option value="">– alle –</option>
                    @foreach ($abladestellen as $stelle)
                        <option value="{{ $stelle->id }}">{{ $stelle->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-[2] pr-2">
                <select wire:model.change="lagerort" class="select w-full">
                    <option value="">– alle –</option>
                    @foreach ($lagerorte as $ort)
                        <option value="{{ $ort->id }}">{{ $ort->bezeichnung }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-[2]">
                <select wire:model.change="buchungsgrund" class="select w-full">
                    <option value="">– alle –</option>
                    @foreach ($buchungsgruende as $grund)
                        <option value="{{ $grund->id }}">{{ $grund->bezeichnung }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="space-y-2">

        <!-- Kopfzeile -->
        <div class="flex font-bold border-b space-x-2 border-gray-500 pb-2">



            <x-sortdirection field="datum_zeit" header="Datum" :sortField="$sortField" :sortDirection="$sortDirection" />

            <div class="flex-[2]">Mitarbeiter</div>

            <x-sortdirection field="artikelnr" header="Artikelnr." :sortField="$sortField" :sortDirection="$sortDirection" width="flex-[1]" />


            <div class="flex-[3]">Artikelbezeichnung</div>
            <x-sortdirection field="abladestelle_id" header="Abladestelle" :sortField="$sortField" :sortDirection="$sortDirection" width="flex-[2]" />
            <x-sortdirection field="lagerort_id" header="Lagerort" :sortField="$sortField" :sortDirection="$sortDirection" width="flex-[2]" />
            <x-sortdirection field="lagerplatz" header="Lagerplatz" :sortField="$sortField" :sortDirection="$sortDirection" width="flex-[2]" />


            <div class="flex-[1] text-right">Menge</div>

            <x-sortdirection field="buchungsgrund_id" header="Grund" :sortField="$sortField" :sortDirection="$sortDirection" width="flex-[2]" />

        </div>

        <!-- Liste -->
        @forelse ($protokolle as $p)
            <div class="flex space-x-2 text-sm hover:bg-gray-200">
                <div class="flex-[2]">{{ $p->datum_zeit->format('d.m.Y H:i') }}</div>
                <div class="flex-[2]">{{ $p->user?->name }}</div>
                <div class="flex-[1]">{{ $p->artikelnr }}</div>
                <div class="flex-[3]">{{ $p->artikel->bezeichnung }}</div>
                <div class="flex-[2]">{{ $p->abladestelle?->name }}</div>
                <div class="flex-[2]">{{ $p->lagerort?->bezeichnung }}</div>
                <div class="flex-[2]">{{ $p->lagerplatz }}</div>
                <div class="flex-[1] text-right">{{ number_format($p->menge, 0, ',', '.') }}</div>
                <div class="flex-[2]">{{ $p->buchungsgrund?->bezeichnung }}</div>

            </div>
        @empty
            <div class="text-center py-4">Keine Einträge gefunden</div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-4">
            {{ $protokolle->links() }}
        </div>
    </div>
</div>
