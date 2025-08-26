<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{


    public function delete(Request $request)
    {
        // path: will always contain the path to the file being deleted
        $filePath = $request->input('path');

        if (!$filePath) {
            return response()->json(['error' => 'No file path provided'], 400);
        }

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['message' => 'File deleted']);
        }
        return response()->json(['error' => 'File not found'], 404);
    }


    public function store(Request $request)
    {
        \Log::info('Store aufgerufen', [
            'all'   => $request->all(),
            'files' => $request->allFiles(),
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            \Log::info('Upload attempt', [
                'original_name' => $file->getClientOriginalName(),
                'mime'          => $file->getMimeType(),
                'ext'           => $file->getClientOriginalExtension(),
                'size'          => $file->getSize(),
            ]);

            // 1. Datei speichern
            $path = $file->store('lieferscheine'); // bleibt wie bei dir
            $absolutePdfPath = Storage::path($path);

            \Log::info('Upload received', ['stored' => $path]);

            // 2. PDF -> Text konvertieren
            $txtPath = $absolutePdfPath . '.txt';
            $command = "pdftotext -layout " . escapeshellarg($absolutePdfPath) . " " . escapeshellarg($txtPath);
            shell_exec($command);

            \Log::info('txtPath', ['textdatei' => $txtPath]);

            return response()->json(['path' => $txtPath ] , 200);
/*
            if (!file_exists($txtPath)) {
                \Log::error("pdftotext fehlgeschlagen", ['pdf' => $absolutePdfPath]);
                return response()->json(['error' => 'PDF konnte nicht in Text konvertiert werden'], 500);
            }

            $text = file_get_contents($txtPath);

            // 3. Text an ChatGPT schicken
            $response = Http::withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini', // klein, günstig, gut für Extraktion
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Du bist ein Extraktionsassistent. Analysiere den folgenden Lieferscheintext und gib NUR ein JSON zurück im Format: [{\"artikelnummer\": \"...\", \"menge\": ...}, ...]. Keine Kommentare, keine Erklärungen. Unsere Artikelnummer hat immer mindestens 6 stellen."
                        ],
                        [
                            'role' => 'user',
                            'content' => $text
                        ],
                    ],
                    'temperature' => 0,
                ]);

            if ($response->failed()) {
                \Log::error("ChatGPT API Fehler", ['response' => $response->body()]);
                return response()->json(['error' => 'ChatGPT Anfrage fehlgeschlagen'], 500);
            }

            $jsonResponse = $response->json();
            $artikel = [];

            if (isset($jsonResponse['choices'][0]['message']['content'])) {
                $artikel = json_decode($jsonResponse['choices'][0]['message']['content'], true);
            }

            // 4. Antwort zurückgeben
            return response()->json([
                'id'       => $path,
                'path'     => $path,
                'artikel'  => $artikel,
            ]);
        }

        \Log::error('Upload failed: no file in request');
        return response()->json(['error' => 'No file uploaded'], 400);
        */
        }
    }

    public function revert(Request $request)
    {
        \Log::info('in Revert');
        $fileId = $request->getContent();

        if ($fileId && Storage::exists($fileId)) {
            Storage::delete($fileId);
            return response()->json(['status' => 'deleted']);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
