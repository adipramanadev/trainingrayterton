<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SalesItem;

class Sales extends Model
{
    //
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Sales_Item::class);
    }
}
