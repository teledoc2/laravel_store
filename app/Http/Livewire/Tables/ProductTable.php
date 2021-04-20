<?php

namespace App\Http\Livewire\Tables;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Kdion4891\LaravelLivewireTables\Column;

class ProductTable extends BaseTableComponent
{

    public $model = Product::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        if( User::find( Auth::id() )->hasRole('admin') ){
            return Product::query();
        }else{
            return Product::where("vendor_id",Auth::user()->vendor_id );
        }
    }

    public function columns()
    {
        return [
            Column::make('ID')->searchable()->sortable(),
            Column::make('Image')->view('components.table.image_sm'),
            Column::make('Name')->searchable()->sortable(),
            Column::make('Price')->view('components.table.price')->searchable()->sortable(),
            Column::make('Discount Price')->view('components.table.discount_price')->searchable()->sortable(),
            Column::make('Capacity')->view('components.table.capacity'),
            Column::make('Available Qty')->sortable(),
            Column::make('Active')->view('components.table.active'),
            Column::make('Created At', 'formatted_date'),
            Column::make('Actions')->view('components.buttons.product_actions'),
        ];
    }
}
