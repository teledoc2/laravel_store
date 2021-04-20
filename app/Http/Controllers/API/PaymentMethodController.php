<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    public function index(Request $request)
    {

        $paymentMethods = PaymentMethod::active()->get();
        return response()->json([
            "data" => $paymentMethods
        ], 200);
    }
}
