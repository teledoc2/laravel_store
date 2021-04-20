<?php

namespace App\Models;

use Malhal\Geographical\Geographical;
use Illuminate\Support\Facades\Auth;
use willvincent\Rateable\Rateable;

class Vendor extends BaseModel
{

    use  Geographical, Rateable;
    protected static $kilometers = true;

    protected $appends = ['formatted_date', 'logo', 'feature_image', 'type', 'rating', 'can_rate'];

    protected $fillable = [
        "id","name","description","delivery_fee","delivery_range","tax","phone","email","address","latitude","longitude","commission","pickup","delivery","is_active","charge_per_km","is_open","is_package_vendor"
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->useFallbackUrl(''.url('').'/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
        $this
            ->addMediaCollection('feature_image')
            ->useFallbackUrl(''.url('').'/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
    }

    public function scopeIsPackageDelivery($query){
        return $query->where('is_package_vendor', 1);
    }

    public function scopeRegularVendor($query){
        return $query->where('is_package_vendor', 0);
    }

    public function scopeMine($query){
        return $query->when( Auth::user()->hasRole('manager'), function($query){
            return $query->where('id', Auth::user()->vendor_id );
        });
    }

    public function getLogoAttribute(){
        return $this->getFirstMediaUrl('logo');
    }
    public function getFeatureImageAttribute(){
        return $this->getFirstMediaUrl('feature_image');
    }

    public function getTypeAttribute()
    {
        return !$this->is_package_vendor ? "Regular" : "Package";
    }

    public function getRatingAttribute()
    {
        return  (int) ($this->averageRating ?? 3);
    }

    public function getCanRateAttribute()
    {

        if( empty( Auth::user() ) ){
            return false;
        }
        //
        $vendorReview = Review::where('user_id', Auth::id() )->where('vendor_id', $this->id )->first();
        return empty($vendorReview);
    }

    public function earning()
    {
        return $this->hasOne('App\Models\Earning', 'vendor_id', 'id');
    }

    public function managers()
    {
        return $this->hasMany('App\Models\User', 'vendor_id', 'id');
    }

    public function sales(){
        return $this->hasMany('App\Models\Order', 'vendor_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
    }

    public function menus()
    {
        return $this->hasMany('App\Models\Menu');
    }

    // public function product_menus()
    // {

    //     return $this->hasManyThrough(
    //         Menu::class,
    //         Product::class,
    //         'vendor_id', // Foreign key on the Product table...
    //         'id', // Foreign key on the Category table...
    //         'id', // Local key on the product table...
    //         'menu_id' // Local key on the Product table...
    //     )->groupBy('id')->distinct();
    // }

    public function package_types_pricing()
    {
        return $this->hasMany('App\Models\PackageTypePricing', 'vendor_id', 'id');
    }

}
