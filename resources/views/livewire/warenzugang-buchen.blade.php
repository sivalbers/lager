<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Lieferschein importieren</h2>

    <div class="flex items-center space-x-4 mb-4">
        <label class="font-medium">Lieferschein-Nr: LS000</label>
        <input type="text" wire:model="lieferscheinNr" class="border px-2 py-1 rounded w-32" placeholder="z.B. 70824">
        <button wire:click="readLieferschein" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
            Lieferschein holen
        </button>
    </div>

    @if ($message)
        <div class="text-red-500 mb-4">{{ $message }}</div>
    @endif

    @if (count($positionen) > 0)
        <div class="overflow-auto">
            <table class="min-w-full text-sm border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Artikel</th>
                        <th class="border px-2 py-1 text-left">Bezeichnung</th>
                        <th class="border px-2 py-1 text-left">Einheit</th>
                        <th class="border px-2 py-1 text-left">Lagerort</th>
                        <th class="border px-2 py-1 text-left">Lagerplatz</th>
                        <th class="border px-2 py-1 text-right">Menge</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positionen as $index => $pos)
                        <tr>
                            <td class="border px-2 py-1">{{ $pos['artikel'] }}</td>
                            <td class="border px-2 py-1">{{ $pos['bezeichnung'] }}</td>
                            <td class="border px-2 py-1">{{ $pos['einheit'] }}</td>
                            <td class="border px-2 py-1">
                                <input type="text" wire:model="positionen.{{ $index }}.lagerort" class="border rounded px-1 py-0.5 w-full">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" wire:model="positionen.{{ $index }}.lagerplatz" class="border rounded px-1 py-0.5 w-full">
                            </td>
                            <td class="border px-2 py-1 text-right">
                                <input type="number" wire:model="positionen.{{ $index }}.menge" class="border rounded px-1 py-0.5 w-16 text-right">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" disabled>
                Buchen (noch nicht implementiert)
            </button>
        </div>
    @endif
</div>
