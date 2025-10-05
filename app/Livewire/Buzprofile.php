<?php
namespace App\Livewire;

use App\Models\Profile;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Buzprofile extends Component
{
    // Individual properties for form binding
    public $business_name;
    public $slogan;
    public $description;
    public $phone;
    public $email;
    public $website;
    public $location;
    public $is_published = false;
    public $social_links = [];

    public $business; // Keep this for image operations

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
        // File rules applied manually in saveProfile()
    ];

    public function mount(): void
    {
        $this->business = Profile::where('user_id', auth()->id())->first();

        if ($this->business) {
            // Populate form fields with existing data
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
            // Initialize empty social links for new profile
            $this->social_links = [
                'facebook' => '',
                'instagram' => '',
                'twitter' => '',
                'linkedin' => ''
            ];
        }
    }

    public function saveProfile(Request $request): void
    {
        // Validate text fields first
        $this->validate();

        // Manual file validation (like your ProjectForms example)
        $request->validate([
            'coverImage' => 'nullable|image|max:5120', // 5MB
            'profileImage' => 'nullable|image|max:5120',
        ]);

        // Check if we're updating existing profile or creating new one
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

        // Handle S3 image uploads (direct from request, like your example's Cloudinary)
        try {
            $coverFile = $request->file('coverImage');
            if ($coverFile) {
                if ($this->business->cover_image) {
                    // Extract just the path from the full URL
                    $oldPath = str_replace(Storage::disk('s3')->url(''), '', $this->business->cover_image);
                    if (!Storage::disk('s3')->delete($oldPath)) {
                        \Log::warning('Failed to delete old cover image: ' . $oldPath);
                    }
                }
                $coverPath = $coverFile->store('business/covers', 's3', ['visibility' => 'public']);
                $this->business->cover_image = Storage::disk('s3')->url($coverPath);
                \Log::info('Cover image uploaded successfully to: ' . $coverPath);
            }

            $profileFile = $request->file('profileImage');
            if ($profileFile) {
                if ($this->business->profile_image) {
                    // Extract just the path from the full URL
                    $oldPath = str_replace(Storage::disk('s3')->url(''), '', $this->business->profile_image);
                    if (!Storage::disk('s3')->delete($oldPath)) {
                        \Log::warning('Failed to delete old profile image: ' . $oldPath);
                    }
                }
                $profilePath = $profileFile->store('business/profiles', 's3', ['visibility' => 'public']);
                $this->business->profile_image = Storage::disk('s3')->url($profilePath);
                \Log::info('Profile image uploaded successfully to: ' . $profilePath);
            }

            $this->business->save();

        } catch (\Exception $e) {
            \Log::error('Profile save failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'cover_name' => $coverFile?->getClientOriginalName() ?? null,
                'profile_name' => $profileFile?->getClientOriginalName() ?? null,
            ]);

            session()->flash('error', 'Image upload failed: ' . $e->getMessage());
            return; // Bail out on failure
        }

        $action = $this->business->wasRecentlyCreated ? 'created' : 'updated';
        session()->flash('message', "Profile {$action} successfully!");
    }

    public function selectTheme($themeName): void
    {
        if ($this->business && array_key_exists($themeName, $this->themes)) {
            $this->business->theme_colors = $this->themes[$themeName];
            $this->business->save();

            // Refresh the business data to show the updated theme
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

    // Remove cover image
    public function removeCoverImage(): void
    {
        if ($this->business && $this->business->cover_image) {
            // Extract just the path from the full URL
            $oldPath = str_replace(Storage::disk('s3')->url(''), '', $this->business->cover_image);
            if (Storage::disk('s3')->delete($oldPath)) {
                $this->business->cover_image = null;
                $this->business->save();
                session()->flash('message', 'Cover image removed successfully!');
            } else {
                \Log::warning('Failed to delete cover image from S3: ' . $oldPath);
                session()->flash('message', 'Failed to remove cover image. Please try again.');
            }
        }
    }

    // Remove profile image
    public function removeProfileImage(): void
    {
        if ($this->business && $this->business->profile_image) {
            // Extract just the path from the full URL
            $oldPath = str_replace(Storage::disk('s3')->url(''), '', $this->business->profile_image);
            if (Storage::disk('s3')->delete($oldPath)) {
                $this->business->profile_image = null;
                $this->business->save();
                session()->flash('message', 'Profile image removed successfully!');
            } else {
                \Log::warning('Failed to delete profile image from S3: ' . $oldPath);
                session()->flash('message', 'Failed to remove profile image. Please try again.');
            }
        }
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.buzprofile');
    }
}
