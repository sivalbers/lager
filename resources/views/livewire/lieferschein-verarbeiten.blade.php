<div class="w-5/6 m-auto">

<!-- https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&$filter=DocumentNo%20eq%20'LS00070912'    <div class="w-10/12 m-auto"> -->
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold my-4">Warenzugang buchen ** Funktion ist in Arbeit - bisher nicht funktionstüchtig **</h1>


            <div class="flex flex-row items-center space-x-2">
                <div class="">
                    Lieferschein-Nr.: LS000 ...
                </div>
                <div class="">
                    <input type="number" wire:model="lieferscheinNr" auto class="h-9 rounded">
                </div>
                <div class="">
                    <x-mary-button wire:click="readLieferschein">
                        Lieferschein holen
                    </x-mary-button>
                </div>
            </div>
            <div class="flex flex-row items-center space-x-2 text-gray-500">
                Bitte die letzten fünf Ziffern
            </div>



            <div class="pt-6">Ergebnis</div>
            <div>

                <textarea id="jsonresult" wire:model="jsonResult" rows="15" class="font-mono text-sm w-full" > </textarea>
            </div>

            <div>
                <div x-data="{ clip: @entangle('clipboardValue') }">
                    <button
                        type="button"
                        @click="
                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(clip)
                                .then(() => alert('Artikel + Lagerort kopiert!'))
                                .catch(err => alert('Fehler beim Kopieren: ' + err));
                            } else {
                                alert('Clipboard API nicht verfügbar – HTTPS nötig');
                            }
                        "
                        class="px-2 py-1 bg-blue-500 text-white rounded"
                    >
                        Nur Artikel + Lagerort kopieren
                    </button>


                </div>
            </div>


        </div>

    </div>
</div>
