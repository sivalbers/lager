<x-mary-card title="MaryUI Icon Explorer" subtitle="Alle verfügbaren Icons aus Heroicons">

<x-mary-icon name="o-home" class="w-8 h-8" />
<x-mary-icon name="s-user" class="w-8 h-8" />
<x-mary-icon name="m-arrow-down" class="w-8 h-8" />


    @foreach($icons as $type => $list)
        <h2 class="text-xl font-bold mt-8 mb-4 capitalize">{{ $type }} Icons</h2>

        <div class="grid grid-cols-6 md:grid-cols-10 gap-4">
            @foreach($list as $icon)
                <div class="p-4 border rounded text-center">
                    <x-mary-icon :name="$icon" class="w-8 h-8 mx-auto" />
                    <div class="text-xs mt-2">{{ $icon }}</div>
                </div>
            @endforeach
        </div>
    @endforeach

</x-mary-card>
