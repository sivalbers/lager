<x-mary-card title="Modal Test">
    <x-mary-button class="btn-info" wire:click="$set('open', true)">
        Modal öffnen
    </x-mary-button>

    <x-mary-modal wire:model.live="open" title="MaryUI Modal">
        <p>Alles funktioniert!</p>

        <x-slot:actions>
            <x-mary-button class="btn-error" wire:click="$set('open', false)">Schließen</x-mary-button>
        </x-slot>
    </x-mary-modal>
</x-mary-card>
