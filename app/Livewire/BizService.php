<?php
// app/Livewire/ServiceManager.php
namespace App\Livewire;

use App\Models\Profile;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BizService extends Component
{
    use WithFileUploads;

    public $profile;
    public $services = [];
    public $newService = [
        'name' => '',
        'description' => '',
        'price' => '',
        'image' => null,
        'is_available' => true
    ];
    public $editingServiceId = null;
    public $serviceImage;

    protected $rules = [
        'name' => 'required|min:2',
        'description' => 'nullable|min:10',
        'price' => 'nullable|numeric|min:0',
        'is_available' => 'boolean',
        'serviceImage' => 'nullable|image|max:5120',
    ];

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
            'serviceImage' => 'nullable|image|max:5120',
        ]);

        $serviceData = [
            'profile_id' => $this->profile->id,
            'name' => $this->newService['name'],
            'description' => $this->newService['description'],
            'price' => $this->newService['price'],
            'is_available' => $this->newService['is_available'],
        ];

        // Handle image upload
        if ($this->serviceImage) {
            $imagePath = $this->serviceImage->store('business/services', 's3', ['visibility' => 'public']);
            $serviceData['image'] = Storage::disk('s3')->url($imagePath);
        }

        if ($this->editingServiceId) {
            // Update existing service
            $service = Service::find($this->editingServiceId);
            if ($service) {
                $service->update($serviceData);
            }
            $this->editingServiceId = null;
        } else {
            // Create new service
            Service::create($serviceData);
        }

        // Reset form
        $this->reset(['newService', 'serviceImage']);
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
                'is_available' => $service->is_available
            ];
            $this->editingServiceId = $serviceId;
        }
    }

    public function deleteService($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service) {
            // Delete image from S3 if exists
            if ($service->image) {
                $path = str_replace(env('AWS_URL') . '/', '', $service->image);
                \Illuminate\Support\Facades\Storage::disk('s3')->delete($path);
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
        $this->reset(['newService', 'editingServiceId', 'serviceImage']);
    }

    public function render()
    {
        return view('livewire.biz-service');
    }
}
