<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Photo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your profile photo.') }}
        </p>
    </header>

    <div class="flex items-center gap-4">
        @if (auth()->user()->profile_photo_path)
            <div class="relative">
                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" 
                     alt="Profile Photo" 
                     class="w-20 h-20 rounded-full object-cover">
            </div>
        @endif

        <input type="file" 
               name="profile_photo" 
               id="profile_photo" 
               accept="image/*"
               class="block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-green-50 file:text-green-700
                      hover:file:bg-green-100">
    </div>
</section>
