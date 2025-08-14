import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import QRCodeScanner from '../Components/QRCodeScanner';
import { useState } from 'react';

export default function Dashboard() {
  const [results, setResults] = useState([]);

  const handleScan = (code) => {
    // Deduplizieren + max. 50 Zeilen behalten
    setResults(prev => {
      if (prev.includes(code)) return prev;
      const next = [...prev, code];
      return next.slice(-50);
    });
  };

  const clearAll = () => setResults([]);

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>}
    >
      <Head title="Dashboard" />

      <div className="w-full mx-0.5">
        <div className="mx-auto w-full sm:px-0.5 lg:px-8">
          <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div className="p-6 text-gray-900">
              <h1 className="text-lg mb-3">Dashboard mit QR-Scanner</h1>

              <QRCodeScanner onScan={handleScan} />

              <div className="mt-4">
                {/* statt Breeze-TextInput verwenden wir ein simples, robustes <textarea> */}
                <textarea
                  value={results.join('\n')}
                  onChange={(e) => setResults(e.target.value.split('\n'))}
                  className="w-full h-40 border rounded p-2"
                  placeholder="Gescannten Code hier sehen…"
                />
                <div className="mt-2 flex gap-2">
                  <button
                    type="button"
                    onClick={clearAll}
                    className="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300"
                  >
                    Leeren
                  </button>
                </div>
                <p className="text-xs text-gray-500 mt-2">
                  Duplikate werden ignoriert, Ausgabe gedrosselt (≤1/s), max. 50 Einträge.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
