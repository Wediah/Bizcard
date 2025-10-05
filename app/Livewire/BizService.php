<?php

namespace App\Livewire;

use App\Models\Profile;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class BizService extends Component
{
    // Remove: use WithFileUploads;

    public $profile;
    public $services = [];
    public $newService = [
        'name' => '',
        'description' => '',
        'price' => '',
        'image' => '', // Will hold base64 string
        'is_available' => true,
    ];
    public $editingServiceId = null;

    // For display only
    public $serviceImageBase64 = '';
    public $serviceFileName = '';

    protected $listeners = ['setServiceFileName'];

    public function mount()
    {
        $this->profile = Profile::where('user_id', auth()->id())->first();
        if ($this->profile) {
            $this->services = $this->profile->services()->get()->toArray();
        }

        if (!$this->profile) {
            $this->redirectRoute('profile');
        }
    }

    public function addService()
    {
        $this->validate([
            'newService.name' => 'required|min:2',
            'newService.description' => 'nullable|min:10',
            'newService.price' => 'nullable|numeric|min:0',
            'newService.is_available' => 'boolean',
        ]);

        $serviceData = [
            'profile_id' => $this->profile->id,
            'name' => $this->newService['name'],
            'description' => $this->newService['description'],
            'price' => $this->newService['price'],
            'is_available' => $this->newService['is_available'],
        ];

        // Handle base64 image upload
        if ($this->serviceImageBase64) {
            try {
                $path = $this->uploadBase64ToS3($this->serviceImageBase64, 'business/services');
                $serviceData['image'] = Storage::disk('s3')->url($path);
            } catch (\Exception $e) {
                \Log::error('Service image upload failed', ['error' => $e->getMessage()]);
                $this->addError('serviceImageBase64', 'Image upload failed. Please try again.');
                return;
            }
        }

        if ($this->editingServiceId) {
            $service = Service::find($this->editingServiceId);
            if ($service) {
                // Delete the old image if replaced
                if ($this->serviceImageBase64 && $service->image) {
                    $oldPath = $this->extractS3PathFromUrl($service->image);
                    if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
                        Storage::disk('s3')->delete($oldPath);
                    }
                }
                $service->update($serviceData);
            }
            $this->editingServiceId = null;
        } else {
            Service::create($serviceData);
        }

        $this->reset(['newService', 'serviceImageBase64', 'serviceFileName']);
        $this->services = $this->profile->fresh()->services()->get()->toArray();
        session()->flash('message', 'Service saved successfully!');
    }

    public function editService($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service) {
            $this->newService = [
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'is_available' => $service->is_available,
            ];
            $this->editingServiceId = $serviceId;
        }
    }

    public function deleteService($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service) {
            if ($service->image) {
                $path = $this->extractS3PathFromUrl($service->image);
                if ($path) {
                    Storage::disk('s3')->delete($path);
                }
            }
            $service->delete();
            $this->services = $this->profile->fresh()->services()->get()->toArray();
            session()->flash('message', 'Service deleted successfully!');
        }
    }

    public function toggleServiceAvailability($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service) {
            $service->update(['is_available' => !$service->is_available]);
            $this->services = $this->profile->fresh()->services()->get()->toArray();
        }
    }

    public function cancelEdit()
    {
        $this->reset(['newService', 'editingServiceId', 'serviceImageBase64', 'serviceFileName']);
    }

    // ===== HELPER METHODS =====

    protected function uploadBase64ToS3(string $dataUrl, string $folder): string
    {
        if (!preg_match('/^data:image\/(\w+);base64,(.*)$/', $dataUrl, $matches)) {
            throw new \InvalidArgumentException('Invalid base64 image');
        }

        $mimeType = $matches[1];
        $base64 = $matches[2];
        $extensionMap = ['jpeg' => 'jpg', 'jpg' => 'jpg', 'png' => 'png', 'gif' => 'gif', 'svg+xml' => 'svg', 'webp' => 'webp'];
        $extension = $extensionMap[$mimeType] ?? 'jpg';
        $binary = base64_decode($base64);

        if ($binary === false) {
            throw new \InvalidArgumentException('Failed to decode base64');
        }

        $filename = Str::random(40) . '.' . $extension;
        $path = "{$folder}/{$filename}";
        Storage::disk('s3')->put($path, $binary, 'public');
        return $path;
    }

    protected function extractS3PathFromUrl(string $fullUrl): ?string
    {
        $baseUrl = rtrim(Storage::disk('s3')->url(''), '/');
        if (str_starts_with($fullUrl, $baseUrl)) {
            return ltrim(substr($fullUrl, strlen($baseUrl)), '/');
        }
        if (!str_starts_with($fullUrl, 'http')) {
            return ltrim($fullUrl, '/');
        }
        return null;
    }

    public function setServiceFileName($name)
    {
        $this->serviceFileName = $name;
    }

    public function render()
    {
        return view('livewire.biz-service');
    }
}
