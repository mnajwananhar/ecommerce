@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

@endphp
<x-app-layout>
    @section('title', 'Edit Profile') <!-- Title halaman Produk -->


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Photo Form -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- Display Current Profile Photo -->
                    <div class="mb-4">
                        @if (Auth::user()->profile_photo)
                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}"
                                class="rounded-full h-20 w-20 object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700">Profile
                                Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                        </div>

                        <x-primary-button
                            class="mt-4 bg-[#FF9C08] hover:bg-[#e68a00]">{{ __('Save Photo') }}</x-primary-button>
                    </form>
                </div>
            </div>

            <!-- Profile Information Form -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
