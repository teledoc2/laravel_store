<?php

namespace App\Http\Livewire\Tables;

use App\Models\Coupon;
use Kdion4891\LaravelLivewireTables\Column;

class CouponTable extends BaseTableComponent
{

    public $model = Coupon::class;
    public $header_view = 'components.buttons.new';

    public function query()
    {
        return Coupon::with('products','vendors');
    }

    public function columns()
    {
        return [
            Column::make('ID'),
            Column::make('Code')->searchable()->sortable(),
            Column::make('Discount')->view('components.table.coupon_discount_price')->searchable()->sortable(),
            Column::make('Description')->view('components.table.short_description'),
            Column::make('Expires On', 'formatted_expires_on'),
            Column::make('Active')->view('components.table.active'),
            Column::make('Actions')->view('components.buttons.coupon_actions'),
        ];
    }
}
