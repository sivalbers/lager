<div x-data="{ showModal: @entangle('showModal') }" class="p-6">
    <h1 class="text-2xl font-bold mb-4">Einkaufsliste ** Funktion ist in Arbeit - bisher nicht funktionstüchtig **</h1>

    <div class="flex justify-end mb-4">
    </div>

    <table class="w-full text-sm">
        <thead class="font-bold text-left border-b border-gray-500 text-sky-600">
            <tr>
                <th>Artikelnr</th>
                <th>Menge</th>
                <th>Kommentar</th>
                <th>Abladestelle</th>
                <th>Lagerort</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
        @foreach($einkaufslisten as $eintrag)
            <tr class="border-b">
                <td>{{ $eintrag->artikelnr }}</td>
                <td>{{ $eintrag->menge }}</td>
                <td>{{ $eintrag->kommentar }}</td>
                <td>{{ $eintrag->abladestelle->name ?? '-' }}</td>
                <td>{{ $eintrag->lagerort->bezeichnung ?? '-' }}</td>
                <td>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center">
        <div class="bg-white p-6 rounded w-1/2">
            <h2 class="text-xl font-bold mb-4">Einkauf {{ $einkaufId ? 'bearbeiten' : 'anlegen' }}</h2>

            <div class="space-y-4">
            </div>

            <div class="flex justify-end mt-4 space-x-2">
            </div>
        </div>
    </div>
</div>
