<?php

namespace App\Models;


class PackageTypePricing extends BaseModel
{
    public function package_type()
    {
        return $this->belongsTo('App\Models\PackageType', 'package_type_id', 'id');
    }
}
