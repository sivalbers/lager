<div class="w-5/6 m-auto">
    <h1 class="text-2xl font-bold my-4 print:hidden">Etiketten erstellen</h1>

    <!-- Eingabeformular -->
    <form wire:submit.prevent="createDataFromText" class="print:hidden mb-6">
        <label for="zeilen" class="block mb-2 font-semibold">Artikelnummer, Abladestelle, Lagerort, Lagerplatz:</label>
        <textarea wire:model.defer="text" id="zeilen" rows="6" class="w-full border p-2"></textarea>

        <button type="submit" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded">
            Erzeugen
        </button>
    </form>

    <!-- Ausgabe der QR-Codes -->
    @if(!empty($data))
        <h2 class="text-lg font-bold mb-2 print:hidden">Erzeugte Etiketten</h2>
        <h2 class="text-lg font-bold mb-2 hidden print:flex">Etiketten:</h2>
        <div class="grid grid-cols-5 gap-6 print:grid-cols-3">
            @foreach($data as $item)
                <div class="p-2 border text-center">

                    <div class="flex flex-col gap-6">

                        <div> {!! QrCode::size(150)->generate(json_encode($item)) !!} </div>
                        <div class="text-left">{{ $item['artikel'] }} | {{ $item['abladestelle'] }} | {{ $item['lagerort'] }} | {{ $item['lagerplatz'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
