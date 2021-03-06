<?php

namespace App\Models;

class Category extends BaseModel
{

    protected $fillable = [
        'id','name', 'is_active'
    ];

    public function vendors()
    {
        return $this->hasMany('App\Models\Vendor');
    }

}
