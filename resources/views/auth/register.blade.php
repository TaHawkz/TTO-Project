<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Optional: Department -->
        <div class="mt-4">
            <x-input-label for="department" :value="__('Department (optional)')" />
            <x-text-input id="department" class="block mt-1 w-full" type="text" name="department"
                          :value="old('department')" autocomplete="organization" />
            <x-input-error :messages="$errors->get('department')" class="mt-2" />
        </div>

        <!-- Optional: Designation -->
        <div class="mt-4">
            <x-input-label for="designation" :value="__('Designation (optional)')" />
            <x-text-input id="designation" class="block mt-1 w-full" type="text" name="designation"
                          :value="old('designation')" />
            <x-input-error :messages="$errors->get('designation')" class="mt-2" />
        </div>

        <!-- Optional: Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone (optional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                          :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <p class="mt-3 text-xs text-gray-500">
            Your account will be created with the <strong>Student</strong> role. A TTO administrator can update your role after registration.
        </p>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
