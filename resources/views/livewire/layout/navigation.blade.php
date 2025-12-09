<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {

    public array $menuitems = [
        ['label' => 'Lagerbestand', 'route' => 'bestand', 'berechtigung' => null, 'image' => 'heroicon-s-cube', 'submenu' => []],
        ['label' => 'Warenzugang NEU', 'route' => 'warenzugang', 'berechtigung' => 'warenzugang buchen', 'image' => 'img_warenzugang', 'submenu' => []],

        ['label' => 'Etiketten erstellen', 'route' => 'etikettenerstellen', 'berechtigung' => 'warenzugang buchen', 'image' => 'heroicon-o-qr-code', 'submenu' => []],
        ['label' => 'Einkaufsliste', 'route' => 'einkaufsliste', 'berechtigung' => 'einkaufsliste anzeigen', 'image' => 'heroicon-s-shopping-bag', 'submenu' => []],
        [
            'label' => 'Artikel buchen', 'route' => '', 'berechtigung' => '', 'image' => 'img_submenu',
            'submenu' => [
                ['label' => 'Entnahme', 'route' => 'artikel.entnahme', 'berechtigung' => 'artikel buchen', 'image' => 'img_artikel_entnahme'],
                ['label' => 'Rückgabe', 'route' => 'artikel.rueckgabe', 'berechtigung' => 'artikel buchen', 'image' => 'img_artikel_rueckgabe'],
                ['label' => 'Korrektur', 'route' => 'artikel.korrektur', 'berechtigung' => 'artikel buchen', 'image' => 'img_artikel_korrektur'],
                ['label' => '-', 'route' => '', 'berechtigung' => 'protokoll anzeigen', 'image' => '', 'submenu' => []],
                ['label' => 'Buchungsprotokoll', 'route' => 'protokoll', 'berechtigung' => 'protokoll anzeigen', 'image' => 'heroicon-o-list-bullet', 'submenu' => []],
            ]
        ],
        [
            'label' => 'Stammdaten', 'route' => '', 'berechtigung' => '', 'image' => 'img_submenu',
            'submenu' => [
                ['label' => 'Debitoren', 'route' => 'debitoren', 'berechtigung' => 'debitor anzeigen', 'image' => 'heroicon-s-user-group'],
                ['label' => 'Artikel', 'route' => 'artikel', 'berechtigung' => 'artikel anzeigen', 'image' => 'heroicon-s-squares-2x2'],
                ['label' => 'Mitarbeiter', 'route' => 'mitarbeiter', 'berechtigung' => 'mitarbeiter anzeigen', 'image' => 'heroicon-c-user-circle'],
                ['label' => 'PSP-Elemente', 'route' => 'psp', 'berechtigung' => 'psp anzeigen', 'image' => 'heroicon-s-credit-card'],
            ]
        ],
    ];

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
};
?>

@php
    // Benutzer laden (kann null sein)
    $user = auth()->user();

    // Sichere Berechtigungsprüfung
    function hasRight($user, $berechtigung) {
        if (!$user) {
            // nicht eingeloggt → keine Berechtigung
            return false;
        }

        if (!$berechtigung) {
            // keine Berechtigung gefordert → immer true
            return true;
        }

        // Falls Methode existiert → aufrufen
        return method_exists($user, 'hasBerechtigung')
            ? $user->hasBerechtigung($berechtigung)
            : false;
    }
@endphp

<div class="bg-white border-b border-gray-100">

    {{-- RESPONSIVE BREAKPOINT Anzeige – bleibt unverändert --}}
    @if ($user && $user->id === 1)
    <div
        x-data="{
            width: window.innerWidth,
            breakpoint() {
                if (this.width >= 1536) return '2XL';
                if (this.width >= 1280) return 'XL';
                if (this.width >= 1024) return 'LG';
                if (this.width >= 768) return 'MD';
                if (this.width >= 640) return 'SM';
                return 'XS';
            }
        }"
        x-init="window.addEventListener('resize', () => width = window.innerWidth)"
        class="block mx-2
               bg-lime-400 sm:bg-red-200 md:bg-orange-200
               lg:bg-green-200 xl:bg-blue-200 2xl:bg-pink-200"
    >
        <span x-text="breakpoint()" class="block text-center font-bold"></span>
    </div>
    @endif

    {{-- LOGO --}}
    <div class="flex flex-row items-center justify-between space-x-6 sm:my-px sm:ps-10 sm:pe-10 p-4">
        <div class="shrink-0 flex items-center">
            <a href="{{ route('bestand') }}" wire:navigate>
                <x-img_ewe_logo />
            </a>
        </div>

            {{-- USER DROPDOWN --}}
        @if ($user)
        <x-mary-dropdown>

            <x-slot:trigger>
                <x-mary-button>
                    <div class="flex flex-row items-center gap-1">
                        {{ auth()->user()->name }}
                        <x-img_submenu class="w-4 h-4" />
                    </div>
                </x-mary-button>
            </x-slot:trigger>

            <li>
                <a href="{{ route('profile') }}" wire:navigate>
                    <div class="flex flex-row items-center gap-1">
                        <x-mary-icon name="s-user" class="w-5 h-5" />
                        <span class="hidden md:flex">Profil</span>
                    </div>
                </a>
            </li>

            <li>

                <a wire:click="logout" wire:navigate>
                    <div class="flex flex-row items-center gap-1">
                        <x-mary-icon name="o-arrow-right-on-rectangle" class="w-6 h-6" />
                        <span class="hidden md:flex">Logout</span>
                    </div>
                </a>
            </li>

        </x-mary-dropdown>
        @endif
    </div>

    {{-- MENÜ --}}
    <div class="flex flex-row items-center space-x-8 sm:-my-px sm:ps-10 bg-white border-y border-gray-200">

        {{-- MENÜPUNKTE --}}

        @foreach ($menuitems as $item)
            @if (count($item['submenu']) > 0)

                {{-- DROPDOWN MIT SUBMENU --}}
                <x-mary-dropdown>

                    <x-slot:trigger>
                        <x-mary-button>
                            <div class="flex flex-row items-center gap-1">
                                <span class="hidden md:flex">{{ $item['label'] }}</span>

                                @if ($item['image'])
                                    <x-dynamic-component :component="$item['image']" class="w-4 h-4" />
                                @endif
                            </div>
                        </x-mary-button>
                    </x-slot:trigger>

                    {{-- SUBMENU EINTRÄGE --}}
                    @foreach ($item['submenu'] as $subitem)

                        @if (hasRight($user, $subitem['berechtigung']))
                            <li>
                                @if ($subitem['label'] === '-')
                                    <hr class="cursor-default h-1 my-1" />

                                @else
                                <a href="{{ route($subitem['route']) }}" wire:navigate>
                                    <div class="flex flex-row items-center gap-2">
                                        @if ($subitem['image'])
                                            <x-dynamic-component :component="$subitem['image']" class="w-5 h-5" />
                                        @endif
                                        <span class="hidden md:flex">{{ $subitem['label'] }}</span>
                                    </div>
                                </a>
                                @endif
                            </li>
                        @endif
                    @endforeach

                </x-mary-dropdown>

            @else

                {{-- NORMALER MENÜPUNKT --}}
                @if (hasRight($user, $item['berechtigung']))
                    <a href="{{ route($item['route']) }}" wire:navigate>
                        <div class="flex flex-row items-center gap-1">
                            @if ($item['image'])
                                <x-dynamic-component :component="$item['image']" class="w-5 h-5" />
                            @endif

                            <span class="hidden md:flex">{{ $item['label'] }}</span>
                        </div>
                    </a>
                @endif

            @endif
        @endforeach


    </div>

</div>
