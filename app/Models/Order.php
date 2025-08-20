<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUlids, HasFactory;

    protected $attributes = [
        'status' => 'initiated',
    ];

    protected $fillable = [
        'client_name',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
