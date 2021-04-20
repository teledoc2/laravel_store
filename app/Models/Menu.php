<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends BaseModel
{

    protected $fillable = [
        "id","name","vendor_id","is_active"
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

}