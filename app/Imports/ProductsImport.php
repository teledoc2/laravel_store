<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductsImport implements OnEachRow, WithHeadingRow
{

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $product = Product::updateOrCreate(
            ['id' => $row["id"]],
            $row
        );

        //
        if ($row["menus_id"] != null) {
            $menusIds = explode(",", $row["menus_id"]);
            $product->menus()->sync($menusIds);
        }
    }
}
