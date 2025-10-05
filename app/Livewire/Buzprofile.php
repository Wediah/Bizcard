<?php

namespace App\Livewire;

use App\Models\Profile;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Buzprofile extends Component
{
    // Remove: use WithFileUploads;

    // Base64 strings instead of file uploads
    public $coverImageBase64 = '';
    public $profileImageBase64 = '';

    // Form fields
    public $business_name;
    public $slogan;
    public $description;
    public $phone;
    public $email;
    public $website;
    public $location;
    public $is_published = false;
    public $social_links = [];

    public $business; // Keep for image operations

    // Theme options
    public $themes = [
        'modern' => ['primary' => '#3B82F6', 'secondary' => '#1E40AF', 'accent' => '#F59E0B'],
        'professional' => ['primary' => '#374151', 'secondary' => '#111827', 'accent' => '#047857'],
        'creative' => ['primary' => '#8B5CF6', 'secondary' => '#7C3AED', 'accent' => '#EC4899'],
        'minimal' => ['primary' => '#6B7280', 'secondary' => '#374151', 'accent' => '#EF4444'],
        'warm' => ['primary' => '#DC2626', 'secondary' => '#B91C1C', 'accent' => '#D97706'],
    ];

    protected $rules = [
        'business_name' => 'required|min:2',
        'slogan' => 'nullable|min:2',
        'description' => 'nullable|min:10',
        'phone' => 'nullable',
        'email' => 'nullable|email',
        'website' => 'nullable|url',
        'location' => 'nullable',
        'is_published' => 'boolean',
        // Note: image validation done manually in saveProfile()
    ];

    public function mount(): void
    {
        $this->business = Profile::where('user_id', auth()->id())->first();

        if ($this->business) {
            $this->business_name = $this->business->business_name;
            $this->slogan = $this->business->slogan;
            $this->description = $this->business->description;
            $this->phone = $this->business->phone;
            $this->email = $this->business->email;
            $this->website = $this->business->website;
            $this->location = $this->business->location;
            $this->is_published = $this->business->is_published;
            $this->social_links = $this->business->social_links ?? [
                'facebook' => '',
                'instagram' => '',
                'twitter' => '',
                'linkedin' => ''
            ];
        } else {
            $this->social_links = [
                'facebook' => '',
                'instagram' => '',
                'twitter' => '',
                'linkedin' => ''
            ];
        }
    }

    public function saveProfile(): void
    {
        $this->validate();

        if (!$this->business) {
            $this->business = new Profile();
            $this->business->user_id = auth()->id();
            $this->business->theme_colors = $this->themes['modern'];
        }

        // Update business data
        $this->business->business_name = $this->business_name;
        $this->business->slogan = $this->slogan;
        $this->business->description = $this->description;
        $this->business->phone = $this->phone;
        $this->business->email = $this->email;
        $this->business->website = $this->website;
        $this->business->location = $this->location;
        $this->business->is_published = $this->is_published;
        $this->business->social_links = $this->social_links;

        try {
            // Handle Cover Image
            if ($this->coverImageBase64) {
                if ($this->business->cover_image) {
                    $oldPath = $this->extractS3PathFromUrl($this->business->cover_image);
                    if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
                        Storage::disk('s3')->delete($oldPath);
                    }
                }
                $path = $this->uploadBase64ToS3($this->coverImageBase64, 'business/covers');
                $this->business->cover_image = Storage::disk('s3')->url($path);
            }

            // Handle Profile Image
            if ($this->profileImageBase64) {
                if ($this->business->profile_image) {
                    $oldPath = $this->extractS3PathFromUrl($this->business->profile_image);
                    if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
                        Storage::disk('s3')->delete($oldPath);
                    }
                }
                $path = $this->uploadBase64ToS3($this->profileImageBase64, 'business/profiles');
                $this->business->profile_image = Storage::disk('s3')->url($path);
            }

            $this->business->save();

            // Clear base64 after successful upload
            $this->coverImageBase64 = '';
            $this->profileImageBase64 = '';

            $action = $this->business->wasRecentlyCreated ? 'created' : 'updated';
            session()->flash('message', "Profile {$action} successfully!");

        } catch (\Exception $e) {
            \Log::error('Buzprofile save failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'cover_provided' => !empty($this->coverImageBase64),
                'profile_provided' => !empty($this->profileImageBase64),
            ]);

            $this->addError('coverImageBase64', 'Image upload failed: ' . $e->getMessage());
            return;
        }
    }

    public function selectTheme($themeName): void
    {
        if ($this->business && array_key_exists($themeName, $this->themes)) {
            $this->business->theme_colors = $this->themes[$themeName];
            $this->business->save();
            $this->business->refresh();
            session()->flash('message', ucfirst($themeName) . ' theme applied successfully!');
        }
    }

    public function togglePublish(): void
    {
        if ($this->business) {
            $this->business->is_published = !$this->business->is_published;
            $this->business->save();
            $this->is_published = $this->business->is_published;
            $status = $this->business->is_published ? 'published' : 'unpublished';
            session()->flash('message', "Profile {$status} successfully!");
        }
    }

    public function removeCoverImage(): void
    {
        if ($this->business && $this->business->cover_image) {
            $oldPath = $this->extractS3PathFromUrl($this->business->cover_image);
            if ($oldPath && Storage::disk('s3')->delete($oldPath)) {
                $this->business->cover_image = null;
                $this->business->save();
                session()->flash('message', 'Cover image removed successfully!');
            } else {
                \Log::warning('Failed to delete cover image from S3', ['path' => $oldPath]);
                session()->flash('message', 'Failed to remove cover image. Please try again.');
            }
        }
    }

    public function removeProfileImage(): void
    {
        if ($this->business && $this->business->profile_image) {
            $oldPath = $this->extractS3PathFromUrl($this->business->profile_image);
            if ($oldPath && Storage::disk('s3')->delete($oldPath)) {
                $this->business->profile_image = null;
                $this->business->save();
                session()->flash('message', 'Profile image removed successfully!');
            } else {
                \Log::warning('Failed to delete profile image from S3', ['path' => $oldPath]);
                session()->flash('message', 'Failed to remove profile image. Please try again.');
            }
        }
    }

    // ===== HELPER METHODS =====

    protected function uploadBase64ToS3(string $dataUrl, string $folder): string
    {
        // Validate base64 format
        if (!preg_match('/^data:image\/(\w+);base64,(.*)$/', $dataUrl, $matches)) {
            throw new \InvalidArgumentException('Invalid base64 image format');
        }

        $mimeType = $matches[1];
        $base64 = $matches[2];

        // Map mime types to extensions
        $extensionMap = [
            'jpeg' => 'jpg',
            'jpg' => 'jpg',
            'png' => 'png',
            'gif' => 'gif',
            'svg+xml' => 'svg',
            'webp' => 'webp',
        ];

        $extension = $extensionMap[$mimeType] ?? 'jpg';
        $binary = base64_decode($base64);

        if ($binary === false) {
            throw new \InvalidArgumentException('Failed to decode base64 string');
        }

        $filename = Str::random(40) . '.' . $extension;
        $path = "{$folder}/{$filename}";

        Storage::disk('s3')->put($path, $binary, 'public');

        return $path;
    }

    protected function extractS3PathFromUrl(string $fullUrl): ?string
    {
        // Get base URL of S3 disk (e.g., https://bucket.s3.region.amazonaws.com)
        $baseUrl = rtrim(Storage::disk('s3')->url(''), '/');

        // If URL starts with base URL, extract path
        if (str_starts_with($fullUrl, $baseUrl)) {
            $path = substr($fullUrl, strlen($baseUrl));
            return ltrim($path, '/');
        }

        // Fallback: if it looks like a path (no http), return as-is
        if (!str_starts_with($fullUrl, 'http')) {
            return ltrim($fullUrl, '/');
        }

        return null;
    }

    public function render(): Factory|View
    {
        return view('livewire.buzprofile');
    }
}
