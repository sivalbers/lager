<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {

        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>

<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class=" mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div
                x-data="{
                    width: window.innerWidth,
                    breakpoint() {
                        if (this.width >= 1536) return '2XL';
                        if (this.width >= 1280) return 'XL';
                        if (this.width >= 1024) return 'LG';
                        if (this.width >= 768)  return 'MD';
                        if (this.width >= 640)  return 'SM';
                        return 'Keine';
                    }
                }"
                x-init="window.addEventListener('resize', () => width = window.innerWidth)"
                class="block mx-2
                    bg-lime-400
                    sm:bg-red-200
                    md:bg-orange-200
                    lg:bg-green-200
                    xl:bg-blue-200
                    2xl:bg-pink-200">

                <span x-text="breakpoint()" class="block text-center font-bold"></span>
            </div>

            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('bestand') }}" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 2048 512" class="h-14">
                            <path transform="translate(321,109)" d="m0 0h64l1 1 16 165 2 36 14-41 14-37 19-48 18-47 11-28 1-1h63l5 43 12 105 2 23 2 32 17-50 38-100 17-45 4-8h60l-11 30-19 50-20 52-19 50-40 104-3 5h-73l-2-12-14-133-3-44-1-14-15 44-21 55-20 52-19 49-2 3h-68l-1-2-13-126-16-154z" fill="#0175B2"></path>
                            <path transform="translate(1118,107)" d="m0 0h22l15 2 12 4 11 6 12 11 8 14 4 14 1 6v27l-9 66-14 98-6 43-1 2h-74l7-50 19-136 1-16-2-12-5-9-7-5-8-2h-14l-12 3-13 7-10 8-10 10-10 15-8 16-5 13-5 25-17 119-2 13-1 1h-74l3-23 26-185 11-77 2-2h68l1 4-7 35-3 10 9-11 9-10 10-9 15-10 17-8 18-5z" fill="#CDD503"></path>
                            <path transform="translate(1371,107)" d="m0 0h24l17 2 15 4 16 7 13 9 11 11 9 14 6 14 4 16 2 18v16l-3 26-5 23-2 2-177 1-1 11v14l3 16 4 10 7 9 7 6 14 7 13 3 8 1h28l23-3 25-6 17-6 6-2v24l-1 34-18 6-24 6-33 5-12 1h-38l-27-4-16-5-14-6-12-8-9-8-9-11-8-14-6-18-3-15-1-8v-29l3-26 6-27 7-20 11-23 12-18 11-13 12-12 15-11 14-8 13-6 16-5 16-3zm-9 55-13 4-10 6-10 9-7 8-7 12-6 15v2h105l2-10v-8l-3-14-5-9-5-6-10-6-9-3z" fill="#CDD503"></path>
                            <path transform="translate(1751,113)" d="m0 0h192l3 2-7 50-4 6-14 17-11 14-11 13-11 14-12 14-11 14-11 13-8 10-9 11-13 16-11 13-10 13h114l-1 13-8 54-195 1-13-1 7-49 2-5 11-13 8-10 14-17 18-22 11-13 11-14 12-14 11-14 11-13 9-11 13-16 8-10h-111l2-17 7-48z" fill="#CDD503"></path>
                            <path transform="translate(1572,68)" d="m0 0h74l-2 19-4 25 68 1 1 1-1 14-6 42-1 1-69 1-13 92-5 36-1 21 2 9 5 8 10 4 15 1 21-2 12-2-2 16-6 42-1 2-20 4-26 3h-33l-19-3-12-5-9-6-7-8-5-11-3-14v-25l5-40 16-113 2-11-7 1h-43l2-17 6-40 1-1h47l2-8 5-36z" fill="#CDD503"></path>
                            <path transform="translate(136,109)" d="m0 0h161l1 2-8 54-2 1h-95l-2 19-6 39h92l-3 24-4 29-2 2h-91l-1 12-7 49-1 2h96l1 3-8 54-1 1h-161l1-13 38-270 1-7z" fill="#0175B2"></path>
                            <path transform="translate(725,109)" d="m0 0h161l-1 13-6 43-2 1h-95l-3 25-5 33h92l-7 52-2 3h-91l-2 19-6 42-1 2h95l2 2-8 55-1 1h-161l1-14 38-270z" fill="#0175B2"></path>
                            </svg>
                    </a>
                </div>



                <!-- Navigation Links -->
                <div class="flex space-x-8 sm:-my-px sm:ms-10">
                    <x-nav-link :href="route('bestand')" :active="request()->routeIs('bestand')" wire:navigate>
                        <div class="hidden md:flex">
                        {{ __('Lagerbestand') }}
                        </div>
                        <div class="flex md:hidden">
                            <x-heroicon-s-cube class="w-10" />
                        </div>
                    </x-nav-link>
                </div>

                @if(auth()->user()->hasBerechtigung('protokoll anzeigen'))
                <div class="hidden space-x-8 ">
                    <x-nav-link :href="route('protokoll')" :active="request()->routeIs('protokoll')" wire:navigate>

                        <div class="hidden md:flex md:-my-px md:ms-10">
                            {{ __('Buchungsprotokoll') }}
                        </div>
                        <div class="flex md:hidden w-10">
                            <x-heroicon-o-list-bullet class="w-10" />
                        </div>
                    </x-nav-link>
                </div>
                @endif

                <!-- Navigation Links -->
                @if(auth()->user()->hasBerechtigung('warenzugang buchen'))
                <div class="flex space-x-8 ">
                    <x-nav-link :href="route('lieferschein')" :active="request()->routeIs('lieferschein')" wire:navigate alt="Warenzugang" title="Warenzugang">

                        <div class="hidden md:flex ">
                        {{ __('Warenzugang') }}
                        </div>
                        <div class="flex md:hidden w-10 md:-my-px md:ms-10">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
  <path fill="currentColor" d="
    M100 20 L30 70 L30 180 L170 180 L170 70 Z

    M70 90 L70 180 L130 180 L130 90 Z

    M90 115 L110 115 L110 135 L120 135 L100 160 L80 135 L90 135 Z
  " fill-rule="evenodd"/>
</svg>

                        </div>
                    </x-nav-link>
                </div>
                @endif

                <!-- Navigation Links -->
                @if(auth()->user()->hasBerechtigung('warenzugang buchen'))
                <div class="hidden space-x-8">
                    <x-nav-link :href="route('etikettenerstellen')" :active="request()->routeIs('etikettenerstellen')" wire:navigate>
                        <div class="hidden md:flex md:-my-px md:ms-10">
                            {{ __('Etiketten erstellen') }}
                        </div>
                        <div class="flex md:hidden w-10" >
                            <x-heroicon-o-qr-code />
                        </div>
                    </x-nav-link>
                </div>
                @endif

                @if(auth()->user()->hasBerechtigung('einkaufsliste anzeigen'))
                <div class="flex space-x-8 ">
                    <x-nav-link :href="route('einkaufsliste')" :active="request()->routeIs('einkaufsliste')" wire:navigate>

                        <div class="hidden md:flex md:-my-px md:ms-10">
                            {{ __('Einkaufsliste') }}
                        </div>
                        <div class="flex md:hidden w-10">
                            <x-heroicon-s-shopping-bag />
                        </div>
                    </x-nav-link>
                </div>
                @endif

            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div >
                                Artikel buchen
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        @if(auth()->user()->hasBerechtigung('artikel buchen'))
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('artikel.entnahme')" wire:navigate>

                                    <div class="hidden md:flex ">
                                        {{ __('Entnahme') }}
                                    </div>

                                    <div class="flex md:hidden h-10">

                                        <svg xmlns="http://www.w3.org/2000/svg"

                                            viewBox="0 0 200 200">

                                        <!-- Zentrierte proportional skalierte Grafik -->
                                        <g transform="translate(5.78, 0) scale(0.3846)">

                                            <defs>
                                            <style type="text/css">
                                                <![CDATA[
                                                .str0 {stroke:#FEFEFE;stroke-width:16;stroke-miterlimit:22.9256}
                                                .fil1 {fill:#0876B3}
                                                .fil0 {fill:black;fill-rule:nonzero}
                                                ]]>
                                            </style>
                                            </defs>

                                            <g id="Ebene_x0020_1">
                                            <g>
                                                <path class="fil0" d="M255 3c-3,-2 -6,-3 -10,-3 -3,0 -7,1 -10,3l-217 126 226 132 226 -132 -217 -126zm236 159l-226 132 0 226 217 -127c6,-3 9,-10 9,-16 0,0 0,0 0,0l0 -216zm-264 358l0 -226 -226 -132 0 216c0,0 0,0 0,0 0,7 4,13 9,16l217 127 0 0z"/>
                                            </g>

                                            <polygon class="fil1 str0"
                                                points="245,388 428,205 297,205 297,132 245,132 194,132 194,205 62,205"/>
                                            </g>

                                        </g>
                                        </svg>
                                    </div>

                                </x-dropdown-link>
                            </div>
                        @endif

                        @if(auth()->user()->hasBerechtigung('artikel buchen'))
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('artikel.rueckgabe')" wire:navigate>


                                    <div class="hidden md:flex ">
                                        {{ __('Rückgabe') }}
                                    </div>

                                    <div class="flex md:hidden h-10">

                                        <svg xmlns="http://www.w3.org/2000/svg"

                                            viewBox="0 0 200 200">

                                        <!-- Proportional skaliert und horizontal zentriert -->
                                        <g transform="translate(5.8, 0) scale(0.10282)">

                                            <defs>
                                            <style type="text/css">
                                                <![CDATA[
                                                .str0 {stroke:#FEFEFE;stroke-width:61;stroke-miterlimit:22.9256}
                                                .fil1 {fill:#0876B3}
                                                .fil0 {fill:black;fill-rule:nonzero}
                                                ]]>
                                            </style>
                                            </defs>

                                            <g id="Ebene_x0020_1">
                                            <g>
                                                <path class="fil0" d="M952 10c-11,-6 -23,-10 -36,-10 -12,0 -25,3 -36,10l-811 473 846 494 846 -494 -811 -473zm881 595l-846 494 0 846 811 -473c22,-13 35,-36 35,-61 0,0 0,0 0,0l0 -806zm-987 1340l0 -846 -846 -494 0 806c0,0 0,0 0,0 0,25 13,48 35,61l811 473 0 0z"/>
                                            </g>

                                            <polygon class="fil1 str0"
                                                    points="917,492 232,1177 724,1177 724,1453 917,1453 1110,1453 1110,1177 1602,1177"/>
                                            </g>

                                        </g>
                                        </svg>


                                    </div>
                                </x-dropdown-link>
                            </div>
                        @endif

                        @if(auth()->user()->hasBerechtigung('artikel buchen'))
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('artikel.korrektur')" wire:navigate>
                                    {{ __('Korrektur') }}
                                </x-dropdown-link>
                            </div>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>


            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div >
                                Stammdaten
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        @if(auth()->user()->hasBerechtigung('debitor anzeigen'))
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('debitoren')" wire:navigate>
                                    {{ __('Debitoren') }}
                                </x-dropdown-link>
                            </div>
                        @endif

                        @if(auth()->user()->hasBerechtigung('artikel anzeigen'))
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('artikel')" wire:navigate>
                                    {{ __('Artikel') }}
                                </x-dropdown-link>
                            </div>
                        @endif

                        @if(auth()->user()->hasBerechtigung('mitarbeiter anzeigen'))

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('mitarbeiter')" wire:navigate>
                                    {{ __('Mitarbeiter') }}
                                </x-dropdown-link>
                            </div>
                        @endif

                        @if(auth()->user()->hasBerechtigung('psp anzeigen'))
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-dropdown-link :href="route('psp')" wire:navigate>
                                    {{ __('PSP-Elemente') }}
                                </x-dropdown-link>
                            </div>
                        @endif


                    </x-slot>
                </x-dropdown>
            </div>


            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>


</nav>
</div>
