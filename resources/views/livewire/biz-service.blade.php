<div class="max-w-4xl mx-auto">
    <div class="border rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold  mb-4">Manage Services</h2>

        @if (session('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Add/Edit Service Form -->
        <form wire:submit="addService" class="mb-6 space-y-4">
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

                <!-- Image -->
                <div>
                    <flux:label>Service Image</flux:label>
                    <input type="file" wire:model="serviceImage" class="w-full px-3 py-2 border border-gray-300 rounded-lg" accept="image/*" />
                    @if ($serviceImage)
                        <div class="mt-2">
                            <img src="{{ $serviceImage->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg">
                            <p class="text-xs text-gray-500">New image preview</p>
                        </div>
                    @elseif($newService['image'])
                        <div class="mt-2">
                            <img src="{{ $newService['image'] }}" class="h-20 w-20 object-cover rounded-lg">
                            <p class="text-xs text-gray-500">Current image</p>
                        </div>
                    @endif
                    @error('serviceImage') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
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
                    <flux:button variant="primary" type="button" wire:click="cancelEdit">Cancel</flux:button>
                @endif
                <flux:button variant="primary" type="submit">
                    {{ $editingServiceId ? 'Update Service' : 'Add Service' }}
                </flux:button>
            </div>
        </form>

        <!-- Services List -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold ">Your Services</h3>

            @if(count($services) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($services as $service)
                        <div class="border border-gray-200 rounded-lg p-4">
                            @if($service['image'])
                                <img src="{{ $service['image'] }}" class="h-32 w-full object-cover rounded-lg mb-3">
                            @endif
                            <h4 class="font-semibold ">{{ $service['name'] }}</h4>
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
