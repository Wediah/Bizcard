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
        <div class="border rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-4">Manage Services</h2>

            @if (session('message'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Add/Edit Service Form -->
            <form wire:submit="addService" class="mb-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Service Name -->
                    <div class="md:col-span-2">
                        <flux:label>Service Name *</flux:label>
                        <flux:input wire:model="newService.name" placeholder="e.g. Web Development" />
                        @error('newService.name') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <flux:label>Description</flux:label>
                        <flux:textarea wire:model="newService.description" placeholder="Describe your service..." rows="3" />
                        @error('newService.description') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <flux:label>Price (₵)</flux:label>
                        <flux:input wire:model="newService.price" type="number" step="0.01" placeholder="0.00" />
                        @error('newService.price') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <!-- Image (Base64 Upload) -->
                    <div>
                        <flux:label>Service Image</flux:label>

                        <!-- Hidden input for base64 -->
                        <input id="serviceImageText" class="sr-only" wire:model="serviceImageBase64" />

                        <!-- Styled file picker -->
                        <label for="serviceImageInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg cursor-pointer text-left block">
                            @if($serviceFileName)
                                <span class="text-sm text-gray-700">{{ $serviceFileName }}</span>
                            @endif
                            <span wire:loading wire:target="serviceImageBase64" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </span>
                            <input
                                id="serviceImageInput"
                                type="file"
                                accept="image/*"
                                onchange="handleImageUpload(event, 'serviceImageText', 'serviceFileName')"
                            />
                        </label>

                        <!-- Preview: New image -->
                        @if($serviceImageBase64)
                            <div class="mt-2">
                                <img src="{{ $serviceImageBase64 }}" alt="service image" class="h-20 w-20 object-cover rounded-lg">
                                <p class="text-xs text-gray-500">New image preview</p>
                                <button
                                    type="button"
                                    wire:click="$set('serviceImageBase64', ''); $set('serviceFileName', '');"
                                    class="text-xs text-red-500 mt-1"
                                >Remove</button>
                            </div>
                        @elseif($newService['image'])
                            <!-- Preview: Existing image -->
                            <div class="mt-2">
                                <img src="{{ $newService['image'] }}" class="h-20 w-20 object-cover rounded-lg">
                                <p class="text-xs text-gray-500">Current image</p>
                            </div>
                        @endif

                        @error('serviceImageBase64') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <!-- Availability -->
                    <div class="md:col-span-2">
                        <flux:field variant="inline">
                            <flux:checkbox wire:model="newService.is_available" value="true" />
                            <flux:label>Service is available</flux:label>
                        </flux:field>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4">
                    @if($editingServiceId)
                        <flux:button variant="secondary" type="button" wire:click="cancelEdit">Cancel</flux:button>
                    @endif
                    <flux:button variant="primary" type="submit">
                        {{ $editingServiceId ? 'Update Service' : 'Add Service' }}
                    </flux:button>
                </div>
            </form>

            <!-- Services List -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Your Services</h3>

                @if(count($services) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($services as $service)
                            <div class="border border-gray-200 rounded-lg p-4">
                                @if($service['image'])
                                    <img src="{{ $service['image'] }}" class="h-32 w-full object-cover rounded-lg mb-3">
                                @endif
                                <h4 class="font-semibold">{{ $service['name'] }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $service['description'] }}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="font-semibold text-green-600">₵{{ number_format($service['price'], 2) }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $service['is_available'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $service['is_available'] ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button wire:click="editService({{ $service['id'] }})" class="text-blue-600 text-sm">Edit</button>
                                    <button wire:click="toggleServiceAvailability({{ $service['id'] }})" class="text-orange-600 text-sm">
                                        {{ $service['is_available'] ? 'Make Unavailable' : 'Make Available' }}
                                    </button>
                                    <button wire:click="deleteService({{ $service['id'] }})" class="text-red-600 text-sm"
                                            onclick="return confirm('Are you sure you want to delete this service?')">Delete</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        No services added yet. Add your first service above.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function handleImageUpload(event, targetInputId, fileNameProperty) {
            const file = event.target.files[0];
            if (!file) return;

            // Update file name in Livewire
            if (window.Livewire) {
                window.Livewire.dispatch('setServiceFileName', { name: file.name });
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const el = document.getElementById(targetInputId);
                if (el) {
                    el.value = e.target.result;
                    el.dispatchEvent(new Event('input'));
                }
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush
