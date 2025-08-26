<div class="w-5/6 m-auto">
    <h1>QR-Code Scanner</h1>

    <!-- Scanner-Feld -->
    <div id="reader" style="width: 300px; height: 300px; border:1px solid #ccc;"></div>

    <form wire:submit.prevent="buchen" class="flex flex-col w-1/2">

        <div class="flex flex-row gap-4 font-bold mt-6">
            <div class="flex-1">Artikel</div>
            <div class="flex-1">Lagerort</div>
            <div class="flex-1 text-right">Menge</div>
        </div>

        @foreach($inputData as $index => $row)
            <div class="flex flex-row gap-4 mt-2">
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Artikel" type="text" class="w-full border rounded px-2 py-1">
                </div>
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Lagerort" type="text" class="w-full border rounded px-2 py-1">
                </div>
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Menge" type="number" min="1" class="w-full border rounded px-2 py-1 text-right">
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Buchen</button>
        </div>

        @if (session()->has('message'))
            <div class="mt-2 text-green-600">{{ session('message') }}</div>
        @endif
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener("livewire:navigated", () => {
    // sicherstellen, dass das DIV existiert
    const reader = document.getElementById("reader");
    if (!reader) {
        console.error("Scanner-Element #reader nicht gefunden!");
        return;
    }

    const html5QrCode = new Html5Qrcode("reader");

function onScanSuccess(decodedText) {
    console.log("QR erkannt:", decodedText);
    Livewire.dispatch('qrcode-scanned', { code: decodedText });

    // Scanner anhalten, damit nicht endlos derselbe Code erkannt wird
    html5QrCode.stop().then(() => {
        console.log("Scanner gestoppt");
        // Falls du gleich wieder scannen willst:
        // setTimeout(() => startScanner(), 2000);
    }).catch(err => console.error("Stop-Fehler:", err));
}

    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
            let cameraId = devices[0].id;
            html5QrCode.start(
                cameraId,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).catch(err => console.error("Start-Fehler:", err));
        } else {
            console.error("Keine Kamera gefunden");
        }
    }).catch(err => console.error("Kamera-Fehler:", err));
});
</script>
@endpush
