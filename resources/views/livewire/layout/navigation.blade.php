<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */

    public array $menuitems = [
        ['label' => 'Lagerbestand', 'route' => 'bestand', 'berechtigung' => null, 'image' => 'heroicon-s-cube', 'submenu' => []],
        ['label' => 'Buchungsprotokoll', 'route' => 'protokoll', 'berechtigung' => 'protokoll anzeigen', 'image' => 'heroicon-o-list-bullet', 'submenu' => []],
        ['label' => 'Warenzugang', 'route' => 'lieferschein', 'berechtigung' => 'warenzugang buchen', 'image' => 'img_warenzugang', 'submenu' => []],
        ['label' => 'Etiketten erstellen', 'route' => 'etikettenerstellen', 'berechtigung' => 'warenzugang buchen', 'image' => 'heroicon-o-qr-code', 'submenu' => []],
        ['label' => 'Einkaufsliste', 'route' => 'einkaufsliste', 'berechtigung' => 'einkaufsliste anzeigen', 'image' => 'heroicon-s-shopping-bag', 'submenu' => []],
        ['label' => 'Artikel buchen', 'route' => '', 'berechtigung' => '', 'image' => 'img_submenu', 'submenu' => [['label' => 'Entnahme', 'route' => 'artikel.entnahme', 'berechtigung' => 'artikel buchen', 'image' => 'img_artikel_entnahme'], ['label' => 'Rückgabe', 'route' => 'artikel.rueckgabe', 'berechtigung' => 'artikel buchen', 'image' => 'img_artikel_rueckgabe'], ['label' => 'Korrektur', 'route' => 'artikel.korrektur', 'berechtigung' => 'artikel buchen', 'image' => 'img_artikel_korrektur']]],
        ['label' => 'Stammdaten', 'route' => '', 'berechtigung' => '', 'image' => 'img_submenu', 'submenu' => [['label' => 'Debitoren', 'route' => 'debitoren', 'berechtigung' => 'debitor anzeigen', 'image' => 'heroicon-s-user-group'], ['label' => 'Artikel', 'route' => 'artikel', 'berechtigung' => 'artikel anzeigen', 'image' => 'heroicon-s-squares-2x2'], ['label' => 'Mitarbeiter', 'route' => 'mitarbeiter', 'berechtigung' => 'mitarbeiter anzeigen', 'image' => 'heroicon-c-user-circle'], ['label' => 'PSP-Elemente', 'route' => 'psp', 'berechtigung' => 'psp anzeigen', 'image' => 'heroicon-s-credit-card']]],
    ];

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>



    <div class="bg-white border-b border-gray-100">

        <div x-data="{
            width: window.innerWidth,
            breakpoint() {
                if (this.width >= 1536) return '2XL';
                if (this.width >= 1280) return 'XL';
                if (this.width >= 1024) return 'LG';
                if (this.width >= 768) return 'MD';
                if (this.width >= 640) return 'SM';
                return 'Keine';
            }
        }" x-init="window.addEventListener('resize', () => width = window.innerWidth)"
            class="block mx-2
                    bg-lime-400
                    sm:bg-red-200
                    md:bg-orange-200
                    lg:bg-green-200
                    xl:bg-blue-200
                    2xl:bg-pink-200">

            <span x-text="breakpoint()" class="block text-center font-bold"></span>
        </div>

        <div class="shrink-0 flex items-center">
            <a href="{{ route('bestand') }}" wire:navigate>
                <x-img_ewe_logo />
            </a>
        </div>

        <div class="flex flex-row space-x-8 sm:-my-px sm:ms-10">

            @foreach ($menuitems as $item)
                @if (count($item['submenu']) > 0)
                    <x-bladewind::dropmenu>
                        <x-slot:trigger>
                            <x-bladewind::button type="secondary" size="tiny">
                                <div class="flex flex-row items-center">

                                    <div class="hidden md:flex">
                                        {{ $item['label'] }}
                                    </div>

                                    @if (!is_null($item['image']))
                                        <x-dynamic-component :component="$item['image']"
                                            class="fill-current ml-1 h-4 w-4 inline-block  mb-1" />
                                    @endif
                                </div>
                            </x-bladewind::button>

                        </x-slot:trigger>

                        @foreach ($item['submenu'] as $subitem)
                            @if (is_null($subitem['berechtigung']) || auth()->user()->hasBerechtigung($subitem['berechtigung']))
                                <x-bladewind::dropmenu.item>

                                    <a href="{{ route($subitem['route']) }}" wire:navigate  aria-label="{{ $subitem['label'] }}" title="{{ $subitem['label'] }}" >
                                        <div class="flex flex-row items-center">
                                        @if (!is_null($subitem['image']))
                                            <x-dynamic-component :component="$subitem['image']" class="w-6 h-6 inline-block me-1 mb-1" />
                                        @endif
                                        <div class="hidden md:flex">
                                            {{ $subitem['label'] }}
                                        </div>
                                        </div>
                                    </a>

                                </x-bladewind::dropmenu.item>
                            @endif
                        @endforeach
                    </x-bladewind::dropmenu>
                @else
                    @if (is_null($item['berechtigung']) || auth()->user()->hasBerechtigung($item['berechtigung']))

                        <a href="{{ route($item['route']) }}" wire:navigate>
                            <div class="flex flex-row items-center">
                                @if (!is_null($item['image']))
                                    <x-dynamic-component :component="$item['image']" class="w-6 h-6 inline-block me-1 mb-1" />
                                @endif
                                <div class="hidden md:flex">
                                    {{ $item['label'] }}
                                </div>
                            </div>
                        </a>

                    @endif
                @endif
            @endforeach

            <x-bladewind::dropmenu>

                <x-slot:trigger>
                    <x-bladewind::button type="secondary" size="tiny">
                        <div class="flex flex-row items-center">
                            {{ auth()->user()->name }}
                            <x-img_submenu class="fill-current ml-1 h-4 w-4 inline-block  mb-1" />
                        </div>
                    </x-bladewind::button>
                </x-slot:trigger>

                <x-bladewind::dropmenu.item>
                    <a href="{{ route('profile') }}" wire:navigate>
                        {{ __('Profile') }}
                    </a>
                </x-bladewind::dropmenu.item>
                <x-bladewind::dropmenu.item>
                    <a wire:click="logout"  wire:navigate>
                        {{ __('Log Out') }}
                    </a>
                </x-bladewind::dropmenu.item>

            </x-bladewind::dropmenu>
        </div>

    </div>

</div>
