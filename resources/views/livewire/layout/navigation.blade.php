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

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
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
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('bestand')" :active="request()->routeIs('bestand')" wire:navigate>
                        {{ __('Bestand') }}
                    </x-nav-link>
                </div>

                @if(auth()->user()->hasBerechtigung('protokoll anzeigen'))
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('protokoll')" :active="request()->routeIs('protokoll')" wire:navigate>
                        {{ __('Buchungsprotokoll') }}
                    </x-nav-link>
                </div>
                @endif

                @if(auth()->user()->hasBerechtigung('artikel buchen'))
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('scanartikel')" :active="request()->routeIs('scanartikel')" wire:navigate>
                        {{ __('Artikel Zu-/abbuchen') }}
                    </x-nav-link>
                </div>
                @endif

                @if(auth()->user()->hasBerechtigung('debitor anzeigen'))
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('debitoren')" :active="request()->routeIs('debitoren')" wire:navigate>
                        {{ __('Debitoren') }}
                    </x-nav-link>
                </div>
                @endif

                <!-- Navigation Links -->
                @if(auth()->user()->hasBerechtigung('warenzugang buchen'))
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('lieferschein')" :active="request()->routeIs('lieferschein')" wire:navigate>
                        {{ __('Warenzugang') }}
                    </x-nav-link>
                </div>
                @endif

                <!-- Navigation Links -->
                @if(auth()->user()->hasBerechtigung('warenzugang buchen'))
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('etikettenerstellen')" :active="request()->routeIs('etikettenerstellen')" wire:navigate>
                        {{ __('Etiketten erstellen') }}
                    </x-nav-link>
                </div>
                @endif

                <!-- Navigation Links -->
                @if(auth()->user()->hasBerechtigung('psp anzeigen'))
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('psp')" :active="request()->routeIs('psp')" wire:navigate>
                        {{ __('PSP-Elemente') }}
                    </x-nav-link>
                </div>
                @endif

                <!-- Navigation Links -->
                @if(auth()->user()->hasBerechtigung('artikel anzeigen'))
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('artikel')" :active="request()->routeIs('artikel')" wire:navigate>
                        {{ __('Artikel') }}
                    </x-nav-link>
                </div>
                @endif

                @if(auth()->user()->hasBerechtigung('mitarbeiter anzeigen'))
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('mitarbeiter')" :active="request()->routeIs('mitarbeiter')" wire:navigate>
                        {{ __('Mitarbeiter') }}
                    </x-nav-link>
                </div>
                @endif

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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('bestand')" :active="request()->routeIs('bestand')" wire:navigate>
                {{ __('Bestand') }}
            </x-responsive-nav-link>
        </div>

        @if(auth()->user()->hasBerechtigung('protokoll anzeigen'))
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('protokoll')" :active="request()->routeIs('protokoll')" wire:navigate>
                {{ __('Buchungsprotokoll') }}
            </x-responsive-nav-link>
        </div>
        @endif

        @if(auth()->user()->hasBerechtigung('artikel buchen'))
        <!-- Navigation Links -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('scanartikel')" :active="request()->routeIs('scanartikel')" wire:navigate>
                {{ __('Artikel Zu-/abbuchen') }}
            </x-responsive-nav-link>
        </div>
        @endif

        @if(auth()->user()->hasBerechtigung('debitor anzeigen'))
        <!-- Navigation Links -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('debitoren')" :active="request()->routeIs('debitoren')" wire:navigate>
                {{ __('Debitoren') }}
            </x-responsive-nav-link>
        </div>
        @endif

        <!-- Navigation Links -->
        @if(auth()->user()->hasBerechtigung('warenzugang buchen'))
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('lieferschein')" :active="request()->routeIs('lieferschein')" wire:navigate>
                {{ __('Warenzugang') }}
            </x-responsive-nav-link>
        </div>
        @endif


        <!-- Navigation Links -->
        @if(auth()->user()->hasBerechtigung('warenzugang buchen'))
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('etikettenerstellen')" :active="request()->routeIs('etikettenerstellen')" wire:navigate>
                {{ __('Etiketten erstellen') }}
            </x-responsive-nav-link>
        </div>
        @endif

        <!-- Navigation Links -->
        @if(auth()->user()->hasBerechtigung('psp anzeigen'))
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('psp')" :active="request()->routeIs('psp')" wire:navigate>
                {{ __('PSP-Elemente') }}
            </x-responsive-nav-link>
        </div>
        @endif

        <!-- Navigation Links -->
        @if(auth()->user()->hasBerechtigung('artikel anzeigen'))
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('artikel')" :active="request()->routeIs('artikel')" wire:navigate>
                {{ __('Artikel') }}
            </x-responsive-nav-link>
        </div>
        @endif

        @if(auth()->user()->hasBerechtigung('mitarbeiter anzeigen'))
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('mitarbeiter')" :active="request()->routeIs('mitarbeiter')" wire:navigate>
                {{ __('Mitarbeiter') }}
            </x-responsive-nav-link>
        </div>
        @endif







        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
