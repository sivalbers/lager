<div>
    <h1>QR-Code Scanner</h1>

    <!-- Container für den Scanner -->
    <div id="reader" style="width: 500px;"></div>

    <!-- Ausgabe -->
    <div>
        Gescannter Code: <span id="scan-result"></span>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
    document.addEventListener("livewire:navigated", () => {
        function onScanSuccess(decodedText) {
            document.getElementById("scan-result").innerText = decodedText;
            Livewire.dispatch('qrcode-scanned', { code: decodedText });
        }

        new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 })
            .render(onScanSuccess);
    });
    </script>
    @endpush
</div>
