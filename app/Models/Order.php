<?php

namespace App\Models;

use App\Traits\FirebaseMessagingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class Order extends BaseModel
{

    use FirebaseMessagingTrait;

    protected $fillable = ["status","note", "type", "sub_total", "driver_id"];
    protected $with = ["user"];
    protected $appends = ["payment_link", 'formatted_date', 'formatted_type', 'can_rate'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->code = Str::random(10);
            $model->user_id = Auth::id();
        });

        //
        self::created(function ($model) {
            //sending notifications base on status change of the order
            $model->sendOrderStatusChangeNotification($model);
        });

        //
        self::updated(function ($model) {
            //sending notifications base on status change of the order
            $model->sendOrderStatusChangeNotification($model);
            $model->updateEarning();
            $model->updateWallet();
        });
    }

    public function scopeFullData($query)
    {
        return $query->with("products.product", "user", "driver", "delivery_address", "payment_method", "vendor", 'package_type', 'pickup_location', 'dropoff_location');
    }

    public function scopeMine($query){
        return $query->when( Auth::user()->hasRole('manager'), function($query){
            return $query->where('vendor_id', Auth::user()->vendor_id );
        });
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    public function delivery_address()
    {
        return $this->belongsTo('App\Models\DeliveryAddress', 'delivery_address_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'id', 'order_id');
    }


    //
    public function package_type()
    {
        return $this->belongsTo('App\Models\PackageType', 'package_type_id', 'id');
    }

    public function pickup_location()
    {
        return $this->belongsTo('App\Models\DeliveryAddress', 'pickup_location_id', 'id');
    }

    public function dropoff_location()
    {
        return $this->belongsTo('App\Models\DeliveryAddress', 'dropoff_location_id', 'id');
    }





    //
    public function getCanRateAttribute()
    {

        if( empty( Auth::user() ) ){
            return false;
        }
        //
        $vendorReview = Review::where('user_id', Auth::id() )->where('order_id', $this->id )->first();
        return empty($vendorReview);
    }

    public function getPaymentLinkAttribute()
    {

        if ($this->payment_status == "pending") {
            return route('order.payment', ["code" => $this->code]);
        } else {
            return "";
        }
    }

    //
    public function getFormattedTypeAttribute()
    {
        return Str::ucfirst($this->type);
    }
    public function getIsPackageAttribute()
    {
        return $this->type == "package";
    }





    //updating earning of vendor & driver
    public function updateEarning(){
        //'pending','preparing','ready','enroute','delivered','failed','cancelled'
        if( $this->status == 'delivered' ){

            //update vendor earning
            $earning = Earning::firstOrCreate(
                ['vendor_id' => $this->vendor_id],
                ['amount' => 0]
            );

            $systemCommission = ($this->vendor->commission / 100) * $this->total;
            //minus our commission
            $earning->amount += $this->total - $systemCommission;
            $earning->save();

            

            //update driver
            if( !empty($this->driver_id) ){

                $earning = Earning::firstOrCreate(
                    ['user_id' => $this->driver_id],
                    ['amount' => 0]
                );

                $driver = User::find($this->driver_id);
                $earnedAmount = ($driver->commission / 100) * $this->total;
                $earning->amount = $earning->amount + $earnedAmount;
                $earning->save();

            }
        }
    }

    //updating wallet balance is order failed and was paid via wallet
    public function updateWallet(){
        //'pending','preparing','ready','enroute','delivered','failed','cancelled'
        if( in_array($this->status, ['failed', 'cancelled'] ) && $this->payment_method->slug != "cash" ){

            //update user wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $this->user_id],
                ['balance' => 0]
            );

            //
            $wallet->balance += $this->total;
            $wallet->save();

        }
    }
}
