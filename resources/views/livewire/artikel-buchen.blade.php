<div class="w-[80vh] md:w-5/6  m-auto">

    <div class="flex flex-row items-center space-x-4">
        <div class="text-2xl font-bold mt-4">{{ $ueberschrift }}</div>

    </div>

    <div class="font-bold mt-4 text-red-400">Wird die Kameraauswahl nicht angezeigt, bitte die Seite mit <F5> aktualisieren!</div>
    <div class="py-6">
        <div class="flex flex-row items-center space-x-4">
            <div>
                Kameraauswahl:
            </div>
            <div>
                <select id="cameraSelection" class="h-10 rounded"></select>
            </div>
        </div>
    </div>

    <div id="reader" style="width: 200px; height: 200px; border:1px solid #ccc;"></div>




<div class="mt-6 p-4 border rounded-md shadow-md bg-white">
    <div class="text-2xl font-bold mb-4">
        Manuelle Erfassung:
    </div>

<form wire:submit.prevent="manuelleErfassung" class="grid grid-cols-[auto_1fr_1fr_1fr_1fr_auto_auto] gap-4 items-end">

    <div class="grid grid-cols-[auto_auto_1fr_1fr_1fr_auto_auto] gap-x-4 ">
        {{-- 1. Zeile: Labels --}}
        <label class="text-base text-gray-500">Artikel</label>
        <label class="text-base text-gray-500">Bezeichnung</label>
        <label class="text-base text-gray-500">Abladestelle</label>
        <label class="text-base text-gray-500">Lagerort</label>
        <label class="text-base text-gray-500">Lagerplatz</label>
        <label class="text-base text-gray-500">Menge</label>
        <div></div> {{-- Leerzelle für den Button --}}

        {{-- 2. Zeile: Eingabefelder --}}
        <input  type="text"
                list="artikelListe"
                wire:model.blur="mArtikel" class="h-10 border rounded px-2 text-sm" />

        <datalist id="artikelListe">
            @foreach($artikelliste as $artikel)
                <option value="{{ $artikel['artikelnr'] }}">{{ $artikel['bezeichnung']  }}</option>
            @endforeach
        </datalist>


        <input type="text" value="{{ $mBezeichnung }}" disabled class="h-10 border rounded px-2 bg-gray-100 text-sm text-gray-600" />

        <select wire:model.blur="mAbladestelle" class="h-10 border rounded px-2 text-sm"  {{ (count($abladestellen) == 1) ? 'disabled' : '' }} >
            <option value="">Bitte wählen</option>
            @foreach($abladestellen as $stelle)
                <option value="{{ $stelle['id'] }}">{{ $stelle['name'] }}</option>
            @endforeach
        </select>
        <select wire:model.blur="mLagerort" class="h-10 border rounded px-2 text-sm">
            <option value="">Bitte wählen</option>
            @foreach($lagerorte as $lagerort)
                <option value="{{ $lagerort['id'] }}">{{ $lagerort['bezeichnung'] }}</option>
            @endforeach
        </select>


        <input type="text" wire:model="mLagerplatz" class="h-10 border rounded px-2 text-sm" />
        <input type="number" wire:model="mMenge" class="h-10 border rounded px-2 text-sm w-20" min="0" />
        <button type="submit" class="h-10 px-4 bg-sky-600 text-white rounded">Hinzufügen</button>
    </div>


</form>

</div>





    <form wire:submit.prevent="buchen" class="flex flex-col w-full">

        <div class="flex flex-row gap-4 font-bold mt-6">
            <div class="w-10 flex-none"></div>
            <div class="w-20 flex-none">Artikel</div>
            <div class="flex-1">Bezeichnung</div>
            <div class="flex-1">Abladestelle</div>

            <div class="flex-1">Lagerort</div>
            <div class="flex-1">Lagerplatz</div>
            <div class="w-20 flex-none text-right">Menge</div>
        </div>

        <div class="flex text-xs text-gray-500 justify-end">
            Negative Mengen werden abgebucht.
        </div>

        @if ($inputData)
            @foreach ($inputData as $index => $row)
                <div class="flex flex-row gap-4 mt-2">
                    <div class="w-10 flex-none">
                        @if ($index == count($inputData) - 1)
                            <button type="button" wire:click="addRow({{ $index }})"
                                class="text-green-500 hover:text-green-700 font-bold">+</button>
                        @endif
                    </div>


                    <div class="w-20 flex-none">
                        <input wire:model="inputData.{{ $index }}.artikel" type="text"
                            class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <input wire:model="inputData.{{ $index }}.bezeichnung" type="text"
                            class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <input wire:model="inputData.{{ $index }}.abladestelle" type="text"
                            class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <input wire:model="inputData.{{ $index }}.lagerort" type="text"
                            class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <input wire:model="inputData.{{ $index }}.lagerplatz" type="text"
                            class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="w-20 flex-none">
                        <input wire:model="inputData.{{ $index }}.menge" type="number"
                            class="w-full border rounded px-2 py-1 text-right">
                    </div>
                </div>
            @endforeach
        @endif
        <div class="flex justify-end">
            <button type="submit" class="mt-4 px-4 py-2 bg-sky-600 text-white rounded">
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
                                    currentCameraId, {
                                        fps: 10,
                                        qrbox: {
                                            width: 250,
                                            height: 250
                                        }
                                    },
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
                        currentCameraId, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        onScanSuccess
                    ).catch(err => console.error("Start-Fehler:", err));
                }
            }).catch(err => console.error("Kamera-Fehler:", err));
        }

        function onScanSuccess(decodedText) {
            console.log("QR erkannt:", decodedText);
            Livewire.dispatch('qrcode-scanned', [String(decodedText)]);



            // Scanner kurz stoppen, um Doppel-Scans zu vermeiden
            html5QrCode.stop().then(() => {
                console.log("Scanner gestoppt");
                setTimeout(() => startScanner(), 1500);
            });
        }
        window.addEventListener('scan-processed', () => {
            console.log("Browser-Event 'scan-processed' empfangen!");

            // kleinen Timeout, damit DOM fertig ist
            setTimeout(() => {
                const inputs = document.querySelectorAll('input[type="number"][wire\\:model$=".Menge"]');
                if (inputs.length > 0) {
                    const lastInput = inputs[inputs.length - 1];
                    lastInput.focus();
                    lastInput.select();
                    console.log("Fokus gesetzt auf letzte Menge!");
                }
            }, 50);
        });

        document.addEventListener("livewire:navigated", startScanner);
    </script>
@endpush
