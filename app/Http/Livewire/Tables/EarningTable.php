<?php

namespace App\Http\Livewire\Tables;

use App\Models\Earning;
use Kdion4891\LaravelLivewireTables\Column;
use Illuminate\Support\Facades\Auth;

class EarningTable extends BaseTableComponent
{

    public $model = Earning::class;
    public $type;


    public function mount(){

        //
        $this->setTableProperties();
    }


    public function query()
    {
        return Earning::with('user','vendor')->when( $this->type == "vendors", function($query){
            return $query->whereNotNull('vendor_id');
        }, function($query){
            return $query->whereNotNull('user_id');
        });
    }

    public function columns()
    {

        $columns = [
            Column::make('ID'),
            Column::make('Amount')->view('components.table.price')->searchable()->sortable(),
        ];


        if( $this->type == "vendors" ){
            array_push($columns, Column::make('Vendor','vendor.name')->searchable());
        }else{
            array_push($columns, Column::make('Driver','user.name')->searchable());
        }

        array_push($columns, Column::make('Updated At', 'formatted_updated_date'));
        array_push($columns, Column::make('Actions')->view('components.buttons.payout'));
        return $columns;
    }
}
