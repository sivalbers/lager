<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LieferscheinController extends Controller
{

    // Wird aktuell nicht benötigt.

    public function verarbeiten(Request $request)
    {
        // 1. Datei-Upload (funktioniert bei dir schon, hier nur Beispiel)
        $pdf = $request->file('file');
        $path = $pdf->store('lieferscheine');

        $pdfPath = Storage::path($path);

        // 2. PDF -> Text mit pdftotext
        $txtPath = $pdfPath . '.txt';
        $command = "pdftotext -layout " . escapeshellarg($pdfPath) . " " . escapeshellarg($txtPath);
        shell_exec($command);

        $text = file_get_contents($txtPath);

        // 3. Anfrage an ChatGPT
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini', // schnell, günstig, gut für Struktur-Parsing
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Du bist ein Extraktionsassistent. Analysiere den folgenden Lieferscheintext und gib NUR ein JSON zurück im Format: [{\"artikelnummer\": \"...\", \"menge\": ...}, ...]. Keine Kommentare, keine Erklärungen."
                    ],
                    [
                        'role' => 'user',
                        'content' => $text
                    ],
                ],
                'temperature' => 0,
                'response_format' => ['type' => 'json_object']
            ]);

        $json = $response->json();

        // 4. Ergebnis weiterverarbeiten
        // JSON von ChatGPT enthält in ['choices'][0]['message']['content']
        $artikel = json_decode($json['choices'][0]['message']['content'], true);

        // Beispiel: in DB speichern
        foreach ($artikel as $pos) {
            // z.B. Model LieferscheinPosition::create([...])
            // Hier nur Demo-Ausgabe:
            dump($pos);
        }

        return response()->json($artikel);
    }
}
