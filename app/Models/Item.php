<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUlids;

    protected $fillable = [
        'description',
        'quantity',
        'unit_price',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
