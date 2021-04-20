<?php

namespace App\Http\Livewire\Tables;

use App\Models\Payout;
use Kdion4891\LaravelLivewireTables\Column;

class PayoutTable extends BaseTableComponent
{

    public $model = Payout::class;
    public $type;


    public function mount(){

        //
        $this->setTableProperties();
    }


    public function query()
    {
        return Payout::with('user','earning.user','earning.vendor')->when( $this->type == "vendors", function($query){
            return $query->whereHas('earning', function($query){
                return $query->whereNotNull('vendor_id');
            });
        }, function($query){
            return $query->whereHas('earning', function($query){
                return $query->whereNotNull('user_id');
            });
        });
    }

    public function columns()
    {

        $columns = [
            Column::make('ID'),
            Column::make('Amount')->view('components.table.price')->searchable()->sortable(),
        ];


        if( $this->type == "vendors" ){
            array_push($columns, Column::make('Vendor','earning.vendor.name')->searchable());
        }else{
            array_push($columns, Column::make('Driver','earning.user.name')->searchable());
        }

        array_push($columns, Column::make('Paid By', 'user.name'));
        array_push($columns, Column::make('Paid On', 'formatted_date'));
        return $columns;
    }
}
