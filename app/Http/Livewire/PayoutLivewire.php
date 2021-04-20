<?php

namespace App\Http\Livewire;

class PayoutLivewire extends BaseLivewireComponent
{


    public $type;
    protected $queryString = ['type'];

    public function render()
    {
        return view('livewire.payouts');
    }



}
