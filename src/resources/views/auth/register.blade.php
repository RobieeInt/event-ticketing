<x-guest-layout>
    @php
        $otpSent = session('otp_sent', false);
    @endphp

    {{-- status message --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST">
        @csrf

        {{-- =========================
            STEP 1: Name + Email + Phone
        ========================== --}}
        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name') }}"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" value="Phone" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                value="{{ old('phone') }}" required inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        {{-- =========================
            STEP 2: OTP + Password (muncul setelah Kirim OTP)
        ========================== --}}
        @if ($otpSent)
            <div class="mt-6 border-t pt-6">
                <div class="mb-2 text-sm text-gray-600">
                    OTP sudah dikirim ke email kamu. Masukkan OTP + buat password untuk menyelesaikan registrasi.
                </div>

                <div class="mt-4">
                    <x-input-label for="code" value="Kode OTP (6 digit)" />
                    <x-text-input id="code" class="block mt-1 w-full" type="text" name="code"
                        inputmode="numeric" maxlength="6" value="{{ old('code') }}" required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" value="Confirm Password" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div class="mt-6 flex flex-col gap-3">
            {{-- STEP 1 ACTION --}}
            @if (!$otpSent)
                <x-primary-button class="w-full justify-center" formaction="{{ route('register.requestOtp') }}">
                    Kirim OTP ke Email
                </x-primary-button>
            @else
                {{-- STEP 2 ACTION --}}
                <x-primary-button class="w-full justify-center" formaction="{{ route('register.verifyOtp') }}">
                    Verifikasi & Buat Akun
                </x-primary-button>

                <x-secondary-button class="w-full justify-center" formaction="{{ route('register.resendOtp') }}">
                    Kirim Ulang OTP
                </x-secondary-button>
            @endif

            <a class="text-center underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Already registered?
            </a>
        </div>
    </form>
</x-guest-layout>
