<?php

use App\Models\User;
use App\Models\Debitor;
use App\Models\Abladestelle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    public $debitoren = [];
    public $abladestellen = [];
    public ?string $camera_device_id = null;


    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->camera_device_id = Auth::user()->camera_device_id;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'camera_device_id' => ['nullable', 'string'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->camera_device_id = $this->camera_device_id;
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('bestand', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[On('camera-selected')]
    public function kameraGewechselt($id)
    {
        $this->camera_device_id = $id;
    }


}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Aktualisieren Sie Ihre Profil Informationen und Ihre E-Mail-Adresse') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>

            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('E-Mail')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="cameraSelection" :value="__('Kameraauswahl')" />
            <div class="flex flex-row items-center space-x-4">
                <select name="cameraSelection" id="cameraSelection"
                        wire:model="camera_device_id"
                        class="h-10 rounded"></select>
                <div id="reader" style="width: 200px; height: 200px; border:1px solid #ccc;"></div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Speichern') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Speichern.') }}
            </x-action-message>
        </div>
    </form>
</section>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        let currentCameraId = null;
        let cameraSelectInitialized = false;

        function startScanner() {
            html5QrCode = new Html5Qrcode("reader");

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraSelect = document.getElementById('cameraSelection');



                    if (!cameraSelectInitialized) {
                        devices.forEach(device => {
                            const option = document.createElement('option');
                            option.value = device.id;
                            option.text = device.label;
                            cameraSelect.appendChild(option);
                        });

                        cameraSelect.addEventListener('change', async () => {
                            const newCameraId = cameraSelect.value;

                            // an Livewire senden
                            Livewire.dispatch('camera-selected', { id: newCameraId });

                            if (html5QrCode && currentCameraId !== newCameraId) {
                                await html5QrCode.stop();
                                currentCameraId = newCameraId;
                                html5QrCode.start(
                                    currentCameraId, {
                                        fps: 10,
                                        qrbox: {
                                            width: 250,
                                            height: 250
                                        }
                                    },
                                    onScanSuccess
                                ).catch(err => console.error("Start-Fehler:", err));
                            }
                        });

                        cameraSelectInitialized = true;
                    }

                    let savedCameraId = @json($camera_device_id);

                    if (savedCameraId) {
                        // prüfen, ob diese Kamera existiert
                        const exists = devices.some(dev => dev.id === savedCameraId);

                        if (exists) {
                            currentCameraId = savedCameraId;
                            cameraSelect.value = savedCameraId;

                            // Scanner sofort mit gespeicherter Kamera starten
                            html5QrCode.start(
                                savedCameraId,
                                { fps: 10, qrbox: { width: 250, height: 250 }},
                                onScanSuccess
                            ).catch(err => console.error("Start-Fehler:", err));

                            return; // fertig → keine weitere Kamera auswählen
                        }
                    }

                    // FALLBACK: erste Kamera verwenden
                    if (!currentCameraId) {
                        currentCameraId = devices[0].id;
                        cameraSelect.value = currentCameraId;
                    }


                }
            }).catch(err => console.error("Kamera-Fehler:", err));
        }

        function onScanSuccess(decodedText) {
            console.log("QR erkannt:", decodedText);
            Livewire.dispatch('qrcode-scanned', [String(decodedText)]);



            // Scanner kurz stoppen, um Doppel-Scans zu vermeiden
            html5QrCode.stop().then(() => {
                console.log("Scanner gestoppt");
                setTimeout(() => startScanner(), 1500);
            });
        }
        window.addEventListener('scan-processed', () => {
            console.log("Browser-Event 'scan-processed' empfangen!");

            // kleinen Timeout, damit DOM fertig ist
            setTimeout(() => {
                const inputs = document.querySelectorAll('input[type="number"][wire\\:model$=".Menge"]');
                if (inputs.length > 0) {
                    const lastInput = inputs[inputs.length - 1];
                    lastInput.focus();
                    lastInput.select();
                    console.log("Fokus gesetzt auf letzte Menge!");
                }
            }, 50);
        });

        document.addEventListener("livewire:navigated", startScanner);
    </script>
@endpush
