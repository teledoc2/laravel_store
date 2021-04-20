<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        return Product::when($request->type == "best", function ($query) {
            return $query->withCount('sales')->orderBy('sales_count', 'DESC');
        })
            ->when($request->keyword, function ($query) use ($request) {
                return $query->where('name', "like", "%" . $request->keyword . "%");
            })
            ->when($request->category_id, function ($query) use ($request) {
                return $query->where('category_id', "=", $request->category_id);
            })
            ->when($request->is_open, function ($query) use ($request) {
                return $query->where('is_open', "=", $request->is_open);
            })
            ->when($request->type == "you", function ($query) {

                if (auth('sanctum')->user()) {
                    return $query->whereHas('purchases')->withCount('purchases')->orderBy('purchases_count', 'DESC');
                } else {
                    return $query->inRandomOrder();
                }
            })
            ->when($request->type == "vendor", function ($query) {
                return $query->where('vendor_id', "=", auth('api')->user()->vendor_id);
            })
            ->paginate($this->perPage);
    }

    public function show(Request $request, $id)
    {

        try {
            return Product::with(['option_groups' => function ($query) use ($id) {
                $query->with(['options' => function ($q) use ($id) {
                    $q->where('product_id', "=", $id);
                }]);
            }])->findorfail($id);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage() ?? "No Product Found"
            ], 400);
        }
    }
}
