import React, { useEffect, useRef } from 'react';
import { Html5QrcodeScanner } from 'html5-qrcode';

const QRCodeScanner = ({
  onScan,
  fps = 10,
  qrbox = 250,
  containerId = 'reader',
}) => {
  const scannerRef = useRef(null);
  const seenRef = useRef(new Set());
  const lastEmitRef = useRef(0);

  useEffect(() => {
    const cleanup = async () => {
      try {
        if (scannerRef.current) {
          await scannerRef.current.clear();
        }
      } catch {}
      scannerRef.current = null;
    };

    const startScanner = async () => {
      await cleanup();

      const scanner = new Html5QrcodeScanner(
        containerId,
        { fps, qrbox, rememberLastUsedCamera: true },
        false
      );

      scanner.render(
        (decodedText) => {
          const now = Date.now();

          // Throttle: max. 1 Meldung pro Sekunde
          if (now - lastEmitRef.current < 1000) return;

          // Deduplizieren innerhalb einer Session
          if (seenRef.current.has(decodedText)) return;
          seenRef.current.add(decodedText);
          lastEmitRef.current = now;

          try {
            onScan?.(decodedText);
          } catch {}
        },
        () => {} // Fehler ignorieren, um Konsolen-Spam zu vermeiden
      );

      scannerRef.current = scanner;
    };

    startScanner();
    return () => { cleanup(); };
  }, [containerId, fps, qrbox, onScan]);

  return <div id={containerId} style={{ width: '500px' }} />;
};

export default QRCodeScanner;
