<div>

    <div class="w-10/12 m-auto">
        <div class="flex flex-col">


            <div class="text-xl pt-4">Lieferschein hochladen:</div>
            <div class="pb-6 border">
                <input type="file" name="file" class="filepond" max_file_size="5mb" />
            </div>

            <div class="pt-6">Ergebnis</div>
            <div>

                <textarea id="jsonresult" wire:model="jsonResult" rows="10" cols="80" > </textarea>
            </div>


        </div>
        <div>
            <x-bladewind::button type="primary">
                Absenden
            </x-bladewind::button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    FilePond.create(document.querySelector('.filepond'), {
        server: {
            process: {
                url: '/file-upload', // deine Route zum Controller
                method: 'POST',
                onload: (response) => {
                    let json = JSON.parse(response);

                    // Nur den JSON-Teil der Artikel nehmen
                    let artikel = json.artikel ?? [];

                    // Livewire-Variable setzen
                    Livewire.dispatch('setJsonResult', { value: JSON.stringify(artikel, null, 2) });

                    return json.id; // FilePond erwartet eine ID
                }
            },
            revert: '/file-upload/revert'
        }
    });
</script>
@endpush
