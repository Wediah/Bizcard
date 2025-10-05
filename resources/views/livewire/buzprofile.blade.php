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

        <form wire:submit="saveProfile" class="my-6 w-full space-y-6" enctype="multipart/form-data">
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
                            <img
                                src="{{ $coverImage->temporaryUrl() }}"
                                alt="New Cover Image"
                                class="h-20 w-full object-cover rounded-lg"
                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiM5Y2EiIGlkPSJub3Rmb3VuZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5ldyBJbWFnZSBBZGRlZCAoQ2hlY2sgQ29uc29sZSk8L3RleHQ+PC9zdmc+'"
                            >
                            <button type="button" wire:click="$set('coverImage', null)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full text-xs">
                                ×
                            </button>
                            <p class="text-xs text-gray-500 mt-1">New cover image preview</p>
                        </div>
                    @endif

                    <!-- Existing cover image -->
                    @if ($business && $business->cover_image && !$coverImage)
                        <div class="mt-2 relative">
                            <img src="{{ $business->cover_image }}" alt="cover image" class="h-20 w-full object-cover rounded-lg">
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
                            <img
                                src="{{ $profileImage->temporaryUrl() }}"
                                alt="New Profile Image"
                                class="h-20 w-20 rounded-full object-cover"
                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHRleHQgeD0iNTAiIHk9IjUwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiM5Y2EiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5OZXcgUHJvZmlsZSBJbWFnZTwvdGV4dD48L3N2Zz4='"
                            >
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
                                    <!-- Primary Color -->
                                    <div
                                        class="h-6 rounded-md w-full"
                                        style="background-color: {{ $colors['primary'] }}"
                                    ></div>
                                    <!-- Secondary Color -->
                                    <div
                                        class="h-4 rounded-md w-full"
                                        style="background-color: {{ $colors['secondary'] }}"
                                    ></div>
                                    <!-- Accent Color -->
                                    <div
                                        class="h-3 rounded-md w-full"
                                        style="background-color: {{ $colors['accent'] }}"
                                    ></div>
                                </div>
                                <span class="block text-xs font-medium mt-2 text-gray-700 text-center">
                                    {{ ucfirst($name) }}
                                </span>

                                <!-- Selected indicator -->
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

                    <!-- Current Theme Display -->
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
                    {{ $business ? 'Update Profile' : 'Create Profile' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
