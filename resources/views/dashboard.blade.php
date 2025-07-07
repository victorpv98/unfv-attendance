<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark lh-sm">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container-xxl mx-auto px-3 px-sm-4 px-lg-5">
            <div class="bg-white overflow-hidden shadow-sm rounded">
                <div class="p-4 text-dark">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>