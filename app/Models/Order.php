<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'city',
        'country',
        'postal_code',
        'mobile_number',
        'email',
        'order_notes',
        'transaction_id',
        'order_status',
    ];

    protected $casts = [
        'order_status' => 'boolean',
    ];
}

