<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\CityVendor;
use App\Models\DeliveryAddress;
use App\Models\PackageTypePricing;
use App\Models\Vendor;
use Illuminate\Http\Request;


class PackageOrderController extends Controller
{

    //
    public function summary(Request $request)
    {

        //check if delivery addresses are with vendor cities
        $pickupLocation = DeliveryAddress::find($request->pickup_location_id);
        $dropoffLocation = DeliveryAddress::find($request->dropoff_location_id);

        //check iof city is even in the system
        $pickupLocationCity = City::where('name', $pickupLocation->city)->first();
        $dropoffLocationCity = City::where('name', $dropoffLocation->city)->first();

        if (empty($pickupLocationCity)) {
            return response()->json([
                "message" => "System does not service pickup location",
            ], 400);
        } else if (empty($dropoffLocationCity)) {
            return response()->json([
                "message" => "System does not service dropoff location",
            ], 400);
        }


        //check if vendor service the city
        $pickupLocationCityVendor = CityVendor::where('vendor_id', $request->vendor_id)
            ->where('city_id', $pickupLocationCity->id)
            ->first();
        $dropoffLocationCityVendor = CityVendor::where('vendor_id', $request->vendor_id)
        ->where('city_id', $dropoffLocationCity->id)
        ->first();

        if (empty($pickupLocationCityVendor)) {
            return response()->json([
                "message" => "Vendor does not service pickup location",
            ], 400);
        } else if (empty($dropoffLocationCityVendor)) {
            return response()->json([
                "message" => "Vendor does not service dropoff location",
            ], 400);
        }


        //
        $packageTypePricing = PackageTypePricing::where('vendor_id', $request->vendor_id)
            ->where('package_type_id', $request->package_type_id)->first();

        //dropoff location distance calculation
        $deliveryLocationDistance = DeliveryAddress::distance($dropoffLocation->latitude, $dropoffLocation->longitude)
            ->where('id', $request->pickup_location_id)
            ->first()
            ->distance;

        //calculation time
        $tax = Vendor::find($request->vendor_id)->tax;;
        $sizeAmount = 0;
        $distanceAmount = 0;
        $totalAmount = 0;

        //calculate the weigth price
        if ($packageTypePricing->price_per_kg) {
            $sizeAmount = $packageTypePricing->size_price * $request->weight;
        } else {
            $sizeAmount = $packageTypePricing->size_price;
        }


        //calculate the distance price
        if ($packageTypePricing->price_per_km) {
            $distanceAmount = $packageTypePricing->distance_price * $deliveryLocationDistance;
        } else {
            $distanceAmount = $packageTypePricing->distance_price;
        }

        //total amount
        $totalAmount = $tax + $distanceAmount + $sizeAmount;

        return response()->json([
            "delivery_fee" => $distanceAmount,
            "package_type_fee" => $sizeAmount,
            "distance" => $deliveryLocationDistance,
            "sub_total" => $totalAmount - $tax,
            "tax" => (double)$tax,
            "total" => $totalAmount,
        ]);
    }
}
