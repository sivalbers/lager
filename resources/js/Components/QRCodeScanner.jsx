// resources/js/components/QRCodeScanner.jsx
import React, { useEffect } from 'react';
import { Html5QrcodeScanner } from 'html5-qrcode';

const QRCodeScanner = () => {
  useEffect(() => {
    const scanner = new Html5QrcodeScanner(
      "reader",
      { fps: 10, qrbox: 250 },
      false
    );

    scanner.render(
      (decodedText, decodedResult) => {
        console.log("QR Code gefunden:", decodedText);
      },
      (errorMessage) => {
        console.warn("Scan-Fehler:", errorMessage);
      }
    );
  }, []);

  return <div id="reader" style={{ width: "500px" }} />;
};

export default QRCodeScanner;
