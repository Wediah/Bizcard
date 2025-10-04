<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Manage Reviews</h2>

        @if (session('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="text-2xl font-bold text-blue-900">{{ $reviews->total() }}</div>
                <div class="text-blue-700">Total Reviews</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="text-2xl font-bold text-green-900">
                    {{ $reviews->where('is_approved', true)->count() }}
                </div>
                <div class="text-green-700">Approved</div>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                <div class="text-2xl font-bold text-orange-900">
                    {{ $reviews->where('is_approved', false)->count() }}
                </div>
                <div class="text-orange-700">Pending</div>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Customer</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Rating</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Comment</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 text-sm text-gray-900">
                            {{ $review->customer_name }}
                            @if($review->user)
                                <span class="text-xs text-gray-500">(Registered)</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    ★
                                @endfor
                                <span class="ml-1 text-gray-600 text-sm">{{ $review->rating }}/5</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700 max-w-md">
                            <p class="line-clamp-2">{{ $review->comment }}</p>
                            @if($review->business_response)
                                <div class="mt-2 p-2 bg-blue-50 rounded text-xs">
                                    <strong>Your Response:</strong> {{ $review->business_response }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">
                            {{ $review->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <div class="flex flex-col gap-2">
                                @if(!$review->is_approved)
                                    <button wire:click="approveReview({{ $review->id }})"
                                            class="text-green-600 hover:text-green-800 text-xs">
                                        Approve
                                    </button>
                                @endif

                                @if(!$review->is_approved)
                                    <button wire:click="rejectReview({{ $review->id }})"
                                            class="text-red-600 hover:text-red-800 text-xs"
                                            onclick="return confirm('Reject this review?')">
                                        Reject
                                    </button>
                                @else
                                    <button wire:click="deleteReview({{ $review->id }})"
                                            class="text-red-600 hover:text-red-800 text-xs"
                                            onclick="return confirm('Delete this review?')">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            No reviews yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- Response Modal -->
    @if($selectedReview)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Respond to {{ $selectedReview->customer_name }}'s Review
                </h3>

                <form wire:submit="submitResponse">
                    <div class="mb-4">
                        <flux:label>Your Response</flux:label>
                        <flux:textarea wire:model="businessResponse" rows="4"
                                       placeholder="Write a professional response to this review..." />
                        @error('businessResponse') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-4">
                        <flux:button variant="secondary" type="button" wire:click="cancelResponse">
                            Cancel
                        </flux:button>
                        <flux:button variant="primary" type="submit">
                            Submit Response
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
