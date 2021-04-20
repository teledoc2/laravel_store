<?php

namespace App\Http\Livewire;

use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\DB;

class CountryLivewire extends BaseLivewireComponent
{

    //
    public $model = Country::class;

    //
    public $name;

    protected $rules = [
        "name" => "required|string",
    ];


    public function render()
    {
        return view('livewire.countries');
    }



}
