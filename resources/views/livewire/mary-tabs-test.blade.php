<x-mary-card title="Tabs Test">

<x-mary-tabs wire:model="activeTab">

    <x-mary-tab name="a" label="Tab A">
        Inhalt von Tab A
    </x-mary-tab>

    <x-mary-tab name="b" label="Tab B">
        Inhalt von Tab B
    </x-mary-tab>

    <x-mary-tab name="c" label="Tab C">
        Inhalt von Tab C
    </x-mary-tab>

</x-mary-tabs>

    <div class="mt-4">
        Aktiver Tab: {{ $activeTab }}
    </div>

</x-mary-card>
