<div>
    <div class="mb-6">
        <flux:button
            onclick="history.back()"
            class="flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </flux:button>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-2xl leading-tight">
                Business Profile
            </h2>
            <p class="font-light text-sm leading-tight">
                {{ $business ? 'Edit your business profile' : 'Create your business profile' }}
            </p>
        </div>

        @if (session('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg my-4">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg my-4">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit="saveProfile" class="my-6 w-full space-y-6">
            @csrf

            <!-- Business Name -->
            <div class="flex flex-col items-start">
                <flux:label>Business Name *</flux:label>
                <flux:input
                    wire:model="business_name"
                    placeholder="e.g. Kofi's Bakery"
                    required
                />
                @error('business_name') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
            </div>

            <!-- Slogan -->
            <div class="flex flex-col items-start">
                <flux:label>Slogan</flux:label>
                <flux:input
                    wire:model="slogan"
                    placeholder="e.g. Fresh bread daily!"
                />
                @error('slogan') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
            </div>

            <!-- Description -->
            <div class="flex flex-col items-start">
                <flux:label>Description</flux:label>
                <flux:textarea
                    wire:model="description"
                    placeholder="Describe your business and what makes it special..."
                    rows="4"
                />
                @error('description') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col items-start">
                    <flux:label>Phone</flux:label>
                    <flux:input
                        wire:model="phone"
                        placeholder="+233 123 456 789"
                    />
                    @error('phone') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <div class="flex flex-col items-start">
                    <flux:label>Email</flux:label>
                    <flux:input
                        wire:model="email"
                        type="email"
                        placeholder="hello@business.com"
                    />
                    @error('email') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <div class="flex flex-col items-start">
                    <flux:label>Website</flux:label>
                    <flux:input
                        wire:model="website"
                        type="url"
                        placeholder="https://yourbusiness.com"
                    />
                    @error('website') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <div class="flex flex-col items-start">
                    <flux:label>Location</flux:label>
                    <flux:input
                        wire:model="location"
                        placeholder="Accra, Ghana"
                    />
                    @error('location') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Image Uploads (Base64) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cover Image -->
                <div class="flex flex-col items-start">
                    <flux:label>Cover Image</flux:label>

                    <!-- Hidden input for base64 -->
                    <input id="coverImageText" class="sr-only" wire:model="coverImageBase64" />

                    <!-- File picker -->
                    <label for="coverImageInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg cursor-pointer  text-left">
                        <span wire:loading.remove wire:target="coverImageBase64">Choose cover image</span>
                        <span wire:loading wire:target="coverImageBase64" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                        </span>
                        <input
                            id="coverImageInput"
                            type="file"
                            class="sr-only"
                            accept="image/*"
                            onchange="handleImageUpload(event, 'coverImageText')"
                        />
                    </label>

                    <!-- Preview: New cover image (base64) -->
                    @if($coverImageBase64)
                        <div class="mt-2 relative">
                            <img
                                src="{{ $coverImageBase64 }}"
                                alt="New Cover Preview"
                                class="h-20 w-full object-cover rounded-lg"
                            >
                            <button
                                type="button"
                                wire:click="$set('coverImageBase64', '')"
                                class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full text-xs"
                            >×</button>
                            <p class="text-xs text-gray-500 mt-1">New cover preview</p>
                        </div>
                    @endif

                    <!-- Preview: Existing cover image -->
                    @if($business && $business->cover_image && !$coverImageBase64)
                        <div class="mt-2 relative">
                            <img
                                src="{{ $business->cover_image }}"
                                alt="Cover"
                                class="h-20 w-full object-cover rounded-lg"
                            >
                            <button
                                type="button"
                                wire:click="removeCoverImage"
                                class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full text-xs"
                            >×</button>
                        </div>
                    @endif

                    @error('coverImageBase64') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <!-- Profile Image -->
                <div class="flex flex-col items-start">
                    <flux:label>Profile Image</flux:label>

                    <input id="profileImageText" class="sr-only" wire:model="profileImageBase64" />

                    <label for="profileImageInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg cursor-pointer  text-left">
                        <span wire:loading.remove wire:target="profileImageBase64">Choose profile image</span>
                        <span wire:loading wire:target="profileImageBase64" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                        </span>
                        <input
                            id="profileImageInput"
                            type="file"
                            class="sr-only"
                            accept="image/*"
                            onchange="handleImageUpload(event, 'profileImageText')"
                        />
                    </label>

                    @if($profileImageBase64)
                        <div class="mt-2 relative inline-block">
                            <img
                                src="{{ $profileImageBase64 }}"
                                alt="New Profile Preview"
                                class="h-20 w-20 rounded-full object-cover"
                            >
                            <button
                                type="button"
                                wire:click="$set('profileImageBase64', '')"
                                class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full text-xs"
                            >×</button>
                            <p class="text-xs text-gray-500 mt-1">New profile preview</p>
                        </div>
                    @endif

                    @if($business && $business->profile_image && !$profileImageBase64)
                        <div class="mt-2 relative inline-block">
                            <img
                                src="{{ $business->profile_image }}"
                                class="h-20 w-20 rounded-full object-cover"
                            >
                            <button
                                type="button"
                                wire:click="removeProfileImage"
                                class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full text-xs"
                            >×</button>
                        </div>
                    @endif

                    @error('profileImageBase64') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Social Links -->
            <div class="space-y-4">
                <flux:label>Social Links</flux:label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="social_links.facebook" placeholder="Facebook URL" />
                    <flux:input wire:model="social_links.instagram" placeholder="Instagram URL" />
                    <flux:input wire:model="social_links.twitter" placeholder="Twitter URL" />
                    <flux:input wire:model="social_links.linkedin" placeholder="LinkedIn URL" />
                </div>
            </div>

            <!-- Theme Selection -->
            @if($business)
                <div class="space-y-4">
                    <flux:label>Theme Selection</flux:label>
                    <p class="text-sm text-gray-600">Choose a color theme for your business profile</p>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach($themes as $name => $colors)
                            <button
                                type="button"
                                wire:click="selectTheme('{{ $name }}')"
                                class="relative p-4 rounded-lg border-2 transition-all duration-200 hover:shadow-md {{ $business->theme_colors && $business->theme_colors['primary'] === $colors['primary'] ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200' }}"
                                title="{{ ucfirst($name) }} Theme"
                            >
                                <div class="flex flex-col gap-2">
                                    <div class="h-6 rounded-md w-full" style="background-color: {{ $colors['primary'] }}"></div>
                                    <div class="h-4 rounded-md w-full" style="background-color: {{ $colors['secondary'] }}"></div>
                                    <div class="h-3 rounded-md w-full" style="background-color: {{ $colors['accent'] }}"></div>
                                </div>
                                <span class="block text-xs font-medium mt-2 text-gray-700 text-center">
                                    {{ ucfirst($name) }}
                                </span>

                                @if($business->theme_colors && $business->theme_colors['primary'] === $colors['primary'])
                                    <div class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    @if($business->theme_colors)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Theme Colors:</p>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded border" style="background-color: {{ $business->theme_colors['primary'] }}"></div>
                                    <span class="text-xs text-gray-600">Primary</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded border" style="background-color: {{ $business->theme_colors['secondary'] }}"></div>
                                    <span class="text-xs text-gray-600">Secondary</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded border" style="background-color: {{ $business->theme_colors['accent'] }}"></div>
                                    <span class="text-xs text-gray-600">Accent</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Publish Toggle -->
            <flux:field variant="inline">
                <flux:checkbox wire:model="is_published" value="1" />
                <flux:label>Publish my business profile</flux:label>
                <flux:error name="is_published" />
            </flux:field>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4">
                <flux:button variant="primary" type="submit" class="w-full md:w-auto" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $business ? 'Update Profile' : 'Create Profile' }}</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </flux:button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        function handleImageUpload(event, targetInputId) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const el = document.getElementById(targetInputId);
                if (el) {
                    el.value = e.target.result; // base64 string
                    el.dispatchEvent(new Event('input'));
                }
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush
