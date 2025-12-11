<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Etikett</title>
    @vite('resources/css/app.css')

    <style>
        /* ---- Druck-Einstellungen ---- */
        @page {
            size: 50mm 30mm;  /* Etikettengröße */
            margin: 0;        /* keine Seitenränder */
        }

        body {
            margin: 0;
            padding: 0;
        }

        /* Container für das Etikett */
        .label {
            width: 50mm;
            height: 30mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
</head>
<body>

<div class="label">
    <div id="qr"></div>

    <div class="text-center mt-1 text-xs">
        {{ $ab }}
    </div>
</div>

<script>
    QRCode.toCanvas(
        document.getElementById('qr'),
        "{{ $ab }}",
        { width: 80 }, // ca. 25mm
        function(error) { if (error) console.error(error); }
    );
</script>

</body>
</html>
