<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;




class AppSettingsController extends Controller
{

    public function index(Request $request)
    {
        //
        $currency = Currency::where('country_code', setting("currencyCountryCode", "GH"))->first();
        return response()->json([

            "colors" => setting("appColorTheme"),
            "strings" => [
                "google_maps_key" => setting("googleMapKey", ""),
                "fcm_key" => setting('fcmServerKey', ""),
                "app_name" => setting('appName', ""),
                "company_name" => setting('websiteName', ""),
                "enble_otp" => setting('enableOTP', "1"),
                "currency" => $currency->symbol,
                "country_code" => $currency->country_code,
            ],

        ]);
    }
}
