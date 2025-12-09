<div class="md:w-10/12 mx-auto w-full">



    <div class="flex flex-row items-center space-x-4">
        <div class="text-2xl font-bold my-4">{{ $ueberschrift }}</div>

    </div>

    <div id="reader" style="width: 200px; height: 200px; border:1px solid #ccc;"></div>



    <div class="mt-6 px-4 py-6 bg-white border rounded shadow max-w-full overflow-hidden">
        <div class="text-2xl font-bold mb-4">
            Manuelle Erfassung:
        </div>

        <form wire:submit.prevent="manuelleErfassung"
            class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Artikel --}}
            <div class="min-w-0">
                <label class="block text-sm text-gray-600 mb-1">Artikel</label>
                <input type="text"
                    list="artikelListe"
                    wire:model.blur="mArtikel"
                    class="block w-full h-10 border rounded px-2 text-sm" />
                <datalist id="artikelListe">
                    @foreach($artikelliste as $artikel)
                        <option value="{{ $artikel['artikelnr'] }}">{{ $artikel['bezeichnung']  }}</option>
                    @endforeach
                </datalist>
            </div>

            {{-- Bezeichnung --}}
            <div class="min-w-0">
                <label class="block text-sm text-gray-600 mb-1">Bezeichnung</label>
                <input type="text"
                    value="{{ $mBezeichnung }}"
                    disabled
                    class="block w-full h-10 border rounded px-2 bg-gray-100 text-sm text-gray-600" />
            </div>

            {{-- Abladestelle --}}
            <div class="min-w-0">
                <label class="block text-sm text-gray-600 mb-1">Abladestelle</label>
                <select wire:model.blur="mAbladestelle"
                        class="block w-full h-10 border rounded px-2 text-sm"
                        {{ count($abladestellen) == 1 ? 'disabled' : '' }}>
                    <option value=\"\">Bitte wählen</option>
                    @foreach($abladestellen as $stelle)
                        <option value="{{ $stelle['id'] }}">{{ $stelle['name'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Lagerort --}}
            <div class="min-w-0">
                <label class="block text-sm text-gray-600 mb-1">Lagerort</label>
                <select wire:model.blur="mLagerort"
                        class="block w-full h-10 border rounded px-2 text-sm">
                    <option value="">Bitte wählen</option>
                    @foreach($lagerorte as $lagerort)
                        <option value="{{ $lagerort['id'] }}">{{ $lagerort['bezeichnung'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Lagerplatz --}}
            <div class="min-w-0">
                <label class="block text-sm text-gray-600 mb-1">Lagerplatz</label>
                <input type="text" wire:model="mLagerplatz"
                    class="block w-full h-10 border rounded px-2 text-sm" />
            </div>

            {{-- Menge --}}
            <div class="min-w-0">
                <label class="block text-sm text-gray-600 mb-1">Menge</label>
                <input type="number" wire:model="mMenge"
                    class="block w-full h-10 border rounded px-2 text-sm" min="0" />
            </div>

            {{-- Button --}}
            <div class="min-w-0 sm:col-span-2 lg:col-span-1 flex items-end">
                <button type="submit" class="w-full h-10 bg-sky-600 text-white rounded">
                    Hinzufügen
                </button>
            </div>
        </form>
    </div>



    <div class="flex flex-col w-full my-8">
        @if ($inputData)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                @foreach ($inputData as $index => $row)
                    <div class="block p-2 bg-white rounded-lg shadow-md shadow-gray-300"  wire:key="{{ $index }}" style="border-width: 2px; border-color: #ccc">
                        <div class="flex flex-row ">
                            <div class="grow flex-col">
                                <div class="text-sm sm:text-sm md:text-base lg:text-base xl:text-lg 2xl:text-lg mb-1">
                                    <div class="flex flex-col">
                                        <div class="flex flex-row space-x-2">
                                            <div>{{ $row['artikel'] }}</div>

                                        </div>
                                        <div class="flex flex-row space-x-2">
                                            <div class="text-sm">{{ $row['bezeichnung'] }}</div>
                                        </div>

                                        <div class="flex flex-row space-x-2">
                                            <div><span class="text-gray-500 text-xs">Abladestelle:</span> {{ $row['abladestelle'] }}</div>
                                        </div>
                                        <div class="flex flex-row space-x-2">
                                            <div><span class="text-gray-500 text-xs">Lagerort:</span> {{ $row['lagerort'] }}</div>
                                        </div>

                                        <div class="flex flex-row justify-between">
                                            <div><span class="text-gray-500 text-xs">Lagerplatz:</span> {{ $row['lagerplatz'] }}</div>
                                            <div><span class="text-gray-500 text-xs">Menge:</span> {{ $row['menge'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>



            <div class="flex justify-end">
                <button wire:click="buchen" class="mt-4 px-4 py-2 bg-sky-600 text-white rounded">
                    Buchen
                </button>
            </div>

            @if (session()->has('message'))
                <div class="mt-2 text-green-600">{{ session('message') }}</div>
            @endif
        @endif

    </div>





</div>

@push('scripts')
    <script>
        window.savedCameraId = @json($cameraId);

        function waitForHtml5Qrcode(callback) {
            if (window.Html5Qrcode) {
                callback();
            } else {
                console.log("Html5Qrcode noch nicht geladen – retry...");
                setTimeout(() => waitForHtml5Qrcode(callback), 200);
            }
        }
    </script>

    <!-- Bibliothek laden -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        if (!window.scannerInitialized) {
            window.scannerInitialized = true;

            let html5QrCode = null;
            let currentCameraId = window.savedCameraId || null;

            function startScanner() {
                waitForHtml5Qrcode(() => {

                    html5QrCode = new Html5Qrcode("reader");

                    Html5Qrcode.getCameras().then(devices => {

                        if (currentCameraId && devices.some(d => d.id === currentCameraId)) {
                            startCamera(currentCameraId);
                            return;
                        }

                        currentCameraId = devices[0].id;
                        startCamera(currentCameraId);

                    }).catch(err => console.error("Kamera-Fehler:", err));
                });
            }

            function startCamera(cameraId) {
                html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: { width: 250, height: 250 }},
                    onScanSuccess
                ).catch(err => console.error("Start-Fehler:", err));
            }

            function onScanSuccess(decodedText) {
                Livewire.dispatch('qrcode-scanned', [decodedText]);

                html5QrCode.stop().then(() => {
                    setTimeout(startScanner, 1500);
                });
            }

            document.addEventListener("livewire:navigated", startScanner);

            startScanner(); // erster Start
        }
    </script>
@endpush
