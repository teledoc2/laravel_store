<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Vendor;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorLivewire extends BaseLivewireComponent
{

    //
    public $model = Vendor::class;

    //
    public $name;
    public $description;
    public $delivery_fee;
    public $charge_per_km;
    public $delivery_range;
    public $phone;
    public $email;
    public $address;
    public $latitude;
    public $longitude;
    public $commission;
    public $tax;
    public $pickup;
    public $delivery;
    public $isActive;
    public $is_package_vendor;

    //
    public $managersIDs;
    public $categoriesIDs;

    protected $rules = [
        "name" => "required|string",
        "description" => "required|string",
        "delivery_fee" => "nullable|sometimes|numeric",
        "delivery_range" => "nullable|sometimes|numeric",
        "phone" => "required|numeric",
        "email" => "required|email|unique:vendors,email",
        "address" => "required|string",
        "latitude" => "required|numeric",
        "longitude" => "required|numeric",
        "commission" => "nullable|sometimes|numeric",
        "tax" => "nullable|sometimes|numeric",
        "photo" => "required|image|max:1024",
        "secondPhoto" => "required|image|max:2048",
    ];


    protected $messages = [
        "photo.max" => "Logo must be not be more than 1MB",
        "photo.required" => "Logo is required",
        "secondPhoto.max" => "Feature Image must be not be more than 2MB",
        "secondPhoto.required" => "Feature Image is required",
        "email.unique" => "Email already used by another vendor",
    ];

    public function render()
    {

        return view('livewire.vendors', [
            "managers" => User::manager()->get(),
            "categories" => Category::active()->get(),
        ]);
    }


    public function showCreateModal()
    {
        $this->reset();
        $this->showCreate = true;
        $this->showSelect2("#categoriesSelect2", $this->categoriesIDs, "categoriesChange");
    }

    public function save()
    {
        //validate
        $this->validate();

        try {

            DB::beginTransaction();
            $model = new Vendor();
            $model->name = $this->name;
            $model->description = $this->description;
            $model->delivery_fee = $this->delivery_fee ?? 0;
            $model->charge_per_km = $this->charge_per_km ?? 0;
            $model->delivery_range = $this->delivery_range ?? 0;
            $model->phone = $this->phone;
            $model->email = $this->email;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->commission = $this->commission ?? 0;
            $model->tax = $this->tax ?? 0;
            $model->pickup = $this->pickup ?? 0;
            $model->delivery = $this->delivery ?? 0;
            $model->is_active = $this->isActive;
            $model->is_package_vendor = $this->is_package_vendor ?? false;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection();
                $model->addMedia($this->photo->getRealPath())->toMediaCollection("logo");
                $this->photo = null;
            }

            if ($this->secondPhoto) {

                $model->clearMediaCollection();
                $model->addMedia($this->secondPhoto->getRealPath())->toMediaCollection("feature_image");
                $this->secondPhoto = null;
            }

            //
            $model->categories()->attach($this->categoriesIDs);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert("Vendor created successfully!");
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Vendor creation failed!");
        }
    }

    // Updating model
    public function initiateEdit($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->name = $this->selectedModel->name;
        $this->description = $this->selectedModel->description;
        $this->delivery_fee = $this->selectedModel->delivery_fee;
        $this->delivery_range = $this->selectedModel->delivery_range;
        $this->phone = $this->selectedModel->phone;
        $this->email = $this->selectedModel->email;
        $this->address = $this->selectedModel->address;
        $this->latitude = $this->selectedModel->latitude;
        $this->longitude = $this->selectedModel->longitude;
        $this->commission = $this->selectedModel->commission;
        $this->tax = $this->selectedModel->tax;
        $this->pickup = $this->selectedModel->pickup;
        $this->delivery = $this->selectedModel->delivery;
        $this->isActive = $this->selectedModel->is_active;
        $this->is_package_vendor = $this->selectedModel->is_package_vendor;
        $this->charge_per_km = $this->selectedModel->charge_per_km;

        $this->categoriesIDs = $this->selectedModel->categories()->pluck('category_id');
        $this->showSelect2("#editCategoriesSelect2", $this->categoriesIDs, "categoriesChange");
        $this->emit('showEditModal');


    }

    public function update()
    {
        //validate
        $this->validate(
            [
                "name" => "required|string",
                "description" => "required|string",
                "delivery_fee" => "nullable|sometimes|numeric",
                "delivery_range" => "nullable|sometimes|numeric",
                "phone" => "required|numeric",
                "email" => "required|email|unique:vendors,email," . $this->selectedModel->id . "",
                "address" => "required|string",
                "latitude" => "required|numeric",
                "longitude" => "required|numeric",
                "commission" => "required|numeric",
                "tax" => "required|numeric",
                "photo" => "nullable|sometimes|image|max:1024",
                "secondPhoto" => "nullable|sometimes|image|max:2048",
            ]
        );

        try {

            DB::beginTransaction();
            $model = $this->selectedModel;
            $model->name = $this->name;
            $model->description = $this->description;
            $model->delivery_fee = $this->delivery_fee;
            $model->charge_per_km = $this->charge_per_km;
            $model->delivery_range = $this->delivery_range;
            $model->phone = $this->phone;
            $model->email = $this->email;
            $model->address = $this->address;
            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->commission = $this->commission;
            $model->tax = $this->tax;
            $model->pickup = $this->pickup;
            $model->delivery = $this->delivery;
            $model->is_active = $this->isActive;
            $model->is_package_vendor = $this->is_package_vendor;
            $model->save();

            if ($this->photo) {

                $model->clearMediaCollection("logo");
                $model->addMedia($this->photo->getRealPath())->toMediaCollection("logo");
                $this->photo = null;
            }

            if ($this->secondPhoto) {

                $model->clearMediaCollection("feature_image");
                $model->addMedia($this->secondPhoto->getRealPath())->toMediaCollection("feature_image");
                $this->secondPhoto = null;
            }

            //
            $model->categories()->sync($this->categoriesIDs);

            DB::commit();

            $this->dismissModal();
            $this->reset();
            $this->showSuccessAlert("Vendor updated successfully!");
            $this->emit('refreshTable');
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Vendor updated failed!");
        }
    }

    // Assigning managers
    public function initiateAssign($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->managersIDs = $this->selectedModel->managers->pluck('id');
        $this->emit('showAssignModal');
        $this->showSelect2("#managersSelect2", $this->managersIDs, "managersChange");
    }

    public function managersChange($data)
    {
        $this->managersIDs = $data;
    }

    public function categoriesChange($data)
    {
        $this->categoriesIDs = $data;
    }

    public function assignManagers()
    {

        try {

            DB::beginTransaction();

            //remove all managers
            User::where('vendor_id', $this->selectedModel->id)
                    ->update(['vendor_id' => null]);

            //assigning
            foreach ($this->managersIDs as $managerId) {
                $manager = User::findorfail($managerId);
                $manager->vendor_id = $this->selectedModel->id;
                $manager->save();
            }

            DB::commit();
            $this->emit('dismissModal');
            $this->showSuccessAlert("Vendor Managers updated successfully!");
        } catch (Exception $error) {
            DB::rollback();
            $this->showErrorAlert($error->getMessage() ?? "Vendor Managers update failed!");
        }
    }
}
