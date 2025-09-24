<div class="w-5/6 m-auto">
    <h1>QR-Code Scanner</h1>

    <!-- Scanner-Feld -->
    <div class="py-6">
    <select id="cameraSelection"></select>
    </div>

    <div id="reader" style="width: 300px; height: 300px; border:1px solid #ccc;"></div>

    <form wire:submit.prevent="buchen" class="flex flex-col w-1/2">

        <div class="flex flex-row gap-4 font-bold mt-6">
            <div class="flex-1">Artikel</div>
            <div class="flex-1">Lagerort</div>
            <div class="flex-1 text-right">Menge</div>
        </div>

        <div class="flex text-xs text-gray-500 justify-end">
            Negative Mengen werden abgebucht.
        </div>

        @foreach($inputData as $index => $row)
            <div class="flex flex-row gap-4 mt-2">
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Artikel" type="text"
                        class="w-full border rounded px-2 py-1">
                </div>
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Lagerort" type="text"
                        class="w-full border rounded px-2 py-1">
                </div>
                <div class="flex-1">
                    <input wire:model="inputData.{{ $index }}.Menge" type="number" min="1"
                        class="w-full border rounded px-2 py-1 text-right">
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit"
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
                Buchen
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mt-2 text-green-600">{{ session('message') }}</div>
        @endif
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrCode;
let currentCameraId = null;
let cameraSelectInitialized = false;

function startScanner() {
    html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
            const cameraSelect = document.getElementById('cameraSelection');

            if (!cameraSelectInitialized) {
                devices.forEach(device => {
                    const option = document.createElement('option');
                    option.value = device.id;
                    option.text = device.label;
                    cameraSelect.appendChild(option);
                });

                cameraSelect.addEventListener('change', async () => {
                    const newCameraId = cameraSelect.value;

                    if (html5QrCode && currentCameraId !== newCameraId) {
                        await html5QrCode.stop();
                        currentCameraId = newCameraId;
                        html5QrCode.start(
                            currentCameraId,
                            { fps: 10, qrbox: { width: 250, height: 250 } },
                            onScanSuccess
                        ).catch(err => console.error("Start-Fehler:", err));
                    }
                });

                cameraSelectInitialized = true;
            }

            // Kamera auswählen: entweder bereits gewählt oder erste
            if (!currentCameraId) {
                currentCameraId = devices[0].id;
                cameraSelect.value = currentCameraId;
            }

            html5QrCode.start(
                currentCameraId,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).catch(err => console.error("Start-Fehler:", err));
        }
    }).catch(err => console.error("Kamera-Fehler:", err));
}

function onScanSuccess(decodedText) {
    console.log("QR erkannt:", decodedText);
    Livewire.dispatch('qrcode-scanned', String(decodedText)); // explizit als String


    // Scanner kurz stoppen, um Doppel-Scans zu vermeiden
    html5QrCode.stop().then(() => {
        console.log("Scanner gestoppt");
        setTimeout(() => startScanner(), 1500);
    });
}

// Optional: visuelles Feedback nach Scan
document.addEventListener('scan-processed', () => {
    console.log("Scan in Livewire verarbeitet.");
});

document.addEventListener("livewire:navigated", startScanner);
</script>
@endpush

