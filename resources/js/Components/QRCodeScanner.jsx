import React, { useEffect, useRef } from 'react';
import { Html5QrcodeScanner } from 'html5-qrcode';

/**
 * Leichter, fehlertoleranter Wrapper um html5-qrcode.
 * - sendet nur deduplizierte Ergebnisse (innerhalb der Session)
 * - optionaler Auto-Stop nach dem ersten erfolgreichen Scan
 * - räumt beim Unmount auf
 */
const QRCodeScanner = ({
  onScan,
  fps = 10,
  qrbox = 250,
  autoStop = false,          // auf true setzen, wenn nach dem ersten Treffer gestoppt werden soll
  containerId = 'reader',
}) => {
  const scannerRef = useRef(null);
  const seenRef = useRef(new Set());
  const lastEmitRef = useRef(0);

  useEffect(() => {
    // Falls der Container schon mal initialisiert wurde, vorher aufräumen
    const cleanup = async () => {
      try {
        if (scannerRef.current) {
          await scannerRef.current.clear();
        }
      } catch {}
      scannerRef.current = null;
    };

    const start = async () => {
      await cleanup();

      const scanner = new Html5QrcodeScanner(
        containerId,
        {
          fps,
          qrbox,
          rememberLastUsedCamera: true,
        },
        false
      );

      scanner.render(
        async (decodedText/*, decodedResult*/) => {
          // Throttle: max. 1 Emit pro Sekunde
          const now = Date.now();
          if (now - lastEmitRef.current < 1000) return;

          // Deduplizierung (nur neue Werte)
          if (seenRef.current.has(decodedText)) return;
          seenRef.current.add(decodedText);
          lastEmitRef.current = now;

          try {
            onScan?.(decodedText);
          } catch {}

            // optional: nach erstem Treffer stoppen
          if (autoStop) {
            try { await scanner.clear(); } catch {}
          }
        },
        // Fehler-Callback: leise – keine Massenausgaben in der Konsole
        () => {}
      );

      scannerRef.current = scanner;
    };

    start();

    return () => { cleanup(); };
  }, [containerId, fps, qrbox, autoStop, onScan]);

  return <div id={containerId} style={{ width: '500px' }} />;
};

export default QRCodeScanner;
