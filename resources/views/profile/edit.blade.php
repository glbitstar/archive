<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
            <hr>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
            <hr>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
            <div>
            <a href="{{ route('landing_pages.index') }}"><<戻る</a>
        </div>
        </div>

    </div>
</x-guest-layout>