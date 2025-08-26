<div class="w-5/6 m-auto">
    <h1>QR-Code Scanner</h1>

    <!-- Container für den Scanner -->
    <div id="reader" style="width: 500px;"></div>

    <!-- Dynamische Eingabefelder -->
    <form wire:submit.prevent="buchen" class="flex flex-col w-1/2">

        <div class="flex flex-row gap-4 font-bold mt-6">
            <div class="flex-1">Artikel</div>
            <div class="flex-1">Lagerort</div>
            <div class="flex-1 text-right">Menge</div>
        </div>

        @foreach($inputData as $index => $row)
            <div class="flex flex-row gap-4 mt-2">
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Artikel"
                           type="text" class="w-full border rounded px-2 py-1">
                </div>

                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Lagerort"
                           type="text" class="w-full border rounded px-2 py-1">
                </div>

                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Menge"
                           type="number" min="1"
                           class="w-full border rounded px-2 py-1 text-right">
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
                Buchen
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mt-2 text-green-600">{{ session('message') }}</div>
        @endif
    </form>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            document.addEventListener("livewire:navigated", () => {
                function onScanSuccess(decodedText) {
                    Livewire.dispatch('qrcode-scanned', { code: decodedText });
                }

                new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 })
                    .render(onScanSuccess);
            });
        </script>
    @endpush
</div>
