<div>
    <!-- Back Navigation -->
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

    <div class="max-w-2xl mx-auto">
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
                <!-- Phone -->
                <div class="flex flex-col items-start">
                    <flux:label>Phone</flux:label>
                    <flux:input
                        wire:model="phone"
                        placeholder="+233 123 456 789"
                    />
                    @error('phone') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <!-- Email -->
                <div class="flex flex-col items-start">
                    <flux:label>Email</flux:label>
                    <flux:input
                        wire:model="email"
                        type="email"
                        placeholder="hello@business.com"
                    />
                    @error('email') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <!-- Website -->
                <div class="flex flex-col items-start">
                    <flux:label>Website</flux:label>
                    <flux:input
                        wire:model="website"
                        type="url"
                        placeholder="https://yourbusiness.com"
                    />
                    @error('website') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <!-- Location -->
                <div class="flex flex-col items-start">
                    <flux:label>Location</flux:label>
                    <flux:input
                        wire:model="location"
                        placeholder="Accra, Ghana"
                    />
                    @error('location') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Image Uploads -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cover Image -->
                <div class="flex flex-col items-start">
                    <flux:label>Cover Image</flux:label>
                    <input
                        type="file"
                        wire:model="coverImage"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        accept="image/*"
                    />

                    <!-- Preview for newly selected cover image -->
                    @if ($coverImage)
                        <div class="mt-2 relative">
                            <img src="{{ $coverImage->temporaryUrl() }}" class="h-20 w-full object-cover rounded-lg">
                            <button type="button" wire:click="$set('coverImage', null)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full text-xs">
                                ×
                            </button>
                            <p class="text-xs text-gray-500 mt-1">New cover image preview</p>
                        </div>
                    @endif

                    <!-- Existing cover image -->
                    @if ($business && $business->cover_image && !$coverImage)
                        <div class="mt-2 relative">
                            <img src="{{ $business->cover_image }}" class="h-20 w-full object-cover rounded-lg">
                            <button type="button" wire:click="removeCoverImage" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full text-xs">
                                ×
                            </button>
                        </div>
                    @endif

                    @error('coverImage') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>

                <!-- Profile Image -->
                <div class="flex flex-col items-start">
                    <flux:label>Profile Image</flux:label>
                    <input
                        type="file"
                        wire:model="profileImage"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        accept="image/*"
                    />

                    <!-- Preview for newly selected profile image -->
                    @if ($profileImage)
                        <div class="mt-2 relative inline-block">
                            <img src="{{ $profileImage->temporaryUrl() }}" class="h-20 w-20 rounded-full object-cover">
                            <button type="button" wire:click="$set('profileImage', null)" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full text-xs">
                                ×
                            </button>
                            <p class="text-xs text-gray-500 mt-1">New profile image preview</p>
                        </div>
                    @endif

                    <!-- Existing profile image -->
                    @if ($business && $business->profile_image && !$profileImage)
                        <div class="mt-2 relative inline-block">
                            <img src="{{ $business->profile_image }}" class="h-20 w-20 rounded-full object-cover">
                            <button type="button" wire:click="removeProfileImage" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full text-xs">
                                ×
                            </button>
                        </div>
                    @endif

                    @error('profileImage') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Social Links -->
            <div class="space-y-4">
                <flux:label>Social Links</flux:label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input
                        wire:model="social_links.facebook"
                        placeholder="Facebook URL"
                    />
                    <flux:input
                        wire:model="social_links.instagram"
                        placeholder="Instagram URL"
                    />
                    <flux:input
                        wire:model="social_links.twitter"
                        placeholder="Twitter URL"
                    />
                    <flux:input
                        wire:model="social_links.linkedin"
                        placeholder="LinkedIn URL"
                    />
                </div>
            </div>

            <!-- Publish Toggle -->
            <flux:field variant="inline">
                <flux:checkbox wire:model="is_published" value="1" />
                <flux:label>Publish my business profile</flux:label>
                <flux:error name="is_published" />
            </flux:field>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4">
{{--                @if($business)--}}
{{--                    <flux:button variant="secondary" type="button" wire:click="togglePublish" class="w-full md:w-auto">--}}
{{--                        {{ $is_published ? 'Unpublish' : 'Publish Now' }}--}}
{{--                    </flux:button>--}}
{{--                @endif--}}

                <flux:button variant="primary" type="submit" class="w-full md:w-auto">
                    {{ $business ? 'Update Profile' : 'Create Profile' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
